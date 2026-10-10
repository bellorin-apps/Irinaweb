<?php
/**
 * Widget: trayectoria (CPT credencial: cursos, conferencias, publicaciones, experiencia).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Timeline extends AbstractWidget {

	public function get_name(): string {
		return 'di-trayectoria';
	}

	public function get_title(): string {
		return __( 'DI · Trayectoria', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'entity/timeline';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Trayectoria', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Formación continua en <em>cirugía de sueño</em>', true );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
		];
	}
}
