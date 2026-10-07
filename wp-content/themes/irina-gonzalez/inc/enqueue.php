<?php
/**
 * Encolado de assets con versión por hash de archivo. Sin jQuery en el front.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

/**
 * Versión por contenido para cache busting.
 */
function irina_asset_version( string $relative ): string {
	$file = IRINA_THEME_DIR . '/' . ltrim( $relative, '/' );
	return is_readable( $file ) ? (string) filemtime( $file ) : IRINA_THEME_VERSION;
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style( 'irina-tokens', IRINA_THEME_URI . '/assets/css/tokens.css', [], irina_asset_version( 'assets/css/tokens.css' ) );
		wp_enqueue_style( 'irina-base', IRINA_THEME_URI . '/assets/css/base.css', [ 'irina-tokens' ], irina_asset_version( 'assets/css/base.css' ) );
		wp_enqueue_style( 'irina-components', IRINA_THEME_URI . '/assets/css/components.css', [ 'irina-base' ], irina_asset_version( 'assets/css/components.css' ) );

		if ( ( is_singular( [ 'condicion', 'tratamiento' ] ) && has_term( 'sueno', 'area' ) ) || is_page( 'sueno' ) ) {
			wp_enqueue_style( 'irina-sleep', IRINA_THEME_URI . '/assets/css/sleep.css', [ 'irina-components' ], irina_asset_version( 'assets/css/sleep.css' ) );
		}

		wp_enqueue_script(
			'irina-main',
			IRINA_THEME_URI . '/assets/js/main.js',
			[],
			irina_asset_version( 'assets/js/main.js' ),
			[
				'strategy'  => 'defer',
				'in_footer' => true,
			]
		);
	},
	20
);

// Iskra vía Adobe Fonts (D-010). El ID del proyecto web se configura con el filtro irina_adobe_fonts_kit o la constante IRINA_ADOBE_FONTS_KIT.
add_action(
	'wp_head',
	static function (): void {
		$kit = apply_filters( 'irina_adobe_fonts_kit', defined( 'IRINA_ADOBE_FONTS_KIT' ) ? IRINA_ADOBE_FONTS_KIT : '' );
		if ( ! $kit || ! preg_match( '/^[a-z0-9]{6,12}$/', $kit ) ) {
			return;
		}
		echo '<link rel="preconnect" href="https://use.typekit.net" crossorigin>' . "\n";
		printf( '<link rel="stylesheet" href="https://use.typekit.net/%s.css" media="print" onload="this.media=\'all\'">' . "\n", esc_attr( $kit ) );
		printf( '<noscript><link rel="stylesheet" href="https://use.typekit.net/%s.css"></noscript>' . "\n", esc_attr( $kit ) );
	},
	1
);
