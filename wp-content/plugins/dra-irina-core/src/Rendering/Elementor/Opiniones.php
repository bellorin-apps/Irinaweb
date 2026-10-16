<?php
/**
 * Widget: opiniones de Doctoralia con su widget oficial (D-012, D-091). No transcribe ni edita opiniones: solo coloca el enlace
 * que el script de Doctoralia convierte en su widget. No muestra nada hasta que se active en Dra. Irina › Consultorio, después de
 * la revisión publicitaria de Codex.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Opiniones extends AbstractWidget {

	public function get_name(): string {
		return 'di-opiniones';
	}

	public function get_title(): string {
		return __( 'DI · Opiniones (widget oficial de Doctoralia)', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/opiniones';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Opiniones', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título (HTML: b, em, br)', 'dra-irina-core' ), __( 'Lo que dicen <em>sus pacientes</em>', 'dra-irina-core' ), true );
		$this->text( 'text', __( 'Texto', 'dra-irina-core' ), '', true );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'text'    => (string) ( $s['text'] ?? '' ),
		];
	}
}
