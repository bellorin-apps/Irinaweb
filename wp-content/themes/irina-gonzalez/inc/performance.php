<?php
/**
 * Recortes de peso en el front (MASTER_PROMPT §37, §113).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

add_action(
	'init',
	static function (): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}
);

// Sin dashicons en el front para visitantes.
add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'dashicons' );
		}
	},
	100
);

// La imagen destacada del hero (LCP) nunca se carga en diferido.
add_filter(
	'wp_get_attachment_image_attributes',
	static function ( array $attr, \WP_Post $attachment, $size ): array {
		if ( 'irina-hero' === $size || 'irina-portrait' === $size ) {
			$attr['loading']       = 'eager';
			$attr['fetchpriority'] = 'high';
			$attr['decoding']      = 'async';
		}
		return $attr;
	},
	10,
	3
);

// Sin jQuery Migrate en el front.
add_action(
	'wp_default_scripts',
	static function ( \WP_Scripts $scripts ): void {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, [ 'jquery-migrate' ] );
		}
	}
);

// Calidad de las versiones intermedias que genera WordPress (por defecto 82). La foto del hero se sirve en tamaño completo con srcset.
add_filter( 'jpeg_quality', static fn(): int => 94 );
// El original subido se conserva como tamaño "full" (WordPress no lo reescala a 2560 px): la foto del hero se sirve tal cual.
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Imagen de cabecera a pantalla completa: srcset con el archivo original para escritorio y solo versiones pequeñas para móvil,
 * para que el navegador no elija una intermedia recomprimida (p. ej. 2048 px) en pantallas grandes.
 */
function irina_hero_image( int $attachment_id ): string {
	$keep = static function ( array $sources, array $size_array, string $src, array $meta ) {
		$full = (int) ( $meta['width'] ?? 0 );
		foreach ( $sources as $w => $source ) {
			if ( (int) $w !== $full && (int) $w > 1024 ) {
				unset( $sources[ $w ] );
			}
		}
		return $sources;
	};
	add_filter( 'wp_calculate_image_srcset', $keep, 10, 4 );
	$html = wp_get_attachment_image( $attachment_id, 'full', false, [ 'sizes' => '100vw', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ] );
	remove_filter( 'wp_calculate_image_srcset', $keep, 10 );
	return $html;
}
add_filter( 'wp_editor_set_quality', static fn(): int => 94 );
