<?php
/**
 * Widget: cabecera a sangre genérica (páginas estáticas).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

use Elementor\Controls_Manager;

final class Bleed extends AbstractWidget {

	public function get_name(): string {
		return 'di-bleed';
	}

	public function get_title(): string {
		return __( 'DI · Cabecera a sangre', 'dra-irina-core' );
	}

	protected function part(): string {
		return 'bleed';
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'c', [ 'label' => __( 'Contenido', 'dra-irina-core' ) ] );
		$this->text( 'eyebrow', __( 'Eyebrow', 'dra-irina-core' ) );
		$this->text( 'title', __( 'Título (permite <em> y <br>)', 'dra-irina-core' ), '', true );
		$this->text( 'lead', __( 'Entradilla', 'dra-irina-core' ), '', true );
		$this->add_control(
			'variant',
			[
				'label'   => __( 'Fondo', 'dra-irina-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sand',
				'options' => [
					'warm'    => __( 'Cálido', 'dra-irina-core' ),
					'sand'    => __( 'Arena', 'dra-irina-core' ),
					'default' => __( 'Púrpura', 'dra-irina-core' ),
					'night'   => __( 'Noche (Sueño)', 'dra-irina-core' ),
					'light'   => __( 'Claro (foto clara, texto oscuro)', 'dra-irina-core' ),
				],
			]
		);
		$this->image( 'image', __( 'Fotografía de fondo', 'dra-irina-core' ) );
		$this->text( 'focus', __( 'Encuadre escritorio (object-position, ej. «65% 40%»; vacío = por defecto)', 'dra-irina-core' ) );
		$this->text( 'focus_mobile', __( 'Encuadre móvil (object-position, ej. «60% 50%»)', 'dra-irina-core' ) );
		$this->text( 'crumb', __( 'Miga de pan (texto final)', 'dra-irina-core' ) );
		$this->text( 'whatsapp_label', __( 'Botón WhatsApp · texto (vacío = por defecto)', 'dra-irina-core' ) );
		$this->text( 'secondary_label', __( 'Botón secundario · texto', 'dra-irina-core' ) );
		$this->text( 'secondary_url', __( 'Botón secundario · URL', 'dra-irina-core' ) );
		$this->text( 'secondary_short', __( 'Botón secundario · texto corto para móvil (vacío = el mismo)', 'dra-irina-core' ) );
		$this->end_controls_section();
	}

	protected function args( array $s ): array {
		// Como en el hero de la home: etiquetas cortas en móvil (botones 50/50).
		$cta = function_exists( 'irina_whatsapp_button' ) ? irina_whatsapp_button( (string) ( $s['whatsapp_label'] ?? '' ), '', 'di-btn ' . ( 'night' === ( $s['variant'] ?? '' ) ? 'di-btn--light' : 'di-btn--whatsapp' ), __( 'WhatsApp', 'dra-irina-core' ) ) : '';
		if ( '' !== (string) ( $s['secondary_label'] ?? '' ) && '' !== (string) ( $s['secondary_url'] ?? '' ) ) {
			$short = '' !== (string) ( $s['secondary_short'] ?? '' ) ? (string) $s['secondary_short'] : (string) $s['secondary_label'];
			$cta  .= sprintf( '<a class="di-btn di-btn--ghost" href="%s" rel="noopener"><span class="di-btn__long">%s</span><span class="di-btn__short">%s</span>%s</a>', esc_url( (string) $s['secondary_url'] ), esc_html( (string) $s['secondary_label'] ), esc_html( $short ), function_exists( 'irina_icon' ) ? irina_icon( 'arrow-right', 'di-icon di-icon--arrow' ) : '' );
		}
		return [
			'eyebrow'  => (string) ( $s['eyebrow'] ?? '' ),
			'title'    => (string) ( $s['title'] ?? '' ),
			'lead'     => (string) ( $s['lead'] ?? '' ),
			'variant'  => (string) ( $s['variant'] ?? 'sand' ),
			'short'    => true,
			'breath'   => 'night' === ( $s['variant'] ?? '' ),
			'crumbs'   => '' !== (string) ( $s['crumb'] ?? '' ) ? [ [ (string) $s['crumb'], '' ] ] : [],
			'image_id' => self::image_id( $s['image'] ?? null ),
			'focus'    => (string) ( $s['focus'] ?? '' ),
			'focus_m'  => (string) ( $s['focus_mobile'] ?? '' ),
			'cta'      => $cta,
		];
	}
}
