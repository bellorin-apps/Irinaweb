<?php
/**
 * Widget: hero a sangre del Home.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;

final class Hero extends AbstractWidget {

	public function get_name(): string {
		return 'di-hero';
	}

	public function get_title(): string {
		return __( 'DI · Hero a sangre', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'home/hero';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ), __( 'Otorrinolaringólogo en Monterrey', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Titular (permite <em> y <br>)', 'dra-irina-core' ), 'Respirar bien,<br>dormir bien,<br><em>oír bien.</em>', true );
		$this->text( 'lead', __( 'Entradilla', 'dra-irina-core' ), '', true );
		$this->text( 'lead_mobile', __( 'Entradilla corta para móvil (vacía = la misma)', 'dra-irina-core' ), '', true );
		$this->image( 'image', __( 'Fotografía de fondo', 'dra-irina-core' ) );
		$this->lines( 'trust', __( 'Señales de confianza', 'dra-irina-core' ) );
		$this->text( 'secondary_label', __( 'Botón secundario · texto', 'dra-irina-core' ), __( 'Conocer a la Dra. Irina', 'dra-irina-core' ) );
		$this->text( 'secondary_url', __( 'Botón secundario · URL', 'dra-irina-core' ), '/dra-irina-gonzalez-saez/' );
		$this->lines( 'ticker', __( 'Marquesina (vacía para ocultar)', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		return [
			'eyebrow'         => (string) ( $s['eyebrow'] ?? '' ),
			'title'           => (string) ( $s['title'] ?? '' ),
			'lead'            => (string) ( $s['lead'] ?? '' ),
			'lead_mobile'     => (string) ( $s['lead_mobile'] ?? '' ),
			'image_id'        => self::image_id( $s['image'] ?? null ),
			'trust'           => self::to_lines( $s['trust'] ?? '' ),
			'secondary_label' => (string) ( $s['secondary_label'] ?? '' ),
			'secondary_url'   => (string) ( $s['secondary_url'] ?? '' ),
			'ticker'          => self::to_lines( $s['ticker'] ?? '' ),
		];
	}
}
