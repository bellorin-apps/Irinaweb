<?php
/**
 * Configuración del tema y hooks de Hello Elementor.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

add_action(
	'after_setup_theme',
	static function (): void {
		load_child_theme_textdomain( 'irina-gonzalez', IRINA_THEME_DIR . '/languages' );
		add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'script', 'style', 'navigation-widgets' ] );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_image_size( 'irina-hero', 1600, 1000, true );
		add_image_size( 'irina-card', 800, 600, true );
		add_image_size( 'irina-portrait', 900, 1200, true );
		register_nav_menus(
			[
				'primary' => __( 'Navegación principal', 'irina-gonzalez' ),
				'footer'  => __( 'Pie de página', 'irina-gonzalez' ),
				'legal'   => __( 'Legal', 'irina-gonzalez' ),
			]
		);
	}
);

// Hello: conservamos su reset y skip link; desactivamos theme.min.css propio de Hello para controlar el CSS base.
add_filter( 'hello_elementor_enqueue_theme_style', '__return_false' );
add_filter( 'hello_elementor_enable_skip_link', '__return_true' );
add_filter( 'hello_elementor_skip_link_URL', static fn(): string => '#content' );

// Templates PHP del child para los tipos de contenido médico (D-016).
add_filter(
	'template_include',
	static function ( string $template ): string {
		$map = [
			'condicion'   => 'templates/single-condicion.php',
			'tratamiento' => 'templates/single-tratamiento.php',
			'recurso'     => 'templates/single-recurso.php',
		];
		if ( is_singular( array_keys( $map ) ) ) {
			$file = IRINA_THEME_DIR . '/' . $map[ get_post_type() ];
			if ( is_readable( $file ) ) {
				return $file;
			}
		}
		if ( is_post_type_archive( 'condicion' ) && is_readable( IRINA_THEME_DIR . '/templates/archive-condicion.php' ) ) {
			return IRINA_THEME_DIR . '/templates/archive-condicion.php';
		}
		if ( is_post_type_archive( 'tratamiento' ) && is_readable( IRINA_THEME_DIR . '/templates/archive-tratamiento.php' ) ) {
			return IRINA_THEME_DIR . '/templates/archive-tratamiento.php';
		}
		return $template;
	}
);

/**
 * Menú de respaldo cuando no hay menú asignado: páginas clave del sitemap.
 *
 * @param array<string, mixed> $args Argumentos de wp_nav_menu.
 */
function irina_nav_fallback( array $args = [] ): void {
	$items = [
		'otorrinolaringologia'    => __( 'Otorrinolaringología', 'irina-gonzalez' ),
		'sueno'                   => __( 'Sueño', 'irina-gonzalez' ),
		'dra-irina-gonzalez-saez' => __( 'Dra. Irina', 'irina-gonzalez' ),
		'primera-consulta'        => __( 'Primera consulta', 'irina-gonzalez' ),
		'contacto'                => __( 'Contacto', 'irina-gonzalez' ),
	];
	$out   = '';
	foreach ( $items as $slug => $label ) {
		$page = get_page_by_path( $slug );
		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			$out .= sprintf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( get_permalink( $page ) ), esc_html( $label ) );
		}
	}
	if ( '' === $out ) {
		return;
	}
	printf( '<ul class="%s">%s</ul>', esc_attr( (string) ( $args['menu_class'] ?? 'menu' ) ), $out ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado arriba.
}

// Clase de body cuando la página abre con cabecera a sangre (evita salto de layout antes del JS).
add_filter(
	'body_class',
	static function ( array $classes ): array {
		if ( is_singular( [ 'condicion', 'tratamiento', 'recurso' ] ) || is_post_type_archive( [ 'condicion', 'tratamiento' ] ) || is_front_page() || is_page() ) {
			$classes[] = 'has-bleed';
		}
		return $classes;
	}
);

// Favicon e iconos de app desde assets/brand (generados del isotipo). Se usan si no hay site icon configurado en WordPress.
add_action(
	'wp_head',
	static function (): void {
		if ( has_site_icon() ) {
			return;
		}
		$b = IRINA_THEME_URI . '/assets/brand/';
		echo '<link rel="icon" href="' . esc_url( $b . 'favicon.ico' ) . '" sizes="32x32">' . "\n";
		echo '<link rel="icon" href="' . esc_url( $b . 'favicon.svg' ) . '" type="image/svg+xml">' . "\n";
		echo '<link rel="apple-touch-icon" href="' . esc_url( $b . 'apple-touch-icon.png' ) . '">' . "\n";
		echo '<meta name="theme-color" content="#8c4eaa">' . "\n";
	},
	2
);
