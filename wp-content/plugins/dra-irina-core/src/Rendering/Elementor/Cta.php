<?php
/**
 * Widget: CTA final a sangre.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Cta extends AbstractWidget {

	public function get_name(): string {
		return 'di-cta';
	}

	public function get_title(): string {
		return __( 'DI · CTA final', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'cta-bleed';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Agenda', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), __( '¿Hablamos de lo que te está quitando el descanso?', 'dra-irina-core' ), true );
		$this->text( 'text', __( 'Texto', 'dra-irina-core' ), __( 'Escríbenos por WhatsApp y te ayudamos a elegir el tipo de consulta.', 'dra-irina-core' ), true );
		$this->text( 'message', __( 'Mensaje prellenado de WhatsApp (vacío = el de Ajustes)', 'dra-irina-core' ), '', true );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'text'    => (string) ( $s['text'] ?? '' ),
			'message' => (string) ( $s['message'] ?? '' ),
		];
	}
}
