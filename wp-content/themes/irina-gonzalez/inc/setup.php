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
