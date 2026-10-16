<?php
/**
 * Carga la versión inglesa en BORRADOR (D-095, PLAN 13.3). Se ejecuta con `wp eval-file` desde tools/deploy/setup-en.sh, con los
 * JSON de tools/content/en/ en el mismo directorio. Idempotente.
 *
 * Reglas:
 * - nunca publica;
 * - no toca una página inglesa ya publicada (cambiarla exige la aprobación de la Dra., 13.6);
 * - los textos del consultorio en inglés solo se escriben si están vacíos;
 * - empareja cada página con su equivalente española (_di_translation_of).
 *
 * @package DraIrina\Core
 */

// phpcs:disable WordPress.WP.AlternativeFunctions,WordPress.Security.EscapeOutput.OutputNotEscaped -- script de WP-CLI.

( static function (): void {
	$dir   = __DIR__;
	$pages = json_decode( (string) file_get_contents( "$dir/pages.json" ), true );
	$seo   = json_decode( (string) file_get_contents( "$dir/seo.json" ), true );
	if ( ! is_array( $pages ) || ! class_exists( '\DraIrina\Core\I18n\Language' ) ) {
		WP_CLI::error( 'Faltan pages.json o el módulo de idioma de dra-irina-core (despliega el código primero).' );
	}

	// Adjuntos que sustituyen los marcadores de imagen de los builds (mismos que setup-site/setup-pages).
	$media = [
		'HERO'      => 'hero-home',
		'ORL_IMG'   => 'area-orl',
		'SUENO_IMG' => 'area-sueno',
		'DRA_IMG'   => 'dra-escucharte',
		'DRA_BG'    => 'dra-escucharte-bg',
	];
	$fill  = static function ( string $json ) use ( $media ): string {
		foreach ( $media as $marker => $slug ) {
			$att  = get_posts(
				[
					'post_type'      => 'attachment',
					'name'           => $slug,
					'post_status'    => 'any',
					'posts_per_page' => 1,
				]
			);
			$id   = $att ? (int) $att[0]->ID : 0;
			$url  = $id ? (string) wp_get_attachment_url( $id ) : '';
			$json = str_replace( [ "__{$marker}_ID__", "__{$marker}_URL__" ], [ (string) ( $id ? $id : '' ), $url ], $json );
		}
		return $json;
	};

	$find = static function ( string $slug, int $parent_id ) {
		$q = get_posts(
			[
				'post_type'      => 'page',
				'name'           => $slug,
				'post_parent'    => $parent_id,
				'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ],
				'posts_per_page' => 1,
			]
		);
		return $q ? $q[0] : null;
	};

	// La raíz primero: las demás páginas son sus hijas.
	uksort( $pages, static fn( $a, $b ) => ( 'en' === $a ? -1 : ( 'en' === $b ? 1 : strcmp( $a, $b ) ) ) );
	$root = 0;
	foreach ( $pages as $slug => $def ) {
		$parent = 'en' === $slug ? 0 : $root;
		if ( 'en' !== $slug && ! $root ) {
			WP_CLI::error( 'No existe la raíz /en/.' );
		}
		$post = $find( $slug, $parent );
		if ( $post && 'publish' === $post->post_status ) {
			WP_CLI::log( "  /en/ {$slug}: PUBLICADA, no se modifica (cambios con aprobación de la Dra., 13.6)" );
			if ( 'en' === $slug ) {
				$root = (int) $post->ID;
			}
			continue;
		}
		$id = $post ? (int) $post->ID : (int) wp_insert_post(
			[
				'post_type'   => 'page',
				'post_status' => 'draft',
				'post_title'  => $def['title'],
				'post_name'   => $slug,
				'post_parent' => $parent,
			],
			true
		);
		if ( ! $id ) {
			WP_CLI::error( "No se pudo crear {$slug}" );
		}
		if ( 'en' === $slug ) {
			$root = $id;
		}
		$data = $fill( (string) file_get_contents( "$dir/{$slug}.elementor.json" ) );
		update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
		update_post_meta( $id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $id, '_elementor_data', wp_slash( $data ) );
		delete_post_meta( $id, '_elementor_css' );
		delete_post_meta( $id, '_elementor_element_cache' );
		$es_id = '__front__' === $def['es'] ? (int) get_option( 'page_on_front' ) : (int) ( get_page_by_path( $def['es'] )->ID ?? 0 );
		if ( $es_id ) {
			update_post_meta( $id, \DraIrina\Core\I18n\Language::PAIR, $es_id );
		}
		if ( isset( $seo[ $slug ] ) ) {
			update_post_meta( $id, 'rank_math_title', $seo[ $slug ]['title'] );
			update_post_meta( $id, 'rank_math_description', $seo[ $slug ]['description'] );
		}
		WP_CLI::log( sprintf( '  /en/ %s: %s (%d, %s), pareja española %d', $slug, $post ? 'actualizada' : 'creada', $id, get_post_status( $id ), $es_id ) );
	}

	// Textos del consultorio en inglés: solo los vacíos.
	$en_texts = json_decode( (string) file_get_contents( "$dir/practice-en.json" ), true );
	$opt      = get_option( \DraIrina\Core\Settings\PracticeSettings::OPTION, [] );
	$opt      = is_array( $opt ) ? $opt : [];
	$set      = [];
	foreach ( (array) $en_texts as $key => $value ) {
		if ( empty( $opt[ $key ] ) ) {
			$opt[ $key ] = $value;
			$set[]       = $key;
		}
	}
	if ( $set ) {
		update_option( \DraIrina\Core\Settings\PracticeSettings::OPTION, $opt );
	}
	WP_CLI::log( '  Textos del consultorio en inglés escritos: ' . ( $set ? implode( ', ', $set ) : 'ninguno (ya tenían valor)' ) );
	flush_rewrite_rules( false );
	WP_CLI::log( '  Reglas de reescritura actualizadas (/en/sleep/{slug}/).' );
} )();
