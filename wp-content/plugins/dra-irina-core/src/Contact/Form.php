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

	private function send( string $name, string $phone, string $email, string $motivo, string $message ): bool {
		$to = (string) PracticeSettings::get( 'email' );
		if ( '' === $to || ! is_email( $to ) ) {
			$to = (string) get_option( 'admin_email' );
		}
		$site    = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
		$subject = sprintf( '[%s] Nueva solicitud: %s · %s', $site, $motivo, $name );
		$lines   = [
			'Nueva solicitud desde el formulario del sitio.',
			'',
			'Nombre:   ' . $name,
			'Teléfono: ' . $phone,
			'Correo:   ' . ( '' !== $email ? $email : '(no indicado)' ),
			'Motivo:   ' . $motivo,
			'',
			'Mensaje:',
			'' !== $message ? $message : '(sin mensaje)',
			'',
			'Enviado: ' . wp_date( 'Y-m-d H:i' ) . ' · Página: ' . ( wp_get_referer() ? esc_url_raw( (string) wp_get_referer() ) : '-' ),
			'',
			'Este correo no guarda copia en el sitio web (minimización de datos). Responde por WhatsApp o teléfono.',
		];
		$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
		if ( '' !== $email ) {
			$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
		}
		$copy = sanitize_email( (string) PracticeSettings::get( 'contacto_copia' ) );
		if ( '' !== $copy && is_email( $copy ) && strtolower( $copy ) !== strtolower( $to ) ) {
			$headers[] = 'Bcc: ' . $copy;
		}
		return wp_mail( $to, $subject, implode( "\n", $lines ), $headers );
	}

	/** SMTP por constantes (sin plugin). */
	public function smtp( \PHPMailer\PHPMailer\PHPMailer $mailer ): void {
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
