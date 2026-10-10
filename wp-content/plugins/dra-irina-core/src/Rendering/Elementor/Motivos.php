<?php
/**
 * Widget: motivos de consulta.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Motivos extends AbstractWidget {

	public function get_name(): string {
		return 'di-motivos';
	}

	public function get_title(): string {
		return __( 'DI · Motivos de consulta', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/motivos';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( '¿Qué estás sintiendo?', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Motivos de consulta <em>frecuentes</em>', true );
		$this->text( 'note', __( 'Nota', 'dra-irina-core' ), __( 'Orientación para encontrar la información adecuada. No sustituye una valoración médica.', 'dra-irina-core' ), true );
		$this->repeater(
			'items',
			__( 'Motivos', 'dra-irina-core' ),
			[
				'label' => __( 'Motivo', 'dra-irina-core' ),
				'sub'   => __( 'Padecimientos', 'dra-irina-core' ),
				'url'   => __( 'URL', 'dra-irina-core' ),
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'note'    => (string) ( $s['note'] ?? '' ),
			'items'   => (array) ( $s['items'] ?? [] ),
		];
	}
}
