<?php
/**
 * Fotografías en WebP calidad 100 al subirlas (D-064; el propietario revisó la comparativa y mantiene 100).
 *
 * Regla del propietario (D-063): la calidad de las fotos no baja y nadie las reescala. Con la optimización de la CDN apagada,
 * los PNG originales de 4–5 MB llegaban tal cual a los teléfonos. Esta clase convierte en el momento de la subida cualquier
 * PNG/JPEG de 1200 px o más a WebP con calidad 100 (sin reescalar; conserva la transparencia): ~¼ del peso del PNG sin pérdida
 * visible (hero de la Dra.: 4 082 KB → 1 033 KB). Las versiones para móvil (1600 px) las genera WordPress. El archivo original no se conserva en el servidor: el propietario guarda los originales en Recursos.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Media;

final class WebpUpload {

	private const MIN_WIDTH = 1200;
	public const QUALITY    = 100;

	public function register(): void {
		add_filter( 'wp_handle_upload', [ $this, 'convert' ], 10, 2 );
	}

	/**
	 * @param array{file:string,url:string,type:string} $upload Resultado de la subida.
	 * @param string                                    $context 'upload' o 'sideload'.
	 * @return array{file:string,url:string,type:string}
	 */
	public function convert( array $upload, string $context ): array {
		if ( ! in_array( $upload['type'] ?? '', [ 'image/png', 'image/jpeg' ], true ) ) {
			return $upload;
		}
		if ( ! wp_image_editor_supports( [ 'mime_type' => 'image/webp' ] ) ) {
			return $upload;
		}
		$size = wp_getimagesize( $upload['file'] );
		if ( ! $size || (int) $size[0] < self::MIN_WIDTH ) {
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
}
