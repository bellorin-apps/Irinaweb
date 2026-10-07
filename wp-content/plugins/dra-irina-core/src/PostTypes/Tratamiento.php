<?php
/**
 * CPT tratamiento (procedimiento, estudio, terapia).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\PostTypes;

final class Tratamiento extends AbstractPostType {

	public const SLUG = 'tratamiento';

	protected function labels(): array {
		return [
			'name'          => __( 'Tratamientos', 'dra-irina-core' ),
			'singular_name' => __( 'Tratamiento', 'dra-irina-core' ),
			'add_new_item'  => __( 'Añadir tratamiento', 'dra-irina-core' ),
			'edit_item'     => __( 'Editar tratamiento', 'dra-irina-core' ),
			'menu_name'     => __( 'Tratamientos', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [
			'has_archive'     => 'tratamientos',
			'rewrite'         => [
				'slug'       => 'tratamientos',
				'with_front' => false,
			],
			'menu_position'   => 22,
			'menu_icon'       => 'dashicons-clipboard',
			'capability_type' => [ 'tratamiento', 'tratamientos' ],
			'taxonomies'      => [ 'area', 'zona', 'estado_medico' ],
		];
	}
}
