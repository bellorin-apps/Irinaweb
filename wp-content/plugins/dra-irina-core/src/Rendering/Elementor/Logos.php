<?php
/**
 * Widget: logos de hospitales y membresías en carrusel, sección morada de la paleta con logos en blanco (propietario, 2026-10-10).
 * Los archivos viven en el tema (assets/brand/logos/<slug>.svg|png), versionados en el repo; el widget solo lista slug, nombre y URL.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Logos extends AbstractWidget {

	public function get_name(): string {
		return 'di-logos';
	}

	public function get_title(): string {
		return __( 'DI · Logos (hospitales y membresías)', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'blocks/logos';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), '' );
		$this->text( 'title', __( 'Título (HTML: b, em, br)', 'dra-irina-core' ), __( 'Membresías y <em>hospitales</em>', 'dra-irina-core' ), true );
		$this->repeater(
			'items',
			__( 'Logos', 'dra-irina-core' ),
			[
				'name' => __( 'Nombre (alt)', 'dra-irina-core' ),
				'slug' => __( 'Archivo (slug en assets/brand/logos/)', 'dra-irina-core' ),
				'url'  => __( 'Enlace (opcional)', 'dra-irina-core' ),
			]
		);
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		$items = [];
		foreach ( (array) ( $s['items'] ?? [] ) as $row ) {
			$items[] = [
				'name' => (string) ( $row['name'] ?? '' ),
				'slug' => sanitize_key( (string) ( $row['slug'] ?? '' ) ),
				'url'  => (string) ( $row['url'] ?? '' ),
			];
		}
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'items'   => $items,
		];
	}
}
