<?php
/**
 * Registro de widgets Elementor (solo si Elementor está activo).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering\Elementor;

final class Widgets {

	public function register(): void {
		add_action(
			'elementor/widgets/register',
			static function ( $manager ): void {
				foreach ( [ Hero::class, Areas::class, Motivos::class, Doctora::class, Pasos::class, Sueno::class, Ubicacion::class, Cta::class, Formulario::class, Bleed::class, Narrative::class, Credenciales::class, Timeline::class, Faq::class, Listado::class, Enlaces::class ] as $class ) {
					$manager->register( new $class() );
				}
			}
		);
		add_action(
			'elementor/elements/categories_registered',
			static function ( $manager ): void {
				if ( ! method_exists( $manager, 'get_categories' ) || isset( $manager->get_categories()['dra-irina'] ) ) {
					return;
				}
				$manager->add_category(
					'dra-irina',
					[
						'title' => __( 'Dra. Irina', 'dra-irina-core' ),
						'icon'  => 'eicon-heart',
					]
				);
			}
		);
	}
}
