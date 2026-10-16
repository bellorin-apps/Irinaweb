<?php
/**
 * Versión en inglés en el tema (D-095): idioma actual, portada por idioma, selector, menús y menú de respaldo en /en/.
 * El módulo de datos vive en dra-irina-core (DraIrina\Core\I18n\Language); sin el plugin todo queda en español.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

/** Idioma de la página actual: «es» o «en». */
function irina_lang(): string {
	return class_exists( '\DraIrina\Core\I18n\Language' ) ? \DraIrina\Core\I18n\Language::current() : 'es';
}

/** Portada del idioma actual (en /en/ solo si la raíz inglesa está publicada; si no, la española). */
function irina_home_url(): string {
	if ( 'en' === irina_lang() && class_exists( '\DraIrina\Core\I18n\Language' ) ) {
		$en = \DraIrina\Core\I18n\Language::home_url( 'en' );
		if ( '' !== $en ) {
			return $en;
		}
	}
	return home_url( '/' );
}

/**
 * Selector de idioma: un enlace a la otra versión (la traducción publicada de esta página o la portada del otro idioma).
 * No se muestra mientras la portada inglesa no esté publicada (D-095: nada de /en/ visible antes de la aprobación de la Dra.).
 *
 * @param string $css_class Clase del enlace.
 */
function irina_language_switcher( string $css_class = 'di-lang' ): string {
	if ( ! class_exists( '\DraIrina\Core\I18n\Language' ) || '' === \DraIrina\Core\I18n\Language::home_url( 'en' ) ) {
		return '';
	}
	$to  = 'en' === irina_lang() ? 'es' : 'en';
	$url = \DraIrina\Core\I18n\Language::switch_url( $to );
	if ( '' === $url ) {
		return '';
	}
	$tag   = \DraIrina\Core\I18n\Language::TAGS[ $to ];
	$short = 'en' === $to ? 'EN' : 'ES';
	$label = 'en' === $to ? 'View this page in English' : 'Ver esta página en español';
	return sprintf(
		'<a class="%1$s" href="%2$s" hreflang="%3$s" lang="%3$s" aria-label="%4$s"><span aria-hidden="true">%5$s</span></a>',
		esc_attr( $css_class ),
		esc_url( $url ),
		esc_attr( $tag ),
		esc_attr( $label ),
		esc_html( $short )
	);
}

// Menús propios del inglés: si el sitio está en /en/ y hay menú asignado a la ubicación inglesa, se usa ese.
add_action(
	'after_setup_theme',
	static function (): void {
		register_nav_menus(
			[
				'primary_en' => 'Navegación principal (inglés)',
				'footer_en'  => 'Pie de página (inglés)',
				'legal_en'   => 'Legal (inglés)',
			]
		);
	},
	11
);
add_filter(
	'wp_nav_menu_args',
	static function ( array $args ): array {
		$location = (string) ( $args['theme_location'] ?? '' );
		if ( 'en' === irina_lang() && in_array( $location, [ 'primary', 'footer', 'legal' ], true ) ) {
			$args['theme_location'] = $location . '_en';
			if ( 'legal' === $location ) {
				$args['fallback_cb'] = 'irina_nav_fallback_legal_en';
			}
		}
		return $args;
	}
);

/**
 * Menú legal de respaldo en inglés: las tres páginas legales publicadas bajo /en/.
 *
 * @param array<string, mixed> $args Argumentos de wp_nav_menu.
 */
function irina_nav_fallback_legal_en( array $args = [] ): void {
	$out = '';
	foreach ( [
		'en/privacy-notice'     => 'Privacy notice',
		'en/medical-disclaimer' => 'Medical disclaimer',
		'en/terms-of-use'       => 'Terms of use',
	] as $path => $label ) {
		$page = get_page_by_path( $path );
		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			$out .= sprintf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( get_permalink( $page ) ), esc_html( $label ) );
		}
	}
	if ( '' !== $out ) {
		printf( '<ul class="%s">%s</ul>', esc_attr( (string) ( $args['menu_class'] ?? 'menu' ) ), $out ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado arriba.
	}
}
