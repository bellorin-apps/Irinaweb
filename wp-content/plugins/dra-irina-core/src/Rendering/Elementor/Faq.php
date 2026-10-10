<?php
/**
 * Widget: preguntas frecuentes.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;

final class Faq extends AbstractWidget {

	public function get_name(): string {
		return 'di-faq';
	}

	public function get_title(): string {
		return __( 'DI · Preguntas frecuentes', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/faq';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Preguntas <em>frecuentes</em>', true );
		$this->repeater(
			'items',
			__( 'Preguntas', 'dra-irina-core' ),
			[
				'pregunta' => __( 'Pregunta', 'dra-irina-core' ),
				'text'     => __( 'Respuesta', 'dra-irina-core' ),
			]
		);
		$this->add_control(
			'narrow',
			[
				'label'        => __( 'Ancho de lectura', 'dra-irina-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		$items = [];
		foreach ( (array) ( $s['items'] ?? [] ) as $i ) {
			$items[] = [
				'pregunta'  => (string) ( $i['pregunta'] ?? '' ),
				'respuesta' => (string) ( $i['text'] ?? '' ),
			];
		}
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'items'   => $items,
			'narrow'  => 'yes' === ( $s['narrow'] ?? 'yes' ),
		];
	}
}
