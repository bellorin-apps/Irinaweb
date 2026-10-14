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
// Calidad 100 en todo lo que genere WordPress (D-064, propietario 2026-10-10: vio la comparativa 100/95 y mantiene 100).
// Excepción (D-073, propietario 2026-10-12): la versión para teléfonos (irina-movil, 1600 px de ancho) va a calidad 95 para que la
// reducir su peso; el original sigue a 100. El filtro de calidad recibe dimensiones, no el nombre del tamaño:
// q95 se aplica abajo al archivo identificado en metadata, después de generar los tamaños a q100.
// D-081 (propietario 2026-10-14, tras comparar 1:1): las fotos de cabecera también se sirven en escritorio a q95 (irina-escritorio,
// mismo tamaño en píxeles que el original); el original a q100 se conserva en la biblioteca.
add_filter( 'jpeg_quality', static fn(): int => 100 );
add_filter(
	'wp_editor_set_quality',
	static fn(): int => 100,
	10,
	3
);
/** Solo el archivo derivado identificado por su nombre de tamaño recibe q95, nunca un original de 1600 px. */
function irina_mobile_metadata_quality( array $metadata, int $attachment_id ): array {
	$mobile = $metadata['sizes']['irina-movil'] ?? [];
	if ( empty( $mobile['file'] ) || ! empty( $mobile['irina_quality_95'] ) || ! in_array( $mobile['mime-type'] ?? '', [ 'image/webp', 'image/jpeg' ], true ) ) {
		return $metadata;
	}
	$original = (string) get_attached_file( $attachment_id );
	$file     = dirname( $original ) . '/' . wp_basename( (string) $mobile['file'] );
	if ( wp_normalize_path( $file ) === wp_normalize_path( $original ) || ! is_file( $file ) ) {
		return $metadata;
	}
	$editor = wp_get_image_editor( $file );
	if ( is_wp_error( $editor ) || is_wp_error( $editor->set_quality( 95 ) ) ) {
		return $metadata;
	}
	$saved = $editor->save( $file, $mobile['mime-type'] );
	if ( ! is_wp_error( $saved ) ) {
		clearstatcache( true, $file );
		$metadata['sizes']['irina-movil']['filesize']         = filesize( $file );
		$metadata['sizes']['irina-movil']['irina_quality_95'] = true;
	}
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'irina_mobile_metadata_quality', 20, 2 );

/** Foto de cabecera: adjunto cuyo archivo o slug empieza por «hero-» (mismo criterio que los tamaños, más abajo). */
function irina_is_hero_attachment( int $attachment_id, array $metadata = [] ): bool {
	$file = basename( (string) ( $metadata['file'] ?? get_attached_file( $attachment_id ) ) );
	$post = get_post( $attachment_id );
	return str_starts_with( $file, 'hero-' ) || ( $post && str_starts_with( (string) $post->post_name, 'hero-' ) );
}

/**
 * D-081: versión de escritorio de las fotos de cabecera a calidad 95, con las mismas dimensiones que el original (q100).
 * Se guarda junto al original como «<nombre>-escritorio.webp» y se registra en metadata como tamaño «irina-escritorio», así
 * WordPress la borra con el adjunto y `wp media regenerate` la rehace. Solo se usa si pesa menos que el original. Idempotente.
 */
function irina_desktop_metadata_quality( array $metadata, int $attachment_id ): array {
	if ( ! irina_is_hero_attachment( $attachment_id, $metadata ) ) {
		return $metadata;
	}
	$original = (string) get_attached_file( $attachment_id );
	$mime     = (string) wp_check_filetype( $original )['type'];
	if ( ! is_file( $original ) || ! in_array( $mime, [ 'image/webp', 'image/jpeg' ], true ) ) {
		return $metadata;
	}
	$info = pathinfo( $original );
	$file = $info['dirname'] . '/' . $info['filename'] . '-escritorio.' . $info['extension'];
	$prev = $metadata['sizes']['irina-escritorio'] ?? [];
	if ( ! empty( $prev['irina_quality_95'] ) && is_file( $file ) && filemtime( $file ) >= filemtime( $original ) ) {
		return $metadata;
	}
	$editor = wp_get_image_editor( $original );
	if ( is_wp_error( $editor ) || is_wp_error( $editor->set_quality( 95 ) ) ) {
		return $metadata;
	}
	$saved = $editor->save( $file, $mime );
	if ( is_wp_error( $saved ) || ! is_file( $file ) ) {
		return $metadata;
	}
	clearstatcache( true, $file );
	if ( filesize( $file ) >= filesize( $original ) ) {
		wp_delete_file( $file );
		unset( $metadata['sizes']['irina-escritorio'] );
		return $metadata;
	}
	$metadata['sizes']['irina-escritorio'] = [
		'file'             => wp_basename( $file ),
		'width'            => (int) $saved['width'],
		'height'           => (int) $saved['height'],
		'mime-type'        => $mime,
		'filesize'         => filesize( $file ),
		'irina_quality_95' => true,
	];
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'irina_desktop_metadata_quality', 21, 2 );

/**
 * Genera (o comprueba) la versión de escritorio q95 de todas las fotos de cabecera ya subidas. Para la sesión local tras desplegar:
 * `wp eval 'print_r( irina_regenerate_desktop_heroes() );'`. Devuelve [id => 'archivo KB → KB'] o el motivo si no se generó.
 *
 * @return array<int,string>
 */
function irina_regenerate_desktop_heroes(): array {
	$out = [];
	$ids = get_posts(
		[
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		]
	);
	foreach ( $ids as $id ) {
		$meta = wp_get_attachment_metadata( (int) $id );
		if ( ! is_array( $meta ) || ! irina_is_hero_attachment( (int) $id, $meta ) ) {
			continue;
		}
		$new = irina_desktop_metadata_quality( $meta, (int) $id );
		if ( $new !== $meta ) {
			wp_update_attachment_metadata( (int) $id, $new );
		}
		$d          = $new['sizes']['irina-escritorio'] ?? [];
		$out[ $id ] = empty( $d['file'] ) ? 'sin versión q95 (no pesa menos o formato no admitido)' : sprintf( '%s %d KB → %s %d KB', wp_basename( (string) get_attached_file( (int) $id ) ), (int) round( filesize( (string) get_attached_file( (int) $id ) ) / 1024 ), $d['file'], (int) round( (int) $d['filesize'] / 1024 ) );
	}
	return $out;
}
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
	$html = irina_normalize_image_attributes(
		irina_image_original(
			$attachment_id,
			[
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
			]
		)
	);
	// D-081: en escritorio (el <img>; los teléfonos usan el <source> irina-movil) la versión q95 si existe. ?m= como en móvil.
	$meta = wp_get_attachment_metadata( $attachment_id );
	$desk = empty( $meta['sizes']['irina-escritorio']['file'] ) ? false : wp_get_attachment_image_src( $attachment_id, 'irina-escritorio' );
	if ( ! $desk ) {
		return $html;
	}
	$file = dirname( (string) get_attached_file( $attachment_id ) ) . '/' . $meta['sizes']['irina-escritorio']['file'];
	if ( ! is_file( $file ) || wp_basename( (string) $desk[0] ) !== $meta['sizes']['irina-escritorio']['file'] ) {
		return $html;
	}
	$src       = add_query_arg( 'm', (string) filemtime( $file ), (string) $desk[0] );
	$processor = new \WP_HTML_Tag_Processor( $html );
	if ( $processor->next_tag( 'IMG' ) ) {
		$processor->set_attribute( 'src', esc_url_raw( $src ) ); // El procesador escapa el atributo.
		$processor->remove_attribute( 'srcset' );
		$processor->remove_attribute( 'sizes' );
	}
	return $processor->get_updated_html();
}

/** Normaliza atributos de carga incluso si un optimizador añadió una segunda copia. */
function irina_normalize_image_attributes( string $html ): string {
	$processor = new \WP_HTML_Tag_Processor( $html );
	while ( $processor->next_tag( 'IMG' ) ) {
		foreach ( [ 'loading', 'fetchpriority' ] as $name ) {
			$value = $processor->get_attribute( $name );
			if ( is_string( $value ) ) {
				$processor->remove_attribute( $name );
				$processor->set_attribute( $name, $value );
			}
		}
	}
	return $processor->get_updated_html();
}

// Elementor puede optimizar imágenes después de renderizar el widget.
add_filter( 'elementor/frontend/the_content', 'irina_normalize_image_attributes', 999 );

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
	// Teléfonos (≤899 px): versión de 1600 px (irina-movil, calidad 95 desde D-073); escritorio recibe el archivo completo.
	return irina_movil_picture( $attachment_id, $html );
}

/**
 * Envuelve una <img> en <picture> con la versión para teléfonos (irina-movil, 1600 px a calidad 95, D-073) para ≤899 px.
 * El escritorio conserva la <img> tal cual (archivo completo o su srcset). La URL lleva ?m=<fecha del archivo>: al regenerar la
 * versión móvil cambia y ni la CDN ni el navegador sirven la copia anterior.
 *
 * @param int    $attachment_id Adjunto.
 * @param string $html          <img> ya generada.
 */
function irina_movil_picture( int $attachment_id, string $html ): string {
	$movil = wp_get_attachment_image_src( $attachment_id, 'irina-movil' );
	if ( '' === $html || ! $movil || empty( $movil[3] ) ) {
		return $html;
	}
	$file = dirname( (string) get_attached_file( $attachment_id ) ) . '/' . wp_basename( (string) $movil[0] );
	$src  = file_exists( $file ) ? add_query_arg( 'm', (string) filemtime( $file ), $movil[0] ) : $movil[0];
	return '<picture><source media="(max-width: 899px)" srcset="' . esc_url( $src ) . '">' . $html . '</picture>';
}

// Las fotos de cabecera (slug hero-*: hero-home, hero-dra…) solo generan la versión para teléfonos (irina-movil); la de escritorio q95
// (irina-escritorio, D-081) la crea irina_desktop_metadata_quality() con el tamaño del original.
add_filter(
	'intermediate_image_sizes_advanced',
	static function ( array $sizes, array $image_meta, int $attachment_id ): array {
		// Por nombre de archivo: al importar, el slug aún no está asignado cuando se generan los tamaños.
		$file = basename( (string) ( $image_meta['file'] ?? '' ) );
		$post = get_post( $attachment_id );
		if ( str_starts_with( $file, 'hero-' ) || ( $post && str_starts_with( (string) $post->post_name, 'hero-' ) ) ) {
			return array_intersect_key( $sizes, [ 'irina-movil' => true ] ); // Solo la versión para teléfonos (D-064).
		}
		return $sizes;
	},
	10,
	3
);

/**
 * Último seguro contra atributos duplicados en las imágenes del contenido (p. ej. `fetchpriority` añadido dos veces por
 * la optimización de carga de WordPress/Elementor sobre un <img> que ya lo traía): conserva la última aparición.
 */
add_filter(
	'wp_content_img_tag',
	static function ( string $img ): string {
		foreach ( [ 'fetchpriority', 'loading', 'decoding' ] as $name ) {
			if ( preg_match_all( '/\s' . $name . '="[^"]*"/', $img, $m ) && count( $m[0] ) > 1 ) {
				$img = (string) preg_replace( '/\s' . $name . '="[^"]*"/', '', $img, count( $m[0] ) - 1 );
			}
		}
		return $img;
	},
	100
);

/**
 * Imágenes altas (retratos recortados con alfa): la CDN de Hostinger, con la optimización móvil activa, RECORTA las imágenes
 * verticales en lugar de reescalarlas (dra-escucharte 1024×2170 llegaba a los teléfonos como 1024×1496, sin piernas y con otra
 * proporción; verificado 2026-10-10 con User-Agent móvil). Con un parámetro de consulta en la URL la CDN sirve el archivo completo
 * (solo convertido a WebP). Se añade `?v=<id>-<fecha>` a src y srcset de cualquier adjunto cuya altura sea ≥ 1.5 veces su ancho.
 */
add_filter(
	'wp_get_attachment_image',
	static function ( string $html, int $attachment_id ): string {
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( ! is_array( $meta ) || empty( $meta['width'] ) || empty( $meta['height'] ) || (int) $meta['height'] < 1.5 * (int) $meta['width'] ) {
			return $html;
		}
		$version = 'v=' . $attachment_id . '-' . substr( md5( (string) get_post_modified_time( 'U', true, $attachment_id ) ), 0, 6 );
		$add     = static function ( string $url ) use ( $version ): string {
			return str_contains( $url, '?' ) ? $url . '&' . $version : $url . '?' . $version;
		};
		$html    = (string) preg_replace_callback(
			'/\ssrc="([^"]+)"/',
			static fn( array $m ): string => ' src="' . esc_url( $add( $m[1] ) ) . '"',
			$html
		);
		return (string) preg_replace_callback(
			'/\ssrcset="([^"]+)"/',
			static function ( array $m ) use ( $add ): string {
				$parts = array_map(
					static function ( string $candidate ) use ( $add ): string {
						$bits = preg_split( '/\s+/', trim( $candidate ), 2 );
						$bits = is_array( $bits ) && $bits ? $bits : [ $candidate ];
						return esc_url( $add( $bits[0] ) ) . ( isset( $bits[1] ) ? ' ' . $bits[1] : '' );
					},
					explode( ',', $m[1] )
				);
				return ' srcset="' . implode( ', ', $parts ) . '"';
			},
			$html
		);
	},
	20,
	2
);
