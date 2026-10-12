<?php
/**
 * Child theme Irina González. Solo responsabilidades de presentación.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

define( 'IRINA_THEME_VERSION', '0.1.0' );
define( 'IRINA_THEME_DIR', get_stylesheet_directory() );
define( 'IRINA_THEME_URI', get_stylesheet_directory_uri() );
// Ancho de la versión para teléfonos (tamaño irina-movil, D-064; calidad 95 desde D-073).
define( 'IRINA_MOVIL_WIDTH', 1600 );

// Adobe Fonts (Iskra, D-010). Proyecto web del propietario; el ID es público.
if ( ! defined( 'IRINA_ADOBE_FONTS_KIT' ) ) {
	define( 'IRINA_ADOBE_FONTS_KIT', 'nlo5pss' );
}

foreach ( [ 'setup', 'enqueue', 'performance', 'accessibility', 'elementor', 'template-tags' ] as $irina_inc ) {
	require_once IRINA_THEME_DIR . '/inc/' . $irina_inc . '.php';
}
unset( $irina_inc );
