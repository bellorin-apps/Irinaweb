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

/**
 * Versión de is_page() con slugs españoles que vale también en la traducción inglesa (/en/sleep/ se comporta como /sueno/).
 *
 * @param string|string[] $slugs Slugs de las páginas españolas.
 */
function irina_is_page( $slugs ): bool {
	return class_exists( '\DraIrina\Core\I18n\Language' ) ? \DraIrina\Core\I18n\Language::is_page( $slugs ) : is_page( $slugs );
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

/**
 * Texto de una credencial en el idioma de la página: en /en/ usa la traducción guardada en `_di_en` (setup-en.sh) si existe.
 *
 * @param WP_Post $credential Credencial.
 * @param string  $field      «titulo», «institucion» o «lugar».
 */
function irina_cred_text( WP_Post $credential, string $field ): string {
	if ( 'en' === irina_lang() ) {
		$en = get_post_meta( $credential->ID, '_di_en', true );
		if ( is_array( $en ) && ! empty( $en[ $field ] ) ) {
			return (string) $en[ $field ];
		}
	}
	return 'titulo' === $field ? $credential->post_title : (string) get_post_meta( $credential->ID, 'di_' . $field, true );
}

// Título de la 404 bajo /en/: Rank Math usa el de Ajustes (en español, Q-061); el nombre, el inglés del Consultorio (Q-080).
add_filter(
	'rank_math/frontend/title',
	static function ( string $title ): string {
		if ( ! is_404() || 'en' !== irina_lang() ) {
			return $title;
		}
		$name = class_exists( '\DraIrina\Core\Settings\PracticeSettings' ) ? (string) \DraIrina\Core\Settings\PracticeSettings::get( 'nombre_profesional' ) : '';
		return 'Page not found | ' . ( '' !== $name ? $name : wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES ) );
	}
);

/**
 * Texto alternativo en inglés de las fotos compartidas por las dos versiones (Q-080). Se traduce el alt guardado en la Biblioteca
 * de medios solo si coincide exactamente con uno de esta lista; un alt distinto (p. ej., el que ya trae en inglés un widget)
 * no se toca. Si se edita un alt en español, hay que actualizar aquí su traducción.
 *
 * @param string $alt Texto alternativo en español.
 */
function irina_alt_en( string $alt ): string {
	$map = [
		'Dra. Irina González Sáez'                        => 'Dr. Irina González Sáez',
		'Dra. Irina González Sáez en consultorio'         => 'Dr. Irina González Sáez in her office',
		'Dra. Irina González Sáez — Primera consulta'     => 'Dr. Irina González Sáez — First visit',
		'Dra. Irina González Sáez — Contacto'             => 'Dr. Irina González Sáez — Contact',
		'Dra. Irina González Sáez — Sueño'                => 'Dr. Irina González Sáez — Sleep',
		'Dra. Irina González Sáez — Otorrinolaringología' => 'Dr. Irina González Sáez — Otolaryngology (ENT)',
		'Otorrinolaringología'                            => 'Otolaryngology (ENT)',
		'Ronquido y apnea del sueño'                      => 'Snoring and sleep apnea',
		'Dra. Irina González Sáez · Tu Otorrino Especialista en Sueño' => 'Dr. Irina González Sáez · Your ENT and sleep specialist',
	];
	return $map[ trim( $alt ) ] ?? $alt;
}

add_filter(
	'wp_get_attachment_image_attributes',
	static function ( array $attr ): array {
		if ( 'en' === irina_lang() && isset( $attr['alt'] ) ) {
			$attr['alt'] = irina_alt_en( (string) $attr['alt'] );
		}
		return $attr;
	},
	20
);

// Alt de la imagen social (og:image:alt) en /en/: Rank Math toma el de la imagen por defecto, en español.
foreach ( [ 'rank_math/opengraph/facebook/og_image_alt', 'rank_math/opengraph/twitter/twitter_image_alt' ] as $irina_og_alt ) {
	add_filter(
		$irina_og_alt,
		static fn( $alt ) => 'en' === irina_lang() && is_string( $alt ) ? irina_alt_en( $alt ) : $alt
	);
}
