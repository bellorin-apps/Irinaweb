<?php
/**
 * Widget: enlaces para redes (/links/).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Enlaces extends AbstractWidget {

	public function get_name(): string {
		return 'di-enlaces';
	}

	public function get_title(): string {
		return __( 'DI · Enlaces para redes', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/enlaces';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido (WhatsApp, redes, Doctoralia y mapa salen de Ajustes → Consultorio)', 'dra-irina-core' ) ] );
		$this->text( 'title', __( 'Título (vacío = nombre profesional)', 'dra-irina-core' ) );
		$this->text( 'subtitle', __( 'Subtítulo (vacío = especialidad · ciudad)', 'dra-irina-core' ) );
		$this->repeater(
			'extra',
			__( 'Enlaces adicionales', 'dra-irina-core' ),
			[
				'label' => __( 'Texto', 'dra-irina-core' ),
				'url'   => __( 'URL', 'dra-irina-core' ),
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'title'    => (string) ( $s['title'] ?? '' ),
			'subtitle' => (string) ( $s['subtitle'] ?? '' ),
			'extra'    => (array) ( $s['extra'] ?? [] ),
		];
	}
}
