<?php
/**
 * Elementor bajo control: sin iconos ni librerías innecesarias, categoría propia de widgets.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

// Elementor no carga Font Awesome ni eicons en el front (usamos Lucide SVG inline).
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if ( is_admin() || ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
			return;
		}
		foreach ( [ 'elementor-icons', 'font-awesome-5-all', 'font-awesome-4-shim', 'elementor-icons-shared-0', 'elementor-icons-fa-solid', 'elementor-icons-fa-regular', 'elementor-icons-fa-brands' ] as $handle ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}
	},
	50
);

// Categoría para los widgets propios registrados por dra-irina-core.
add_action(
	'elementor/elements/categories_registered',
	static function ( $manager ): void {
		$manager->add_category(
			'dra-irina',
			[
				'title' => __( 'Dra. Irina', 'irina-gonzalez' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}
);
