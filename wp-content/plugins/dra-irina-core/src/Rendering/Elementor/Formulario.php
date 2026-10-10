<?php
/**
 * Widget: formulario de contacto con minimización de datos (PLAN 8.4).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Formulario extends AbstractWidget {

	public function get_name(): string {
		return 'di-form';
	}

	public function get_title(): string {
		return __( 'DI · Formulario de contacto', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/form';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Escríbenos', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título (HTML: b, em, br)', 'dra-irina-core' ), __( '¿Prefieres que <b>te</b> <em>llamemos</em>?', 'dra-irina-core' ), true );
		$this->text( 'lead', __( 'Entradilla', 'dra-irina-core' ), __( 'Déjanos tu nombre y teléfono y te contactamos en horario de consultorio. Para agendar más rápido, WhatsApp.', 'dra-irina-core' ), true );
		$this->text( 'button', __( 'Texto del botón', 'dra-irina-core' ), __( 'Enviar solicitud', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'lead'    => (string) ( $s['lead'] ?? '' ),
			'button'  => (string) ( $s['button'] ?? '' ),
		];
	}
}
