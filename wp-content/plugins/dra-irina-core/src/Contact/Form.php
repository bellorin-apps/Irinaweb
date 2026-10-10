<?php
/**
 * Formulario de contacto con minimización de datos (PLAN 8.4, D-050).
 *
 * - Recibe nombre, teléfono, motivo (categoría), correo opcional y mensaje opcional; exige consentimiento del aviso de privacidad.
 * - No guarda nada en la base de datos: envía un correo al consultorio y redirige a la página de gracias (noindex).
 * - Anti-abuso sin cookies ni captcha: honeypot, tiempo mínimo de llenado, límite por IP (transitorio con hash) y
 *   comprobación de origen. Compatible con la caché de página (no usa nonce).
 * - SMTP opcional por constantes en wp-config.php: DI_SMTP_HOST, DI_SMTP_PORT, DI_SMTP_USER, DI_SMTP_PASS, DI_SMTP_SECURE (ssl|tls),
 *   DI_SMTP_FROM, DI_SMTP_FROM_NAME. Sin ellas se usa wp_mail() normal.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Contact;

use DraIrina\Core\Settings\PracticeSettings;

final class Form {

	public const ACTION = 'di_contact';

	/** Motivos (categoría) que acepta el formulario: clave => etiqueta. */
	public const MOTIVOS = [
		'sueno'    => 'Ronquido o apnea del sueño',
		'oido'     => 'Oído',
		'nariz'    => 'Nariz y senos paranasales',
		'garganta' => 'Garganta y voz',
		'ninos'    => 'Niños',
		'otro'     => 'Otro / no lo sé',
	];

	private const MIN_SECONDS = 4;
	private const MAX_PER_HOUR = 5;

	public function register(): void {
		add_action( 'admin_post_nopriv_' . self::ACTION, [ $this, 'handle' ] );
		add_action( 'admin_post_' . self::ACTION, [ $this, 'handle' ] );
		add_action( 'phpmailer_init', [ $this, 'smtp' ] );
	}

	public static function action_url(): string {
		return admin_url( 'admin-post.php' );
	}

	public static function thanks_url(): string {
		$page = get_page_by_path( 'gracias', OBJECT, 'page' );
		return $page && 'publish' === $page->post_status ? (string) get_permalink( $page ) : home_url( '/' );
	}

	/** Procesa el envío (POST) tanto con JS (JSON) como sin él (redirección). */
	public function handle(): void {
		$ajax = isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'fetch' === strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) );
		$res  = $this->process();
		if ( $ajax ) {
			wp_send_json( $res, $res['ok'] ? 200 : 422 );
		}
		if ( $res['ok'] ) {
			wp_safe_redirect( self::thanks_url(), 303 );
			exit;
		}
		$back = wp_get_referer() ? remove_query_arg( [ 'di_error' ], wp_get_referer() ) : home_url( '/contacto/' );
		wp_safe_redirect( add_query_arg( 'di_error', rawurlencode( $res['error'] ?? 'form' ), $back ) . '#di-form', 303 );
		exit;
	}

	/**
	 * @return array{ok:bool,error?:string,message?:string}
	 */
	private function process(): array {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- sin nonce a propósito (páginas cacheadas); protección por honeypot, tiempo, origen y límite por IP.
		if ( ! $this->same_origin() ) {
			return [
				'ok'      => false,
				'error'   => 'origin',
				'message' => __( 'No pudimos validar el origen del envío. Vuelve a cargar la página e inténtalo de nuevo.', 'dra-irina-core' ),
			];
		}
		if ( '' !== sanitize_text_field( wp_unslash( $_POST['di_web'] ?? '' ) ) ) {
			// Honeypot relleno: se responde como éxito para no dar pistas, sin enviar nada.
			return [ 'ok' => true ];
		}
		$started = (int) sanitize_text_field( wp_unslash( $_POST['di_t'] ?? '0' ) );
		if ( $started > 0 && ( time() - $started ) < self::MIN_SECONDS ) {
			return [
				'ok'      => false,
				'error'   => 'fast',
				'message' => __( 'Revisa los datos e inténtalo de nuevo.', 'dra-irina-core' ),
			];
		}
		if ( $this->rate_limited() ) {
			return [
				'ok'      => false,
				'error'   => 'rate',
				'message' => __( 'Hemos recibido varios mensajes desde tu conexión. Espera unos minutos o escríbenos por WhatsApp.', 'dra-irina-core' ),
			];
		}

		$name    = $this->clean_text( sanitize_text_field( wp_unslash( $_POST['di_name'] ?? '' ) ), 80 );
		$phone   = preg_replace( '/[^\d+]/', '', sanitize_text_field( wp_unslash( $_POST['di_phone'] ?? '' ) ) ) ?? '';
		$raw_mail = sanitize_text_field( wp_unslash( $_POST['di_email'] ?? '' ) );
		$email    = sanitize_email( $raw_mail );
		$motivo  = sanitize_key( (string) wp_unslash( $_POST['di_motivo'] ?? '' ) );
		$message = $this->clean_text( sanitize_textarea_field( wp_unslash( $_POST['di_message'] ?? '' ) ), 800, true );
		$consent = ! empty( $_POST['di_consent'] );
		// phpcs:enable

		$digits = preg_replace( '/\D/', '', $phone ) ?? '';
		if ( '' === $name || mb_strlen( $name ) < 2 ) {
			return $this->invalid( 'name', __( 'Escribe tu nombre.', 'dra-irina-core' ) );
		}
		if ( strlen( $digits ) < 10 || strlen( $digits ) > 15 ) {
			return $this->invalid( 'phone', __( 'Escribe un teléfono válido de 10 dígitos (o con lada internacional).', 'dra-irina-core' ) );
		}
		if ( '' !== $raw_mail && ! is_email( $email ) ) {
			return $this->invalid( 'email', __( 'El correo no parece válido. Puedes dejarlo vacío.', 'dra-irina-core' ) );
		}
		if ( ! isset( self::MOTIVOS[ $motivo ] ) ) {
			return $this->invalid( 'motivo', __( 'Elige el motivo de tu consulta.', 'dra-irina-core' ) );
		}
		if ( ! $consent ) {
			return $this->invalid( 'consent', __( 'Necesitamos tu consentimiento para contactarte.', 'dra-irina-core' ) );
		}

		$sent = $this->send( $name, $phone, $email, self::MOTIVOS[ $motivo ], $message );
		if ( ! $sent ) {
			return [
				'ok'      => false,
				'error'   => 'mail',
				'message' => __( 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp y te atendemos enseguida.', 'dra-irina-core' ),
			];
		}
		$this->count();
		return [ 'ok' => true ];
	}

	/**
	 * @return array{ok:bool,error:string,message:string}
	 */
	private function invalid( string $field, string $message ): array {
		return [
			'ok'      => false,
			'error'   => $field,
			'message' => $message,
		];
	}

	private function clean_text( string $value, int $max, bool $multiline = false ): string {
		$value = $multiline ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		$value = preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $value ) ?? $value;
		return mb_substr( trim( $value ), 0, $max );
	}

	private function same_origin(): bool {
		$home   = wp_parse_url( home_url(), PHP_URL_HOST );
		$origin = isset( $_SERVER['HTTP_ORIGIN'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ORIGIN'] ) ), PHP_URL_HOST ) : null;
		$ref    = isset( $_SERVER['HTTP_REFERER'] ) ? wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ), PHP_URL_HOST ) : null;
		$host   = $origin ? $origin : $ref;
		if ( ! $host ) {
			return true; // Navegadores con política estricta de referer: no bloquear a personas reales.
		}
		return strtolower( (string) $host ) === strtolower( (string) $home ) || 'www.' . strtolower( (string) $home ) === strtolower( (string) $host );
	}

	private function ip_key(): string {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
		return 'di_contact_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	}

	private function rate_limited(): bool {
		return (int) get_transient( $this->ip_key() ) >= self::MAX_PER_HOUR;
	}

	private function count(): void {
		$key = $this->ip_key();
		$n   = (int) get_transient( $key );
		set_transient( $key, $n + 1, HOUR_IN_SECONDS );
	}

	/**
	 * Texto plano alternativo del último correo (multipart): lo lee el hook phpmailer_init.
	 *
	 * @var string
	 */
	private static string $alt_body = '';

	private function send( string $name, string $phone, string $email, string $motivo, string $message ): bool {
		$to = (string) PracticeSettings::get( 'email' );
		if ( '' === $to || ! is_email( $to ) ) {
			$to = (string) get_option( 'admin_email' );
		}
		$site    = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
		$subject = sprintf( '[%s] Nueva solicitud: %s · %s', $site, $motivo, $name );
		$page    = wp_get_referer() ? esc_url_raw( (string) wp_get_referer() ) : home_url( '/contacto/' );
		$when    = wp_date( "l j \\d\\e F, H:i" );
		$digits  = preg_replace( '/\D/', '', $phone ) ?? '';
		$wa      = 10 === strlen( $digits ) ? '52' . $digits : $digits;

		$text = implode(
			"\n",
			[
				'Nueva solicitud desde el formulario del sitio.',
				'',
				'Nombre:   ' . $name,
				'Teléfono: ' . $phone,
				'WhatsApp: https://wa.me/' . $wa,
				'Correo:   ' . ( '' !== $email ? $email : '(no indicado)' ),
				'Motivo:   ' . $motivo,
				'',
				'Mensaje:',
				'' !== $message ? $message : '(sin mensaje)',
				'',
				'Enviado: ' . $when . ' · Página: ' . $page,
				'',
				'Mensaje automático del sitio. No se guarda copia en el sitio web (minimización de datos). Responde por WhatsApp o teléfono.',
			]
		);
		$html = $this->html_mail( $name, $phone, $wa, $email, $motivo, $message, $page, $when );

		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		if ( '' !== $email ) {
			$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
		}
		$copy = sanitize_email( (string) PracticeSettings::get( 'contacto_copia' ) );
		if ( '' !== $copy && is_email( $copy ) && strtolower( $copy ) !== strtolower( $to ) ) {
			$headers[] = 'Bcc: ' . $copy;
		}
		self::$alt_body = $text;
		$sent           = wp_mail( $to, $subject, $html, $headers );
		self::$alt_body = '';
		return $sent;
	}

	/**
	 * Correo HTML (estilos inline, tablas, sin imágenes externas salvo el icono del sitio) con la identidad del consultorio.
	 */
	private function html_mail( string $name, string $phone, string $wa, string $email, string $motivo, string $message, string $page, string $when ): string {
		$p       = PracticeSettings::public_data();
		$site    = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
		$icon    = (string) get_site_icon_url( 96 );
		$centro  = (string) ( $p['centro'] ?? '' );
		$addr    = trim( ( $p['calle'] ?? '' ) . ( ! empty( $p['interior'] ) ? ', ' . $p['interior'] : '' ) . ( ! empty( $p['colonia'] ) ? ', ' . $p['colonia'] : '' ) . ( ! empty( $p['ciudad'] ) ? ', ' . $p['ciudad'] : '' ) );
		$font    = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;";
		$label   = 'style="padding:10px 0;border-bottom:1px solid #eee4dc;color:#75696f;font-size:13px;letter-spacing:.02em;vertical-align:top;width:110px;' . $font . '"';
		$value   = 'style="padding:10px 0;border-bottom:1px solid #eee4dc;color:#241f23;font-size:16px;line-height:1.4;vertical-align:top;' . $font . '"';
		$link    = 'style="color:#2a787d;text-decoration:none;font-weight:600;"';
		$esc     = static fn( string $v ): string => esc_html( $v );
		$rows    = [
			[ 'Nombre', $esc( $name ) ],
			[ 'Teléfono', sprintf( '<a href="tel:%1$s" %3$s>%2$s</a> &nbsp;·&nbsp; <a href="https://wa.me/%4$s" %3$s>WhatsApp</a>', esc_attr( $phone ), $esc( $phone ), $link, esc_attr( $wa ) ) ],
			[ 'Motivo', '<span style="display:inline-block;padding:4px 12px;border-radius:999px;background:#efe6f4;color:#6b3a86;font-size:14px;font-weight:600;">' . $esc( $motivo ) . '</span>' ],
			[ 'Correo', '' !== $email ? sprintf( '<a href="mailto:%1$s" %2$s>%1$s</a>', esc_attr( $email ), $link ) : '<span style="color:#75696f;">No indicado</span>' ],
		];
		$rows_html = '';
		foreach ( $rows as [ $k, $v ] ) {
			$rows_html .= '<tr><td ' . $label . '>' . $esc( $k ) . '</td><td ' . $value . '>' . $v . '</td></tr>';
		}
		$msg_html = '' !== $message
			? '<div style="margin-top:18px;padding:16px 18px;border-radius:14px;background:#fbf7f2;color:#241f23;font-size:16px;line-height:1.5;' . $font . '">' . nl2br( $esc( $message ) ) . '</div>'
			: '<p style="margin:18px 0 0;color:#75696f;font-size:14px;' . $font . '">Sin mensaje adicional.</p>';
		$icon_html = '' !== $icon ? '<img src="' . esc_url( $icon ) . '" width="44" height="44" alt="" style="display:block;border-radius:12px;margin-bottom:12px;">' : '';

		return '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width"><title>' . $esc( $site ) . '</title></head>'
			. '<body style="margin:0;padding:0;background:#f3ece5;">'
			. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3ece5;"><tr><td align="center" style="padding:28px 12px;">'
			. '<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;">'
			// Cabecera.
			. '<tr><td style="padding:8px 8px 18px;' . $font . '">' . $icon_html
			. '<div style="font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:#2a787d;font-weight:700;">Nueva solicitud desde el sitio</div>'
			. '<div style="font-size:24px;font-weight:700;color:#241f23;margin-top:6px;line-height:1.2;">Hola, Dra. <span style="color:#8c4eaa;">Irina</span> 👋</div>'
			. '<div style="font-size:15px;color:#5c525a;margin-top:6px;line-height:1.5;">Una persona pidió que la contacten desde <a href="' . esc_url( home_url( '/' ) ) . '" ' . $link . '>' . $esc( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a>. Aquí tienes sus datos.</div></td></tr>'
			// Tarjeta.
			. '<tr><td style="background:#ffffff;border-radius:20px;padding:22px 24px;box-shadow:0 6px 24px rgba(40,20,50,.06);">'
			. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0">' . $rows_html . '</table>'
			. $msg_html
			// Botones.
			. '<table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:22px;"><tr>'
			. '<td style="border-radius:999px;background:#2a787d;"><a href="https://wa.me/' . esc_attr( $wa ) . '" style="display:inline-block;padding:13px 22px;color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;border-radius:999px;' . $font . '">Responder por WhatsApp</a></td>'
			. '<td style="width:10px;"></td>'
			. '<td style="border-radius:999px;background:#f1e8f4;"><a href="tel:' . esc_attr( $phone ) . '" style="display:inline-block;padding:13px 22px;color:#6b3a86;font-size:15px;font-weight:700;text-decoration:none;border-radius:999px;' . $font . '">Llamar</a></td>'
			. '</tr></table>'
			. '</td></tr>'
			// Pie.
			. '<tr><td style="padding:18px 8px 0;color:#8a7f86;font-size:12px;line-height:1.6;' . $font . '">'
			. '<strong style="color:#5c525a;">' . $esc( $centro ) . '</strong>' . ( '' !== $addr ? '<br>' . $esc( $addr ) : '' )
			. '<br>Enviado el ' . $esc( $when ) . ' desde <a href="' . esc_url( $page ) . '" style="color:#8a7f86;">' . $esc( $page ) . '</a>.'
			. '<br>Mensaje automático del formulario de contacto. El sitio no guarda copia de los datos: responde por WhatsApp o por teléfono.'
			. '</td></tr>'
			. '</table></td></tr></table></body></html>';
	}

	/** SMTP por constantes (sin plugin) y texto plano alternativo para los correos HTML del formulario. */
	public function smtp( \PHPMailer\PHPMailer\PHPMailer $mailer ): void {
		if ( '' !== self::$alt_body ) {
			$mailer->AltBody = self::$alt_body; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- propiedad de PHPMailer.
		}
		if ( ! defined( 'DI_SMTP_HOST' ) || '' === (string) DI_SMTP_HOST ) {
			return;
		}
		// phpcs:disable WordPress.NamingConventions.ValidVariableName -- propiedades de PHPMailer.
		$mailer->isSMTP();
		$mailer->Host       = (string) DI_SMTP_HOST;
		$mailer->Port       = defined( 'DI_SMTP_PORT' ) ? (int) DI_SMTP_PORT : 465;
		$mailer->SMTPSecure = defined( 'DI_SMTP_SECURE' ) ? (string) DI_SMTP_SECURE : 'ssl';
		$mailer->SMTPAuth   = defined( 'DI_SMTP_USER' ) && '' !== (string) DI_SMTP_USER;
		if ( $mailer->SMTPAuth ) {
			$mailer->Username = (string) DI_SMTP_USER;
			$mailer->Password = defined( 'DI_SMTP_PASS' ) ? (string) DI_SMTP_PASS : '';
		}
		// phpcs:enable
		$from = defined( 'DI_SMTP_FROM' ) ? (string) DI_SMTP_FROM : ( defined( 'DI_SMTP_USER' ) ? (string) DI_SMTP_USER : '' );
		if ( '' !== $from && is_email( $from ) ) {
			$mailer->setFrom( $from, defined( 'DI_SMTP_FROM_NAME' ) ? (string) DI_SMTP_FROM_NAME : wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES ), false );
		}
	}
}
