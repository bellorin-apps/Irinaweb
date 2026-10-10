<?php
/**
 * Widget: credenciales verificables (CPT credencial).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Credenciales extends AbstractWidget {

	public function get_name(): string {
		return 'di-credenciales';
	}

	public function get_title(): string {
		return __( 'DI · Credenciales', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'entity/credenciales';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido (las credenciales se editan en Credenciales → marcar «mostrar»)', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Formación y certificaciones', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Credenciales <em>verificables</em>', true );
		$this->text( 'tags_title', __( 'Título de etiquetas', 'dra-irina-core' ), __( 'Membresías y hospitales', 'dra-irina-core' ) );
		$this->lines( 'extra_tags', __( 'Etiquetas adicionales (hospitales)', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'    => (string) ( $s['eyebrow'] ?? '' ),
			'title'      => (string) ( $s['title'] ?? '' ),
			'tags_title' => (string) ( $s['tags_title'] ?? '' ),
			'extra_tags' => self::to_lines( $s['extra_tags'] ?? '' ),
		];
	}
}
