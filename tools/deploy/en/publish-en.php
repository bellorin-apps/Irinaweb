<?php
/**
 * Publica la versión inglesa completa (D-095, PLAN 13.7). Se ejecuta con `wp eval-file publish-en.php [yes] [legal-ok]`
 * desde tools/deploy/publish-en.sh, con pages.json y fichas-en.json en el mismo directorio.
 *
 * Todo o nada: solo publica si
 * - cada página inglesa (Elementor) está aprobada por la Dra. en su guía (_di_page_approved_at);
 * - cada ficha inglesa está aprobada en su app (medically_approved o ready_to_publish);
 * - José acepta los legales en inglés (`legal-ok`; su revisión legal es 8.6).
 * Sin `yes` solo informa (dry-run). Primero la raíz /en/, luego las hijas y al final las fichas, para que hreflang, el selector y los
 * menús aparezcan a la vez.
 *
 * @package DraIrina\Core
 */

// phpcs:disable WordPress.WP.AlternativeFunctions,WordPress.Security.EscapeOutput.OutputNotEscaped -- script de WP-CLI.

( static function ( array $argv ): void {
	// Argumentos posicionales (eval-file rechaza --opciones desconocidas); publish-en.sh traduce --yes y --legal-ok.
	$yes      = in_array( 'yes', $argv, true );
	$legal_ok = in_array( 'legal-ok', $argv, true );
	$dir      = __DIR__;
	$pages    = json_decode( (string) file_get_contents( "$dir/pages.json" ), true );
	$fichas   = json_decode( (string) file_get_contents( "$dir/fichas-en.json" ), true );
	if ( ! is_array( $pages ) || ! is_array( $fichas ) || ! class_exists( '\DraIrina\Core\I18n\Language' ) ) {
		WP_CLI::error( 'Faltan pages.json, fichas-en.json o el módulo de idioma.' );
	}
	$root = \DraIrina\Core\I18n\Language::root_id();
	if ( ! $root ) {
		WP_CLI::error( 'No existe la raíz /en/ (ejecuta setup-en.sh).' );
	}
	$todo = [];
	$bad  = [];
	foreach ( $pages as $slug => $def ) {
		$id = 'en' === $slug ? $root : (int) ( get_page_by_path( 'en/' . $slug )->ID ?? 0 );
		if ( ! $id ) {
			$bad[] = "página /en/{$slug}/ no existe";
			continue;
		}
		$status = get_post_status( $id );
		if ( 'html' === ( $def['kind'] ?? '' ) ) {
			$ok  = $legal_ok;
			$why = $ok ? 'legal aceptado por José (--legal-ok)' : 'legal: falta --legal-ok (OK de José)';
		} else {
			$at  = (string) get_post_meta( $id, '_di_page_approved_at', true );
			$ok  = '' !== $at;
			$why = $ok ? "aprobada por la Dra. {$at}" : 'falta la aprobación de la Dra. en la guía';
		}
		WP_CLI::log( sprintf( '  %-44s %-8s %s', '/en/' . ( 'en' === $slug ? '' : $slug . '/' ), $status, $why ) );
		if ( ! $ok ) {
			$bad[] = ( 'en' === $slug ? '/en/' : "/en/{$slug}/" ) . ": {$why}";
		} elseif ( 'publish' !== $status ) {
			$todo[] = $id;
		}
	}
	foreach ( $fichas as $f ) {
		$q = get_posts(
			[
				'post_type'      => $f['post_type'],
				'post_name__in'  => [ $f['slug'] ],
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'meta_key'       => \DraIrina\Core\I18n\Language::META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => 'en', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);
		if ( ! $q ) {
			$bad[] = "ficha /en/sleep/{$f['slug']}/ no existe";
			continue;
		}
		$id    = (int) $q[0]->ID;
		$state = \DraIrina\Core\Workflow\MedicalReview::state( $id );
		$ok    = \DraIrina\Core\Workflow\MedicalReview::is_publishable( $id );
		WP_CLI::log( sprintf( '  %-44s %-8s %s', '/en/sleep/' . $f['slug'] . '/', get_post_status( $id ), $ok ? 'aprobada por la Dra. ' . get_post_meta( $id, '_di_approved_at', true ) : "estado {$state}: falta la aprobación de la Dra." ) );
		if ( ! $ok ) {
			$bad[] = "ficha {$f['slug']}: {$state}";
		} elseif ( 'publish' !== get_post_status( $id ) ) {
			$todo[] = $id;
		}
	}
	if ( $bad ) {
		WP_CLI::warning( 'No se publica nada (todo o nada). Pendiente: ' . implode( '; ', $bad ) );
		return;
	}
	if ( ! $yes ) {
		WP_CLI::success( count( $todo ) . ' entradas listas para publicar. Dry-run: repite con --yes para publicar.' );
		return;
	}
	// Raíz primero (las hijas heredan /en/ en su URL y el selector depende de ella).
	usort( $todo, static fn( $a, $b ) => ( $a === $root ? -1 : ( $b === $root ? 1 : 0 ) ) );
	foreach ( $todo as $id ) {
		$r = wp_update_post(
			[
				'ID'          => $id,
				'post_status' => 'publish',
			],
			true
		);
		WP_CLI::log( ( is_wp_error( $r ) ? '  ERROR ' . $r->get_error_message() . ' ' : '  publicada ' ) . get_permalink( $id ) . ' → ' . get_post_status( $id ) );
	}
	WP_CLI::success( 'Versión inglesa publicada.' );
} )( $args ?? [] );
