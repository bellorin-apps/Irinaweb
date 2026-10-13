<?php
/**
 * Fotografías en WebP calidad 100 al subirlas (D-064; el propietario revisó la comparativa y mantiene 100).
 *
 * Regla del propietario (D-063): la calidad de las fotos no baja y nadie las reescala. Con la optimización de la CDN apagada,
 * los PNG originales de 4–5 MB llegaban tal cual a los teléfonos. Esta clase convierte en el momento de la subida cualquier
 * PNG/JPEG de 1200 px o más a WebP con calidad 100 (sin reescalar; conserva la transparencia): ~¼ del peso del PNG sin pérdida
 * visible (hero de la Dra.: 4 082 KB → 1 033 KB). Las versiones para móvil (1600 px) las genera WordPress. El archivo original no se conserva en el servidor: el propietario guarda los originales en Recursos.
 *
 * PNG con transparencia (recortes: retrato de «Escucharte», endoscopio) desde 800 px: WebP SIN PÉRDIDA con alfa (propietario,
 * 2026-10-12). Con Imagick los píxeles son idénticos; con GD (fallback) el color es idéntico y el alfa puede variar 1/255 en los
 * bordes, porque GD guarda el alfa en 7 bits: imperceptible. Retrato 2 451 → 1 318 KB; endoscopio 1 024 → 352 KB. Si el WebP no pesa
 * menos, se queda el PNG.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Media;

final class WebpUpload {

	private const MIN_WIDTH       = 1200;
	private const MIN_WIDTH_ALPHA = 800;
	public const QUALITY          = 100;

	public function register(): void {
		add_filter( 'wp_handle_upload', [ $this, 'convert' ], 10, 2 );
	}

	/**
	 * @param array{file:string,url:string,type:string} $upload Resultado de la subida.
	 * @param string                                    $context 'upload' o 'sideload'.
	 * @return array{file:string,url:string,type:string}
	 */
	public function convert( array $upload, string $context ): array {
		unset( $context ); // Firma del filtro de WordPress: upload y sideload usan la misma política.
		if ( ! in_array( $upload['type'] ?? '', [ 'image/png', 'image/jpeg' ], true ) ) {
			return $upload;
		}
		if ( ! wp_image_editor_supports( [ 'mime_type' => 'image/webp' ] ) ) {
			return $upload;
		}
		// Imagen para compartir en redes (og-*): se queda en JPEG; Facebook, LinkedIn y WhatsApp no muestran WebP en og:image.
		if ( str_starts_with( basename( $upload['file'] ), 'og-' ) ) {
			return $upload;
		}
		$size = wp_getimagesize( $upload['file'] );
		if ( ! $size ) {
			return $upload;
		}
		if ( 'image/png' === $upload['type'] && self::png_has_alpha( $upload['file'] ) ) {
			return (int) $size[0] >= self::MIN_WIDTH_ALPHA ? self::lossless( $upload ) : $upload;
		}
		if ( (int) $size[0] < self::MIN_WIDTH ) {
			return $upload;
		}
		$editor = wp_get_image_editor( $upload['file'] );
		if ( is_wp_error( $editor ) ) {
			return $upload;
		}
		$editor->set_quality( self::QUALITY );
		$target = preg_replace( '/\.(png|jpe?g)$/i', '.webp', $upload['file'] );
		if ( ! is_string( $target ) || $target === $upload['file'] ) {
			return $upload;
		}
		$saved = $editor->save( $target, 'image/webp' );
		if ( is_wp_error( $saved ) || empty( $saved['path'] ) || ! file_exists( $saved['path'] ) ) {
			return $upload;
		}
		// Solo sustituye si el WebP realmente pesa menos (en fotos siempre; en gráficos planos podría no hacerlo).
		if ( filesize( $saved['path'] ) >= filesize( $upload['file'] ) ) {
			wp_delete_file( $saved['path'] );
			return $upload;
		}
		wp_delete_file( $upload['file'] );
		$upload['file'] = $saved['path'];
		$upload['url']  = preg_replace( '/\.(png|jpe?g)$/i', '.webp', $upload['url'] ) ?? $upload['url'];
		$upload['type'] = 'image/webp';
		return $upload;
	}

	/**
	 * PNG con canal alfa (tipo de color 4 o 6 en IHDR) o paleta con transparencia (bloque tRNS).
	 *
	 * @param string $file Ruta del PNG.
	 */
	public static function png_has_alpha( string $file ): bool {
		$head = (string) file_get_contents( $file, false, null, 0, 4096 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( strlen( $head ) < 26 || "\x89PNG" !== substr( $head, 0, 4 ) ) {
			return false;
		}
		$type = ord( $head[25] );
		return 4 === $type || 6 === $type || ( 3 === $type && str_contains( $head, 'tRNS' ) );
	}

	/**
	 * Convierte un PNG con alfa a WebP sin pérdida (GD con IMG_WEBP_LOSSLESS o Imagick con webp:lossless).
	 *
	 * @param array{file:string,url:string,type:string} $upload Resultado de la subida.
	 * @return array{file:string,url:string,type:string}
	 */
	private static function lossless( array $upload ): array {
		$target = preg_replace( '/\.png$/i', '.webp', $upload['file'] );
		if ( ! is_string( $target ) || $target === $upload['file'] ) {
			return $upload;
		}
		$ok = self::lossless_write( $upload['file'], $target );
		if ( ! $ok || ! file_exists( $target ) ) {
			return $upload;
		}
		if ( filesize( $target ) >= filesize( $upload['file'] ) ) {
			wp_delete_file( $target );
			return $upload;
		}
		wp_delete_file( $upload['file'] );
		$upload['file'] = $target;
		$upload['url']  = preg_replace( '/\.png$/i', '.webp', $upload['url'] ) ?? $upload['url'];
		$upload['type'] = 'image/webp';
		return $upload;
	}

	/**
	 * Escribe el WebP sin pérdida: Imagick (exacto) y, si no está, GD. Público para poder probarlo fuera de WordPress.
	 *
	 * @param string $src    PNG de origen.
	 * @param string $target WebP de destino.
	 */
	public static function lossless_write( string $src, string $target ): bool {
		if ( class_exists( '\Imagick' ) ) {
			try {
				$im = new \Imagick( $src );
				$im->setImageFormat( 'webp' );
				$im->setOption( 'webp:lossless', 'true' );
				$im->setOption( 'webp:alpha-quality', '100' );
				$ok = $im->writeImage( $target );
				$im->clear();
				if ( $ok ) {
					return true;
				}
			} catch ( \Throwable $e ) {
				// Sin Imagick funcional: se intenta con GD.
				unset( $e );
			}
		}
		if ( function_exists( 'imagewebp' ) && defined( 'IMG_WEBP_LOSSLESS' ) ) {
			$img = @imagecreatefrompng( $src ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			if ( false !== $img ) {
				imagepalettetotruecolor( $img );
				imagealphablending( $img, false );
				imagesavealpha( $img, true );
				$ok = imagewebp( $img, $target, IMG_WEBP_LOSSLESS );
				imagedestroy( $img );
				if ( $ok ) {
					return true;
				}
			}
		}
		return false;
	}
}
