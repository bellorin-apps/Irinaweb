<?php
/**
 * Plugin Name: Inyección de fallos (pruebas locales 2026-10-19)
 * Solo actúa si existe tmp/wp-en-local/fallo.json. Modos: wperror | retenido | excepcion | http500 | sin-hreflang | php-fatal.
 * {"modo": "...", "slug": "en/contact", "restaurar_falla": "en/ent"} — restaurar_falla hace fallar la restauración de esa página.
 */
$di_cfg_file = WP_CONTENT_DIR . '/../../fallo.json';
if ( ! is_file( $di_cfg_file ) ) {
	return;
}
$di_cfg = json_decode( (string) file_get_contents( $di_cfg_file ), true ) ?: [];
$di_id  = static function ( string $path ): int {
	$p = get_page_by_path( $path );
	return $p ? (int) $p->ID : 0;
};
$di_log = static function ( string $m ): void {
	file_put_contents( WP_CONTENT_DIR . '/../../fallo.log', gmdate( 'c' ) . " $m\n", FILE_APPEND );
};
$modo = $di_cfg['modo'] ?? '';
if ( 'php-fatal' === $modo && defined( 'WP_CLI' ) && WP_CLI ) {
	add_action( 'init', static function () use ( $di_log ) { $di_log( 'php-fatal en WP-CLI' ); trigger_error( 'Fallo PHP inyectado', E_USER_ERROR ); } );
}
if ( in_array( $modo, [ 'wperror', 'retenido', 'excepcion' ], true ) || ! empty( $di_cfg['restaurar_falla'] ) ) {
	add_filter( 'wp_insert_post_empty_content', static function ( $empty, $data ) use ( $di_cfg, $di_id, $modo, $di_log ) {
		if ( 'wperror' === $modo && 'publish' === ( $data['post_status'] ?? '' ) && (int) ( $data['ID'] ?? 0 ) === $di_id( $di_cfg['slug'] ?? '' ) ) {
			$di_log( 'wperror en ' . $data['ID'] );
			return true; // wp_update_post(..., true) devuelve WP_Error('empty_content')
		}
		return $empty;
	}, 99, 2 );
	add_filter( 'wp_insert_post_data', static function ( $data, $postarr ) use ( $di_cfg, $di_id, $modo, $di_log ) {
		$id = (int) ( $postarr['ID'] ?? 0 );
		if ( $id && $id === $di_id( $di_cfg['slug'] ?? '' ) && 'publish' === $data['post_status'] ) {
			if ( 'retenido' === $modo ) { $di_log( "retenido en $id" ); $data['post_status'] = 'draft'; }
			if ( 'excepcion' === $modo ) { $di_log( "excepcion en $id" ); throw new RuntimeException( 'Fallo inyectado' ); }
		}
		if ( $id && ! empty( $di_cfg['restaurar_falla'] ) && $id === $di_id( $di_cfg['restaurar_falla'] ) && 'draft' === $data['post_status'] ) {
			$di_log( "restauracion falla en $id" );
			throw new RuntimeException( 'Fallo inyectado en la restauración' );
		}
		return $data;
	}, 99, 2 );
}
if ( 'crear-falla' === $modo ) {
	add_filter( 'wp_insert_post_empty_content', static function ( $empty, $data ) use ( $di_cfg, $di_log ) {
		if ( empty( $data['ID'] ) && ( $data['post_name'] ?? '' ) === ( $di_cfg['slug'] ?? '' ) ) { $di_log( 'crear-falla en ' . $data['post_name'] ); return true; }
		return $empty;
	}, 99, 2 );
}
if ( 'http500' === $modo ) {
	add_action( 'template_redirect', static function () use ( $di_cfg ) {
		if ( str_starts_with( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), '/' . trim( $di_cfg['slug'] ?? 'en/faq', '/' ) . '/' ) ) {
			status_header( 500 );
			exit( 'Error 500 inyectado' );
		}
	}, 0 );
}
if ( 'sin-hreflang' === $modo ) {
	// Quita del HTML los alternates y el selector, como si el módulo de idioma no los imprimiera.
	add_action( 'template_redirect', static function () {
		ob_start( static fn( $h ) => preg_replace( [ '/<link[^>]+hreflang=[^>]+>/i', '/class="di-lang/' ], [ '', 'class="x-lang' ], $h ) );
	}, 0 );
}
