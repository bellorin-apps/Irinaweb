<?php
/**
 * Taxonomía area: orl | sueno. Decide el prefijo de URL (/padecimientos|/tratamientos vs /sueno).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Taxonomies;

final class Area extends AbstractTaxonomy {

	public const SLUG = 'area';

	protected array $object_types = [ 'condicion', 'tratamiento', 'recurso' ];

	protected function labels(): array {
		return [
			'name'          => __( 'Áreas', 'dra-irina-core' ),
			'singular_name' => __( 'Área', 'dra-irina-core' ),
		];
	}

	protected function args(): array {
		return [ 'hierarchical' => true ];
	}

	protected function default_terms(): array {
		return [
			[
				'name' => 'Otorrinolaringología',
				'slug' => 'orl',
			],
			[
				'name' => 'Sueño',
				'slug' => 'sueno',
			],
		];
	}
}
