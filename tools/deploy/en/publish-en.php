<?php
/**
 * Publica la versión inglesa completa (D-095, PLAN 13.7). Se ejecuta con `wp eval-file publish-en.php [yes] [legal-ok]`
 * desde tools/deploy/publish-en.sh, con pages.json y fichas-en.json en el mismo directorio.
 *
 * Preflight completo: solo inicia la publicación si
 * - cada página inglesa (Elementor) está aprobada por la Dra. en su guía (_di_page_approved_at);
 * - cada ficha inglesa está aprobada en su app (medically_approved o ready_to_publish);
 * - José acepta los legales en inglés (`legal-ok`; su revisión legal es 8.6).
 * Sin `yes` solo informa (dry-run). Primero la raíz /en/, luego las hijas y al final las fichas, para que hreflang, el selector y los
 * menús tengan sus destinos. La escritura es secuencial, no una transacción atómica de WordPress: si falla se intenta restaurar
 * los estados modificados y se termina con error. Un fallo de restauración requiere revisión manual; no revierte hooks ni cachés.
 *
 * @package DraIrina\Core
 */

// phpcs:disable WordPress.WP.AlternativeFunctions,WordPress.Security.EscapeOutput.OutputNotEscaped -- script de WP-CLI.

( static function ( array $argv ): void {
	// Argumentos posicionales (eval-file rechaza --opciones desconocidas); publish-en.sh traduce --yes y --legal-ok.
	$yes      = in_array( 'yes', $argv, true );
	$legal_ok = in_array( 'legal-ok', $argv, true );
	if ( array_diff( $argv, [ 'yes', 'legal-ok' ] ) ) {
		WP_CLI::error( 'Argumentos desconocidos; no se publica nada.' );
	}
	$dir    = __DIR__;
	$pages  = json_decode( (string) file_get_contents( "$dir/pages.json" ), true );
	$fichas = json_decode( (string) file_get_contents( "$dir/fichas-en.json" ), true );
	if ( ! is_array( $pages ) || ! is_array( $fichas ) || ! class_exists( '\DraIrina\Core\I18n\Language' ) ) {
		WP_CLI::error( 'Faltan pages.json, fichas-en.json o el módulo de idioma.' );
	}
	// Alcance cerrado D-095/D-098: un JSON vacío o recortado no puede saltarse revisiones.
	$expected_pages  = [ 'en', 'dr-irina-gonzalez-saez', 'ent', 'sleep', 'first-visit', 'contact', 'thank-you', 'faq', 'privacy-notice', 'medical-disclaimer', 'terms-of-use' ];
	$expected_fichas = [
		'snoring'                         => 'condicion',
		'obstructive-sleep-apnea'         => 'condicion',
		'home-sleep-test'                 => 'tratamiento',
		'cpap-therapy'                    => 'tratamiento',
		'snoring-and-sleep-apnea-surgery' => 'tratamiento',
	];
	$keys            = array_keys( $pages );
	sort( $keys );
	sort( $expected_pages );
	if ( $keys !== $expected_pages || count( $fichas ) !== count( $expected_fichas ) ) {
		WP_CLI::error( 'Manifiesto inglés incompleto o inesperado; no se publica nada.' );
	}
	$seen = [];
	foreach ( $pages as $slug => $def ) {
		$kind = in_array( $slug, [ 'privacy-notice', 'medical-disclaimer', 'terms-of-use' ], true ) ? 'html' : 'elementor';
		if ( ! is_array( $def ) || ( $def['kind'] ?? '' ) !== $kind ) {
			WP_CLI::error( 'Tipo de página inglés inesperado; no se publica nada.' );
		}
	}
	foreach ( $fichas as $f ) {
		if ( ! is_array( $f ) ) {
			WP_CLI::error( 'Ficha inválida en manifiesto; no se publica nada.' );
		}
		$slug = $f['slug'] ?? '';
		if ( ! isset( $expected_fichas[ $slug ] ) || isset( $seen[ $slug ] ) || ( $f['post_type'] ?? '' ) !== $expected_fichas[ $slug ] ) {
			WP_CLI::error( 'Manifiesto de fichas incorrecto; no se publica nada.' );
		}
		$seen[ $slug ] = true;
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
		$es_id  = \DraIrina\Core\I18n\Language::translation( $id, 'es' );
		if ( ! $es_id || 'publish' !== get_post_status( $es_id ) || 'es' !== \DraIrina\Core\I18n\Language::of( $es_id ) ) {
			$bad[] = "pareja española no publicada o inválida: {$slug}";
			continue;
		}
		if ( 'page' !== get_post_type( $id ) || 'en' !== \DraIrina\Core\I18n\Language::of( $id ) || ! in_array( $status, [ 'draft', 'pending', 'private', 'publish' ], true ) ) {
			$bad[] = "destino de página inválido: {$slug}";
			continue;
		}
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
		$es_id = \DraIrina\Core\I18n\Language::translation( $id, 'es' );
		if ( ! $es_id || 'publish' !== get_post_status( $es_id ) || 'es' !== \DraIrina\Core\I18n\Language::of( $es_id ) ) {
			$bad[] = "pareja española de ficha inválida: {$f['slug']}";
			continue;
		}
		$state = \DraIrina\Core\Workflow\MedicalReview::state( $id );
		$ok    = \DraIrina\Core\Workflow\MedicalReview::is_publishable( $id ) && in_array( get_post_status( $id ), [ 'draft', 'pending', 'private', 'publish' ], true );
		WP_CLI::log( sprintf( '  %-44s %-8s %s', '/en/sleep/' . $f['slug'] . '/', get_post_status( $id ), $ok ? 'aprobada por la Dra. ' . get_post_meta( $id, '_di_approved_at', true ) : "estado {$state}: falta la aprobación de la Dra." ) );
		if ( ! $ok ) {
			$bad[] = "ficha {$f['slug']}: {$state}";
		} elseif ( 'publish' !== get_post_status( $id ) ) {
			$todo[] = $id;
		}
	}
	if ( $bad ) {
		WP_CLI::warning( 'No se publica nada (todo o nada). Pendiente: ' . implode( '; ', $bad ) );
		if ( $yes ) {
			WP_CLI::error( 'Publicación bloqueada por preflight.' );
		}
		return;
	}
	if ( ! $yes ) {
		WP_CLI::success( count( $todo ) . ' entradas listas para publicar. Dry-run: repite con --yes para publicar.' );
		return;
	}
	// Raíz primero (las hijas heredan /en/ en su URL y el selector depende de ella).
	usort( $todo, static fn( $a, $b ) => ( $a === $root ? -1 : ( $b === $root ? 1 : 0 ) ) );
	$changed = [];
	foreach ( $todo as $id ) {
		$previous = get_post_status( $id );
		// Se registra antes: un hook puede cambiar el estado aun cuando WP devuelva un error.
		$changed[ $id ] = $previous;
		try {
			$r = wp_update_post(
				[
					'ID'          => $id,
					'post_status' => 'publish',
				],
				true
			);
		} catch ( \Throwable $error ) {
			$r = new \WP_Error( 'di_publish_exception', 'Excepción durante la publicación.' );
		}
		if ( is_wp_error( $r ) || 'publish' !== get_post_status( $id ) ) {
			$rollback_failed = [];
			foreach ( array_reverse( $changed, true ) as $restore_id => $restore_status ) {
				try {
					$restored = wp_update_post(
						[
							'ID'          => $restore_id,
							'post_status' => $restore_status,
						],
						true
					);
				} catch ( \Throwable $error ) {
					$restored = new \WP_Error( 'di_restore_exception', 'Excepción durante la restauración.' );
				}
				if ( is_wp_error( $restored ) || get_post_status( $restore_id ) !== $restore_status ) {
					$rollback_failed[] = $restore_id;
				}
			}
			WP_CLI::error( 'Falló la publicación; se detuvo y se intentó restaurar los estados de esta ejecución. ' . ( $rollback_failed ? 'REVISIÓN MANUAL: no restaurados IDs ' . implode( ',', $rollback_failed ) : 'Estados restaurados.' ) );
		}
		WP_CLI::log( '  publicada ' . get_permalink( $id ) . ' → ' . get_post_status( $id ) );
	}
	WP_CLI::success( 'Versión inglesa publicada.' );
} )( $args ?? [] );
