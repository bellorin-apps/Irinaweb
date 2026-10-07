<?php
/**
 * CPT recurso (artículo o guía para pacientes).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\PostTypes;

final class Recurso extends AbstractPostType {

	public const SLUG = 'recurso';

	protected function labels(): array {
		return [
			'name'          => __( 'Recursos', 'dra-irina-core' ),
			'singular_name' => __( 'Recurso', 'dra-irina-core' ),
			'add_new_item'  => __( 'Añadir recurso', 'dra-irina-core' ),
			'edit_item'     => __( 'Editar recurso', 'dra-irina-core' ),
			'menu_name'     => __( 'Recursos', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [
			'has_archive'     => 'recursos',
			'rewrite'         => [
				'slug'       => 'recursos',
				'with_front' => false,
			],
			'menu_position'   => 23,
			'menu_icon'       => 'dashicons-book-alt',
			'capability_type' => [ 'recurso', 'recursos' ],
			'taxonomies'      => [ 'area', 'estado_medico' ],
		];
	}
}
