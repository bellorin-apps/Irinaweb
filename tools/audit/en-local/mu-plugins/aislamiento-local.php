<?php
/**
 * Plugin Name: Aislamiento local (pruebas 2026-10-19)
 * Correo anulado (se registra, no se envía) y cualquier petición HTTP fuera de localhost bloqueada.
 */
add_filter( 'pre_wp_mail', static function ( $null, $atts ) {
	file_put_contents( WP_CONTENT_DIR . '/../../mail-capturado.log', gmdate( 'c' ) . ' ' . wp_json_encode( [ 'to' => $atts['to'] ?? '', 'subject' => $atts['subject'] ?? '' ] ) . "\n", FILE_APPEND );
	// Con correo-ok.flag se simula un envío correcto (true) sin enviar nada; sin el archivo, fallo.
	return is_file( WP_CONTENT_DIR . '/../../correo-ok.flag' ) ? true : false; // nada sale
}, 1, 2 );
add_filter( 'pre_http_request', static function ( $pre, $args, $url ) {
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );
	if ( in_array( $host, [ '127.0.0.1', 'localhost' ], true ) ) { return $pre; }
	file_put_contents( WP_CONTENT_DIR . '/../../http-bloqueado.log', gmdate( 'c' ) . ' ' . ( $args['method'] ?? 'GET' ) . ' ' . $url . "
", FILE_APPEND );
	return new WP_Error( 'blocked', 'Red externa bloqueada en la copia local' );
}, 1, 3 );
