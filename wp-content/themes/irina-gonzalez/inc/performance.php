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

// Calidad 100 % en todo lo que genere WordPress (JPEG y WebP), por decisión del propietario (tanda 1, 2026-10-09): las fotos son
// profesionales y la CDN se encarga del peso por dispositivo. Los originales se conservan sin reescalar (abajo).
add_filter( 'jpeg_quality', static fn(): int => 100 );
add_filter( 'wp_editor_set_quality', static fn(): int => 100 );
// El original subido se conserva como tamaño "full" (WordPress no lo reescala a 2560 px): la foto del hero se sirve tal cual.
add_filter( 'big_image_size_threshold', '__return_false' );
// Sin «sizes=auto» en imágenes diferidas: con «auto» el navegador pedía la versión del ancho CSS a 1× (borrosa en 2×). Q-020.
add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );

/**
 * Imagen de cabecera a pantalla completa (D-042/D-043): se entrega ÚNICAMENTE el archivo original, sin srcset ni tamaños
 * intermedios generados por WordPress. La CDN de Hostinger (optimización de imágenes activa) redimensiona y convierte por
 * dispositivo a partir de ese original sin pérdida.
 */
function irina_hero_image( int $attachment_id ): string {
	return irina_image_original(
		$attachment_id,
		[
			'loading'       => 'eager',
			'fetchpriority' => 'high',
			'decoding'      => 'async',
		]
	);
}

/**
 * Imagen servida solo como archivo original (sin srcset ni «sizes»): la CDN decide el ancho por dispositivo (2400/1200 px),
 * no el ancho del viewport. Para fondos recortados en móvil (object-fit: cover) evita que llegue una versión al ancho de pantalla
 * que luego se amplíe borrosa.
 *
 * @param int                  $attachment_id Adjunto.
 * @param array<string,string> $attrs         Atributos del <img>.
 */
function irina_image_original( int $attachment_id, array $attrs = [] ): string {
	$none = static fn(): array => [];
	add_filter( 'wp_calculate_image_srcset', $none, 10, 0 );
	$html = wp_get_attachment_image( $attachment_id, 'full', false, $attrs );
	remove_filter( 'wp_calculate_image_srcset', $none, 10 );
	return $html;
}

// Las fotos de cabecera (slug hero-*: hero-home, hero-dra…) no generan tamaños intermedios: el original es la única versión en disco.
add_filter(
	'intermediate_image_sizes_advanced',
	static function ( array $sizes, array $image_meta, int $attachment_id ): array {
		// Por nombre de archivo: al importar, el slug aún no está asignado cuando se generan los tamaños.
		$file = basename( (string) ( $image_meta['file'] ?? '' ) );
		$post = get_post( $attachment_id );
		if ( str_starts_with( $file, 'hero-' ) || ( $post && str_starts_with( (string) $post->post_name, 'hero-' ) ) ) {
			return [];
		}
		return $sizes;
	},
	10,
	3
);
add_filter( 'wp_editor_set_quality', static fn(): int => 94 );
