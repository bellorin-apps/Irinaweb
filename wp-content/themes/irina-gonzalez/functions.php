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

foreach ( [ 'setup', 'enqueue', 'performance', 'accessibility', 'elementor', 'template-tags' ] as $irina_inc ) {
	require_once IRINA_THEME_DIR . '/inc/' . $irina_inc . '.php';
}
unset( $irina_inc );
