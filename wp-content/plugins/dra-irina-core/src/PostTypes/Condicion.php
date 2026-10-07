<?php
/**
 * CPT condicion (padecimiento).
 *
 * URL: /padecimientos/{slug}/ o /sueno/{slug}/ según la taxonomía area (ver Rewrite en MetaRegistry).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\PostTypes;

final class Condicion extends AbstractPostType {

	public const SLUG = 'condicion';

	protected function labels(): array {
		return [
			'name'          => __( 'Padecimientos', 'dra-irina-core' ),
			'singular_name' => __( 'Padecimiento', 'dra-irina-core' ),
			'add_new_item'  => __( 'Añadir padecimiento', 'dra-irina-core' ),
			'edit_item'     => __( 'Editar padecimiento', 'dra-irina-core' ),
			'menu_name'     => __( 'Padecimientos', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [
			'has_archive'     => 'padecimientos',
			'rewrite'         => [
				'slug'       => 'padecimientos',
				'with_front' => false,
			],
			'menu_position'   => 21,
			'menu_icon'       => 'dashicons-visibility',
			'capability_type' => [ 'condicion', 'condiciones' ],
			'taxonomies'      => [ 'area', 'zona', 'estado_medico' ],
		];
	}
}
