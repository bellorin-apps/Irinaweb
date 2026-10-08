<?php
/**
 * Widget: ubicación (datos del consultorio desde la fuente única).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Ubicacion extends AbstractWidget {

	public function get_name(): string {
		return 'di-ubicacion';
	}

	public function get_title(): string {
		return __( 'DI · Ubicación y horario', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/ubicacion';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Consultorio', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título (vacío = centro y colonia de Ajustes → Consultorio)', 'dra-irina-core' ), '', true );
		$this->repeater(
			'facts',
			__( 'Datos extra (estacionamiento, acceso…)', 'dra-irina-core' ),
			[
				'label' => __( 'Etiqueta', 'dra-irina-core' ),
				'value' => __( 'Valor', 'dra-irina-core' ),
			]
		);
		$this->text( 'map_embed', __( 'URL de inserción de Google Maps (iframe src)', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		$facts = [];
		foreach ( (array) ( $s['facts'] ?? [] ) as $f ) {
			$facts[] = [ (string) ( $f['label'] ?? '' ), (string) ( $f['value'] ?? '' ) ];
		}
		return [
			'eyebrow'   => (string) ( $s['eyebrow'] ?? '' ),
			'title'     => (string) ( $s['title'] ?? '' ),
			'facts'     => $facts,
			'map_embed' => (string) ( $s['map_embed'] ?? '' ),
		];
	}
}
