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
		'DRA_HERO'  => 'hero-dra',
		'DRA_ENDO'  => 'dra-endoscopio',
		'PC_HERO'   => 'hero-primera',
		'PC_BG'     => 'pc-fondo',
		'CO_HERO'   => 'hero-contacto',
		'SU_HERO'   => 'hero-sueno',
	];
	$fill  = static function ( string $json ) use ( $media ): string {
		foreach ( $media as $marker => $slug ) {
			$att  = get_posts(
				[
					'post_type'      => 'attachment',
					'post_name__in'  => [ $slug ],
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
				'post_name__in'  => [ $slug ],
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
		if ( 'html' === ( $def['kind'] ?? 'elementor' ) ) {
			// Legales: HTML con los shortcodes del consultorio, plantilla por defecto (como en español).
			wp_update_post(
				[
					'ID'           => $id,
					'post_content' => wp_slash( (string) file_get_contents( "$dir/{$slug}.html" ) ),
				]
			);
			delete_post_meta( $id, '_wp_page_template' );
		} else {
			$data = $fill( (string) file_get_contents( "$dir/{$slug}.elementor.json" ) );
			update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
			update_post_meta( $id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $id, '_elementor_data', wp_slash( $data ) );
			delete_post_meta( $id, '_elementor_css' );
			delete_post_meta( $id, '_elementor_element_cache' );
		}
		if ( 'thank-you' === $slug ) {
			update_post_meta( $id, 'rank_math_robots', [ 'noindex', 'nofollow' ] ); // Como /gracias/: destino del formulario.
		}
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
	// Fichas de sueño en inglés (13.4): CPT en BORRADOR con «requiere revisión médica»; la Dra. las aprueba en su app.
	// No se tocan las ya publicadas ni las ya aprobadas (lo que aprobó la Dra. es lo que se publica).
	$fichas_file = "$dir/fichas-en.json";
	if ( is_readable( $fichas_file ) ) {
		$fichas = json_decode( (string) file_get_contents( $fichas_file ), true );
		$en_ids = [];
		$es_of  = static function ( string $type, string $slug ): int {
			$q = get_posts(
				[
					'post_type'      => $type,
					'post_name__in'  => [ $slug ],
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'meta_query'     => [
						[
							'key'     => \DraIrina\Core\I18n\Language::META,
							'compare' => 'NOT EXISTS',
						],
					], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				]
			);
			return $q ? (int) $q[0]->ID : 0;
		};
		foreach ( (array) $fichas as $f ) {
			$existing = get_posts(
				[
					'post_type'      => $f['post_type'],
					'post_name__in'  => [ $f['slug'] ],
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'meta_key'       => \DraIrina\Core\I18n\Language::META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value'     => 'en', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				]
			);
			$fid      = $existing ? (int) $existing[0]->ID : 0;
			if ( $fid && ( 'publish' === get_post_status( $fid ) || \DraIrina\Core\Workflow\MedicalReview::is_publishable( $fid ) ) ) {
				WP_CLI::log( "  ficha /en/sleep/{$f['slug']}/: aprobada o publicada, sin cambios" );
				$en_ids[ $f['es_slug'] ] = $fid;
				continue;
			}
			$args = [
				'post_type'    => $f['post_type'],
				'post_status'  => 'draft',
				'post_title'   => $f['title'],
				'post_name'    => $f['slug'],
				'post_content' => wp_slash( $f['content'] ),
			];
			if ( $fid ) {
				$args['ID'] = $fid;
			}
			$fid = (int) wp_insert_post( $args, true );
			if ( ! $fid ) {
				WP_CLI::warning( "No se pudo crear la ficha {$f['slug']}" );
				continue;
			}
			// Primero idioma y estado: así la puerta médica la trata como borrador pendiente de revisión.
			update_post_meta( $fid, \DraIrina\Core\I18n\Language::META, 'en' );
			wp_set_object_terms( $fid, \DraIrina\Core\Taxonomies\EstadoMedico::MEDICAL_REVIEW_REQ, \DraIrina\Core\Taxonomies\EstadoMedico::SLUG, false );
			wp_set_object_terms( $fid, (array) $f['terms']['area'], 'area', false );
			wp_set_object_terms( $fid, (array) $f['terms']['zona'], 'zona', false );
			$es_id = $es_of( $f['post_type'], $f['es_slug'] );
			if ( $es_id ) {
				update_post_meta( $fid, \DraIrina\Core\I18n\Language::PAIR, $es_id );
			}
			foreach ( (array) $f['meta'] as $key => $value ) {
				update_post_meta( $fid, 'di_' . $key, $value );
			}
			update_post_meta( $fid, 'rank_math_title', $f['seo_title'] );
			$en_ids[ $f['es_slug'] ] = $fid;
			WP_CLI::log( sprintf( '  ficha /en/sleep/%s/: %d (%s), pareja española %d, estado %s', $f['slug'], $fid, get_post_status( $fid ), $es_id, \DraIrina\Core\Workflow\MedicalReview::state( $fid ) ) );
		}
		// Relaciones: slugs españoles → IDs de las fichas inglesas (sin versión inglesa no se enlaza).
		foreach ( (array) $fichas as $f ) {
			$fid = $en_ids[ $f['es_slug'] ] ?? 0;
			if ( ! $fid || 'publish' === get_post_status( $fid ) || \DraIrina\Core\Workflow\MedicalReview::is_publishable( $fid ) ) {
				continue;
			}
			foreach ( (array) $f['relations'] as $key => $slugs ) {
				update_post_meta( $fid, 'di_' . $key, array_values( array_filter( array_map( static fn( $sl ) => (int) ( $en_ids[ $sl ] ?? 0 ), (array) $slugs ) ) ) );
			}
		}
	}

	// Credenciales en inglés (meta _di_en por slug; la plantilla la usa en /en/).
	$creds = json_decode( (string) file_get_contents( "$dir/credentials-en.json" ), true );
	$done  = 0;
	foreach ( (array) $creds as $cred_slug => $en ) {
		$c = get_posts(
			[
				'post_type'      => 'credencial',
				'post_name__in'  => [ $cred_slug ],
				'post_status'    => 'any',
				'posts_per_page' => 1,
			]
		);
		if ( $c ) {
			update_post_meta( $c[0]->ID, '_di_en', $en );
			++$done;
		}
	}
	WP_CLI::log( "  Credenciales con texto en inglés: {$done}" );

	// Menús ingleses (los borradores los oculta el filtro del tema hasta que se publiquen).
	$menus     = [
		'primary_en' => [ 'Main (English)', [ [ 'en', 'Home' ], [ 'ent', 'ENT' ], [ 'sleep', 'Sleep' ], [ 'dr-irina-gonzalez-saez', 'About' ], [ 'first-visit', 'First visit' ], [ 'contact', 'Contact' ] ] ],
		'footer_en'  => [ 'Footer (English)', [ [ 'ent', 'Otolaryngology (ENT)' ], [ 'sleep', 'Snoring and sleep apnea' ], [ 'first-visit', 'First visit' ], [ 'faq', 'FAQ' ], [ 'contact', 'Contact' ] ] ],
		'legal_en'   => [ 'Legal (English)', [ [ 'privacy-notice', 'Privacy notice' ], [ 'medical-disclaimer', 'Medical disclaimer' ], [ 'terms-of-use', 'Terms of use' ] ] ],
	];
	$locations = (array) get_theme_mod( 'nav_menu_locations', [] );
	foreach ( $menus as $location => [ $name, $items ] ) {
		$menu    = wp_get_nav_menu_object( $name );
		$menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $name );
		$have    = array_map( static fn( $i ) => (int) $i->object_id, (array) wp_get_nav_menu_items( $menu_id ) );
		foreach ( $items as $pos => [ $page_slug, $label ] ) {
			$page = 'en' === $page_slug ? $find( 'en', 0 ) : $find( $page_slug, $root );
			if ( $page && ! in_array( (int) $page->ID, $have, true ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					[
						'menu-item-object-id' => $page->ID,
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-title'     => $label,
						'menu-item-position'  => $pos + 1,
						'menu-item-status'    => 'publish',
					]
				);
			}
		}
		$locations[ $location ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	WP_CLI::log( '  Menús ingleses asignados: ' . implode( ', ', array_keys( $menus ) ) );

	// Sin flush aquí: con --skip-plugins=elementor se perderían las reglas de Elementor. Borrar la opción hace que WordPress las
	// regenere en la siguiente petición web con todos los plugins (como setup-site.sh); setup-en.sh hace esa petición.
	delete_option( 'rewrite_rules' );
	WP_CLI::log( '  Reglas de reescritura: se regeneran en la siguiente visita (/en/sleep/{slug}/).' );
} )();
