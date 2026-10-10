<?php
/**
 * Cabeceras de seguridad (PLAN 10.3): se envían desde PHP para no depender del .htaccess.
 * HSTS un año sin subdominios (el sitio ya fuerza HTTPS y redirige www), nosniff, sin incrustar en terceros,
 * referer recortado y permisos de hardware cerrados (el sitio no usa cámara, micrófono ni geolocalización).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Security;

final class Headers {

	public function register(): void {
		add_action( 'send_headers', [ $this, 'send' ] );
	}

	public function send(): void {
		if ( headers_sent() || is_admin() ) {
			return;
		}
		if ( is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000' );
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()' );
	}
}
