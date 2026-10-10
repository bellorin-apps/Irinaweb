<?php
/**
 * Widget: dos paneles de área (ORL / Sueño).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Areas extends AbstractWidget {

	public function get_name(): string {
		return 'di-areas';
	}

	public function get_title(): string {
		return __( 'DI · Áreas de atención', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/areas';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Encabezado', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Áreas de atención', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título', 'dra-irina-core' ), 'Dos áreas,<br>una misma forma de <em>atender</em>', true );
		$this->end_controls_section();
		foreach ( [
			'orl'   => __( 'Panel ORL', 'dra-irina-core' ),
			'sleep' => __( 'Panel Sueño', 'dra-irina-core' ),
		] as $k => $label ) {
			$this->start_controls_section( $k, [ 'label' => $label ] );
			$this->text( $k . '_eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
			$this->text( $k . '_title', __( 'Título', 'dra-irina-core' ), '', true );
			$this->lines( $k . '_items', __( 'Temas', 'dra-irina-core' ) );
			$this->text( $k . '_link', __( 'URL', 'dra-irina-core' ) );
			$this->text( $k . '_link_label', __( 'Texto del enlace', 'dra-irina-core' ) );
			$this->image( $k . '_image', __( 'Fotografía de fondo', 'dra-irina-core' ) );
			$this->end_controls_section();
		}
	}

	protected function args( array $s ): array {
		$panel = static fn( string $k ): array => [
			'eyebrow'    => (string) ( $s[ $k . '_eyebrow' ] ?? '' ),
			'title'      => (string) ( $s[ $k . '_title' ] ?? '' ),
			'items'      => self::to_lines( $s[ $k . '_items' ] ?? '' ),
			'link'       => (string) ( $s[ $k . '_link' ] ?? '' ),
			'link_label' => (string) ( $s[ $k . '_link_label' ] ?? '' ),
			'image_id'   => self::image_id( $s[ $k . '_image' ] ?? null ),
		];
		return [
			'eyebrow' => (string) ( $s['eyebrow'] ?? '' ),
			'title'   => (string) ( $s['title'] ?? '' ),
			'orl'     => $panel( 'orl' ),
			'sleep'   => $panel( 'sleep' ),
		];
	}
}
