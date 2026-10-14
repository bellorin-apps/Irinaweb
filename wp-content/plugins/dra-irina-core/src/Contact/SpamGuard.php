<?php
/**
 * Protección antispam del formulario de contacto (propietario, 2026-10-14: «hay que instalarle un captcha y lo necesario»).
 *
 * Dos capas además de las que ya tenía Form (honeypot, tiempo mínimo, origen, límite por IP):
 * 1. Filtro de contenido: los mensajes con enlaces o con ofertas comerciales (SEO, marketing, diseño web, cripto…) se descartan
 *    en silencio, sin enviar correo. Las reglas son estrictas a propósito para no bloquear a pacientes.
 * 2. Cloudflare Turnstile (captcha sin rompecabezas, compatible con la caché de página): solo se activa si hay claves, por constantes
 *    en wp-config.php (DI_TURNSTILE_SITEKEY, DI_TURNSTILE_SECRET) o por opciones (di_turnstile_sitekey, di_turnstile_secret).
 *    Si Cloudflare no responde, el envío pasa (no se pierde a un paciente por una caída ajena) y queda anotado.
 *
 * Solo guarda contadores por motivo y la fecha del último descarte: ningún dato personal.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Contact;

final class SpamGuard {

	private const LOG_OPTION = 'di_contact_spam';

	/** Términos que solo aparecen en ofertas comerciales: uno basta. */
	private const STRONG = [
		'\bseo\b', 'backlinks?', 'guest ?posts?', 'link ?building', 'bitcoin', 'crypto', '\bcasino\b', '\bforex\b',
		'rank(?:ing|ings)? (?:on|in|higher on) google', 'first page (?:of|on) google', 'web ?site design', 'web design', 'digital marketing',
		'lead generation', 'virtual assistant', 'posicionamiento (?:web|seo|en google)', 'dise[ñn]o (?:web|de p[áa]ginas)',
		'agencia de marketing', 'marketing digital', 'p[áa]gina web profesional',
	];

	/** Palabras comerciales en inglés: cuentan solo si el mensaje está escrito en inglés. */
	private const COMMERCIAL_EN = [ 'website', 'business', 'services', 'customers', 'traffic', 'leads', 'package', 'packages', 'pricing', 'offer', 'proposal', 'strategy', 'rankings', 'organic' ];

	/** Palabras vacías del inglés para detectar el idioma de forma aproximada. */
	private const STOP_EN = [ 'the', 'and', 'your', 'our', 'we', 'with', 'for', 'you', 'this', 'that', 'would', 'are', 'can', 'will', 'let', 'me', 'know' ];

	/**
	 * Motivo de descarte o cadena vacía si el mensaje parece legítimo.
	 *
	 * @param string $name    Nombre.
	 * @param string $email   Correo.
	 * @param string $message Mensaje.
	 */
	public static function classify( string $name, string $email, string $message ): string {
		$text = mb_strtolower( $name . ' ' . $message );
		if ( preg_match( '~https?://|www\.[a-z0-9-]+\.[a-z]{2,}|\b[a-z0-9-]+\.(?:com|net|org|io|co|info|biz|xyz|online|site)(?:/|\b)~iu', $text ) ) {
			return 'link';
		}
		foreach ( self::STRONG as $pattern ) {
			if ( preg_match( '~' . $pattern . '~iu', $text ) ) {
				return 'commercial';
			}
		}
		$words = preg_split( '/[^\p{L}]+/u', $text, -1, PREG_SPLIT_NO_EMPTY );
		$words = is_array( $words ) ? $words : [];
		$stop  = count( array_intersect( $words, self::STOP_EN ) );
		$comm  = count( array_intersect( $words, self::COMMERCIAL_EN ) );
		if ( $stop >= 6 && $comm >= 2 ) {
			return 'commercial_en';
		}
		unset( $email );
		return '';
	}

	/** Clave pública de Turnstile o cadena vacía. */
	public static function site_key(): string {
		return defined( 'DI_TURNSTILE_SITEKEY' ) ? (string) DI_TURNSTILE_SITEKEY : (string) get_option( 'di_turnstile_sitekey', '' );
	}

	/** Clave secreta de Turnstile o cadena vacía. */
	public static function secret(): string {
		return defined( 'DI_TURNSTILE_SECRET' ) ? (string) DI_TURNSTILE_SECRET : (string) get_option( 'di_turnstile_secret', '' );
	}

	/** Turnstile está activo solo con las dos claves. */
	public static function captcha_enabled(): bool {
		return '' !== self::site_key() && '' !== self::secret();
	}

	/**
	 * Verifica el token de Turnstile en Cloudflare.
	 *
	 * @param string $token Valor de cf-turnstile-response.
	 * @param string $ip    IP del visitante (opcional).
	 * @return bool|null true válido, false inválido, null si Cloudflare no respondió.
	 */
	public static function verify( string $token, string $ip = '' ): ?bool {
		if ( '' === $token ) {
			return false;
		}
		$res = wp_remote_post(
			'https://challenges.cloudflare.com/turnstile/v0/siteverify',
			[
				'timeout' => 8,
				'body'    => array_filter(
					[
						'secret'   => self::secret(),
						'response' => $token,
						'remoteip' => $ip,
					]
				),
			]
		);
		if ( is_wp_error( $res ) || 200 !== (int) wp_remote_retrieve_response_code( $res ) ) {
			return null;
		}
		$data = json_decode( (string) wp_remote_retrieve_body( $res ), true );
		return is_array( $data ) && ! empty( $data['success'] );
	}

	/**
	 * Anota un descarte: contador por motivo y fecha del último (sin datos personales).
	 *
	 * @param string $reason Motivo.
	 */
	public static function log( string $reason ): void {
		$log = get_option( self::LOG_OPTION, [] );
		$log = is_array( $log ) ? $log : [];
		$log['total']           = (int) ( $log['total'] ?? 0 ) + 1;
		$log['by'][ $reason ]   = (int) ( $log['by'][ $reason ] ?? 0 ) + 1;
		$log['last']            = current_time( 'mysql' );
		$log['last_reason']     = $reason;
		update_option( self::LOG_OPTION, $log, false );
	}
}
