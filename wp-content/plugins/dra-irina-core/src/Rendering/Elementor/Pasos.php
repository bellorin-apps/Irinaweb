<?php
/**
 * Widget: pasos de la consulta.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Pasos extends AbstractWidget {

	public function get_name(): string {
		return 'di-pasos';
	}

	public function get_title(): string {
		return __( 'DI · Pasos de la consulta', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/pasos';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Cómo trabajamos', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Qué esperar en <em>tu consulta</em>', true );
		$this->repeater(
			'steps',
			__( 'Pasos', 'dra-irina-core' ),
			[
				'title' => __( 'Título', 'dra-irina-core' ),
				'text'  => __( 'Texto', 'dra-irina-core' ),
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'steps'   => (array) ( $s['steps'] ?? [] ),
		];
	}
}
