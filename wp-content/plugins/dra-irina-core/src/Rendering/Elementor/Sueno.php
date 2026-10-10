<?php
/**
 * Widget: bloque Sueño (registro claro) del Home.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Sueno extends AbstractWidget {

	public function get_name(): string {
		return 'di-sueno';
	}

	public function get_title(): string {
		return __( 'DI · Sueño (ruta)', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/sueno';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Especialización en sueño', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Del ronquido al descanso, <em>en un solo lugar</em>', true );
		$this->text( 'lead', __( 'Texto', 'dra-irina-core' ), '', true );
		$this->text( 'link', __( 'URL del botón', 'dra-irina-core' ), '/sueno/' );
		$this->text( 'link_label', __( 'Texto del botón', 'dra-irina-core' ), __( 'Explorar la ruta de tratamiento', 'dra-irina-core' ) );
		$this->lines( 'steps', __( 'Ruta (pasos)', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'    => (string) ( $s['eyebrow'] ?? '' ),
			'title'      => (string) ( $s['title'] ?? '' ),
			'lead'       => (string) ( $s['lead'] ?? '' ),
			'link'       => (string) ( $s['link'] ?? '' ),
			'link_label' => (string) ( $s['link_label'] ?? '' ),
			'steps'      => self::to_lines( $s['steps'] ?? '' ),
		];
	}
}
