<?php
/**
 * Widget: listado de condiciones/tratamientos (desde el CPT por área/zona, con respaldo manual).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;

final class Listado extends AbstractWidget {

	public function get_name(): string {
		return 'di-listado';
	}

	public function get_title(): string {
		return __( 'DI · Listado de padecimientos / tratamientos', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/listado';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), '', true );
		$this->text( 'note', __( 'Nota', 'dra-irina-core' ), '', true );
		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Tipo', 'dra-irina-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'condicion',
				'options' => [
					'condicion'   => __( 'Padecimientos', 'dra-irina-core' ),
					'tratamiento' => __( 'Tratamientos', 'dra-irina-core' ),
				],
			]
		);
		$this->add_control(
			'area',
			[
				'label'   => __( 'Área', 'dra-irina-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''      => __( 'Todas', 'dra-irina-core' ),
					'orl'   => 'ORL',
					'sueno' => __( 'Sueño', 'dra-irina-core' ),
				],
			]
		);
		$this->add_control(
			'zona',
			[
				'label'   => __( 'Zona', 'dra-irina-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''         => __( 'Todas', 'dra-irina-core' ),
					'oido'     => __( 'Oído', 'dra-irina-core' ),
					'nariz'    => __( 'Nariz', 'dra-irina-core' ),
					'garganta' => __( 'Garganta', 'dra-irina-core' ),
					'cuello'   => __( 'Cuello', 'dra-irina-core' ),
					'sueno'    => __( 'Sueño', 'dra-irina-core' ),
				],
			]
		);
		$this->repeater(
			'items',
			__( 'Respaldo manual (si aún no hay entradas publicadas)', 'dra-irina-core' ),
			[
				'label' => __( 'Nombre', 'dra-irina-core' ),
				'sub'   => __( 'Descripción corta', 'dra-irina-core' ),
				'url'   => __( 'URL', 'dra-irina-core' ),
			]
		);
		$this->add_control(
			'tight',
			[
				'label'        => __( 'Sin espacio superior', 'dra-irina-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'   => (string) ( $s['eyebrow'] ?? '' ),
			'title'     => (string) ( $s['title'] ?? '' ),
			'note'      => (string) ( $s['note'] ?? '' ),
			'post_type' => (string) ( $s['post_type'] ?? 'condicion' ),
			'area'      => (string) ( $s['area'] ?? '' ),
			'zona'      => (string) ( $s['zona'] ?? '' ),
			'items'     => (array) ( $s['items'] ?? [] ),
			'tight'     => 'yes' === ( $s['tight'] ?? '' ),
		];
	}
}
