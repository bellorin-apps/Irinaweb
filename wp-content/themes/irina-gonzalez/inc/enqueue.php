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

// Fuentes locales: preload solo del archivo crítico (regular). Se activa cuando existan los WOFF2 licenciados.
add_action(
	'wp_head',
	static function (): void {
		$font = '/assets/fonts/iskra-regular.woff2';
		if ( is_readable( IRINA_THEME_DIR . $font ) ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( IRINA_THEME_URI . $font ) );
		}
	},
	1
);
