<?php
/**
 * Base para los tipos de contenido del sitio.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\PostTypes;

abstract class AbstractPostType {

	public const SLUG = '';

	abstract protected function labels(): array;

	abstract protected function args(): array;

	public function register(): void {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	public function register_post_type(): void {
		$defaults = [
			'labels'       => $this->labels(),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => false,
			'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ],
			'menu_icon'    => 'dashicons-heart',
			'map_meta_cap' => true,
		];
		register_post_type( static::SLUG, array_merge( $defaults, $this->args() ) );
	}
}
