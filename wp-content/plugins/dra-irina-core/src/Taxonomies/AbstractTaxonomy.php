<?php
/**
 * Base para taxonomías.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Taxonomies;

abstract class AbstractTaxonomy {

	public const SLUG = '';

	/** @var string[] */
	protected array $object_types = [];

	abstract protected function labels(): array;

	abstract protected function args(): array;

	/** @return array<string, array{name:string, slug:string}> */
	protected function default_terms(): array {
		return [];
	}

	public function register(): void {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
		add_action( 'init', [ $this, 'insert_default_terms' ], 20 );
	}

	public function register_taxonomy(): void {
		$defaults = [
			'labels'            => $this->labels(),
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => false,
			'rewrite'           => false,
		];
		register_taxonomy( static::SLUG, $this->object_types, array_merge( $defaults, $this->args() ) );
	}

	public function insert_default_terms(): void {
		foreach ( $this->default_terms() as $term ) {
			if ( ! term_exists( $term['slug'], static::SLUG ) ) {
				wp_insert_term( $term['name'], static::SLUG, [ 'slug' => $term['slug'] ] );
			}
		}
	}
}
