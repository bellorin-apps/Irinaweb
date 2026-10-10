<?php
/**
 * Receptor del briefing de la Dra. Irina. Sin WordPress: guarda JSON fuera del webroot, sin correos (D-040).
 * El token se define en config.php (generado al desplegar; no versionado).
 */

declare( strict_types=1 );

header( 'Content-Type: application/json; charset=utf-8' );
header( 'Cache-Control: no-store' );
header( 'X-Robots-Tag: noindex' );

$config_file = __DIR__ . '/config.php';
if ( ! is_readable( $config_file ) ) {
	http_response_code( 500 );
	echo json_encode( [ 'error' => 'config' ] );
	exit;
}
$config = require $config_file; // ['token' => '...', 'notify' => 'correo@', 'data_dir' => '/ruta/privada']

$token = $_SERVER['REQUEST_METHOD'] === 'GET' ? ( $_GET['token'] ?? '' ) : '';
$body  = null;
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
	$raw  = file_get_contents( 'php://input', false, null, 0, 200001 );
	$body = json_decode( (string) $raw, true );
	$token = is_array( $body ) ? ( $body['token'] ?? '' ) : '';
}
if ( ! is_string( $token ) || '' === $token || ! hash_equals( (string) $config['token'], $token ) ) {
	http_response_code( 403 );
	echo json_encode( [ 'error' => 'token' ] );
	exit;
}

$dir = rtrim( (string) $config['data_dir'], '/' );
if ( ! is_dir( $dir ) ) {
	mkdir( $dir, 0700, true );
}
$latest = $dir . '/briefing-latest.json';

if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
	if ( is_readable( $latest ) ) {
		readfile( $latest );
	} else {
		echo json_encode( [ 'answers' => new stdClass() ] );
	}
	exit;
}

if ( ! is_array( $body ) || ! isset( $body['answers'] ) || ! is_array( $body['answers'] ) ) {
	http_response_code( 400 );
	echo json_encode( [ 'error' => 'body' ] );
	exit;
}
if ( strlen( (string) $raw ) > 200000 ) {
	http_response_code( 413 );
	echo json_encode( [ 'error' => 'size' ] );
	exit;
}

$record = [
	'answers'   => $body['answers'],
	'updatedAt' => gmdate( 'c' ),
	'version'   => 1,
	'ip_hash'   => hash( 'sha256', (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) ),
];
$json = json_encode( $record, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
if ( false === file_put_contents( $latest, $json, LOCK_EX ) ) {
	http_response_code( 500 );
	echo json_encode( [ 'error' => 'storage' ] );
	exit;
}
file_put_contents( $dir . '/briefing-' . gmdate( 'Ymd-His' ) . '.json', $json, LOCK_EX );

echo json_encode( [ 'ok' => true, 'updatedAt' => $record['updatedAt'] ] );
