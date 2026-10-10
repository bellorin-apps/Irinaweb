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
		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- carga diferida de Adobe Fonts (print → all) no expresable con wp_enqueue_style.
		printf( '<link rel="stylesheet" href="https://use.typekit.net/%s.css" media="print" onload="this.media=\'all\'">' . "\n", esc_attr( $kit ) );
		printf( '<noscript><link rel="stylesheet" href="https://use.typekit.net/%s.css"></noscript>' . "\n", esc_attr( $kit ) );
		// phpcs:enable
	},
	1
);

/**
 * Mapa con estilo propio (D-048): carga el script del tema y la Maps JavaScript API con la clave del consultorio.
 * El estilo viene del ajuste «maps_style_json» (Snazzy Maps) o, si está vacío, del estilo de la paleta en assets/map-style.json.
 */
function irina_enqueue_map( string $api_key ): void {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done  = true;
	$style = (string) irina_practice( 'maps_style_json' );
	if ( '' === $style ) {
		$file  = IRINA_THEME_DIR . '/assets/map-style.json';
		$style = is_readable( $file ) ? (string) file_get_contents( $file ) : '[]'; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- archivo del tema.
	}
	wp_enqueue_script(
		'irina-map',
		IRINA_THEME_URI . '/assets/js/map.js',
		[],
		irina_asset_version( 'assets/js/map.js' ),
		[
			'strategy'  => 'defer',
			'in_footer' => true,
		]
	);
	$decoded = json_decode( $style, true );
	$style   = wp_json_encode( is_array( $decoded ) ? $decoded : [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
	wp_add_inline_script( 'irina-map', 'window.diMapStyle = ' . $style . '; window.diMapMarker = ' . wp_json_encode( IRINA_THEME_URI . '/assets/brand/map-pin.svg' ) . ';', 'before' );
	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google controla v=weekly; no se agrega versión local.
	wp_enqueue_script(
		'google-maps',
		'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode( $api_key ) . '&loading=async&callback=diInitMap&v=weekly&language=es&region=MX',
		[ 'irina-map' ],
		null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google controla v=weekly.
		[
			'strategy'  => 'defer', // Defer: se ejecuta tras map.js (mismo grupo, en orden), así el callback diInitMap ya existe.
			'in_footer' => true,
		]
	); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- versión gestionada por Google.
}
