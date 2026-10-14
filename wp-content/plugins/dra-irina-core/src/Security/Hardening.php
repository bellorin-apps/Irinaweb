<?php
/**
 * Endurecimiento contra la enumeración de usuarios (PLAN 10.3, sesión local 2026-10-14).
 *
 * La API REST pública listaba al usuario administrador y su página de autor (/?author=1 → /author/<slug>/) revelaba un nombre de
 * usuario derivado de su correo. Para quien no ha iniciado sesión:
 * - se retiran las rutas /wp/v2/users de la API (el editor y wp-admin no cambian: allí hay sesión);
 * - las páginas de autor y la consulta ?author= responden 404 (el sitio no tiene blog ni páginas de autor);
 * - los datos oEmbed no incluyen el nombre ni la URL del autor.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Security;

final class Hardening {

	public function register(): void {
		add_filter( 'rest_endpoints', [ $this, 'rest_endpoints' ] );
		add_action( 'template_redirect', [ $this, 'author_404' ], 0 );
		add_filter( 'oembed_response_data', [ $this, 'oembed' ] );
	}

	/**
	 * Sin sesión, la API no expone usuarios.
	 *
	 * @param array<string,mixed> $endpoints Rutas registradas.
	 * @return array<string,mixed>
	 */
	public function rest_endpoints( array $endpoints ): array {
		if ( is_user_logged_in() ) {
			return $endpoints;
		}
		foreach ( array_keys( $endpoints ) as $route ) {
			if ( str_starts_with( (string) $route, '/wp/v2/users' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	}

	/** Páginas de autor y ?author=N: 404 antes de la redirección canónica que revelaría el nombre de usuario. */
	public function author_404(): void {
		if ( is_user_logged_in() ) {
			return;
		}
		$by_query = isset( $_GET['author'] ) || isset( $_GET['author_name'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- solo se comprueba su presencia.
		if ( ! $by_query && ! is_author() ) {
			return;
		}
		remove_action( 'template_redirect', 'redirect_canonical' );
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}

	/**
	 * oEmbed sin nombre ni URL del autor.
	 *
	 * @param array<string,mixed> $data Datos oEmbed.
	 * @return array<string,mixed>
	 */
	public function oembed( array $data ): array {
		unset( $data['author_name'], $data['author_url'] );
		return $data;
	}
}
