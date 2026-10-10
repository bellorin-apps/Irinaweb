<?php
/**
 * Widget: bloque narrativo de lectura.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;

final class Narrative extends AbstractWidget {

	public function get_name(): string {
		return 'di-narrativa';
	}

	public function get_title(): string {
		return __( 'DI · Bloque narrativo', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'entity/narrative';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), '', true );
		$this->text( 'lead', __( 'Entradilla', 'dra-irina-core' ), '', true );
		$this->add_control(
			'text',
			[
				'label' => __( 'Texto', 'dra-irina-core' ),
				'type'  => Controls_Manager::WYSIWYG,
			]
		);
		$this->image( 'image', __( 'Imagen a la izquierda (opcional; escritorio dos columnas, móvil arriba)', 'dra-irina-core' ) );
		$this->text( 'image_alt', __( 'Texto alternativo de la imagen', 'dra-irina-core' ) );
		$this->add_control(
			'draft',
			[
				'label'        => __( 'Marcar como borrador pendiente de la Dra.', 'dra-irina-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'   => (string) ( $s['eyebrow'] ?? '' ),
			'title'     => (string) ( $s['title'] ?? '' ),
			'lead'      => (string) ( $s['lead'] ?? '' ),
			'text'      => (string) ( $s['text'] ?? '' ),
			'draft'     => 'yes' === ( $s['draft'] ?? '' ),
			'image_id'  => self::image_id( $s['image'] ?? null ),
			'image_alt' => (string) ( $s['image_alt'] ?? '' ),
		];
	}
}
