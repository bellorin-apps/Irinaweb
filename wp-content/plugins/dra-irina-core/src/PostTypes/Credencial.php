<?php
/**
 * CPT credencial: formación, certificaciones, membresías, publicaciones, cursos, experiencia.
 * No es público como URL; alimenta la página de la Dra. y el schema Physician.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\PostTypes;

final class Credencial extends AbstractPostType {

	public const SLUG = 'credencial';

	protected function labels(): array {
		return [
			'name'          => __( 'Credenciales', 'dra-irina-core' ),
			'singular_name' => __( 'Credencial', 'dra-irina-core' ),
			'add_new_item'  => __( 'Añadir credencial', 'dra-irina-core' ),
			'edit_item'     => __( 'Editar credencial', 'dra-irina-core' ),
			'menu_name'     => __( 'Credenciales', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'supports'            => [ 'title', 'page-attributes' ],
			'menu_position'       => 24,
			'menu_icon'           => 'dashicons-awards',
			'capability_type'     => [ 'credencial', 'credenciales' ],
		];
	}
}
