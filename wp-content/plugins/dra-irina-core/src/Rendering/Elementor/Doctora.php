<?php
/**
 * Widget: bloque de la Dra. en el Home.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Doctora extends AbstractWidget {

	public function get_name(): string {
		return 'di-doctora';
	}

	public function get_title(): string {
		return __( 'DI · La Dra.', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/doctora';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
		$this->text( 'quote', __( 'Cita', 'dra-irina-core' ), '', true );
		$this->text( 'text', __( 'Texto', 'dra-irina-core' ), '', true );
		$this->lines( 'creds', __( 'Credenciales', 'dra-irina-core' ) );
		$this->image( 'image', __( 'Retrato', 'dra-irina-core' ) );
		$this->text( 'link', __( 'URL del botón', 'dra-irina-core' ), '/dra-irina-gonzalez-saez/' );
		$this->text( 'link_label', __( 'Texto del botón', 'dra-irina-core' ), __( 'Conocer a la Dra. Irina', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'    => (string) ( $s['eyebrow'] ?? '' ),
			'quote'      => (string) ( $s['quote'] ?? '' ),
			'text'       => (string) ( $s['text'] ?? '' ),
			'creds'      => self::to_lines( $s['creds'] ?? '' ),
			'image_id'   => self::image_id( $s['image'] ?? null ),
			'link'       => (string) ( $s['link'] ?? '' ),
			'link_label' => (string) ( $s['link_label'] ?? '' ),
		];
	}
}
