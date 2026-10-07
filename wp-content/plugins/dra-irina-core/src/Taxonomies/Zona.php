<?php
/**
 * Taxonomía zona anatómica para agrupar padecimientos en el hub.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Taxonomies;

final class Zona extends AbstractTaxonomy {

	public const SLUG = 'zona';

	protected array $object_types = [ 'condicion', 'tratamiento' ];

	protected function labels(): array {
		return [
			'name'          => __( 'Zonas', 'dra-irina-core' ),
			'singular_name' => __( 'Zona', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [ 'hierarchical' => true ];
	}

	protected function default_terms(): array {
		return [
			[
				'name' => 'Oído',
				'slug' => 'oido',
			],
			[
				'name' => 'Nariz y senos paranasales',
				'slug' => 'nariz',
			],
			[
				'name' => 'Garganta y voz',
				'slug' => 'garganta',
			],
			[
				'name' => 'Cuello',
				'slug' => 'cuello',
			],
			[
				'name' => 'Sueño y respiración',
				'slug' => 'sueno',
			],
		];
	}
}
