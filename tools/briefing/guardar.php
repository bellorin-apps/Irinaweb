<?php
/**
 * Receptor del briefing de la Dra. Irina. Sin WordPress: guarda JSON fuera del webroot y avisa por correo.
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
	$raw  = file_get_contents( 'php://input' );
	$body = json_decode( (string) $raw, true );
	$token = is_array( $body ) ? (string) ( $body['token'] ?? '' ) : '';
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
file_put_contents( $latest, $json, LOCK_EX );
file_put_contents( $dir . '/briefing-' . gmdate( 'Ymd-His' ) . '.json', $json, LOCK_EX );

// Aviso cuando la Dra. confirma (última sección) o cada 20 guardados.
$confirmed = ! empty( $body['answers']['confirmo'] );
$counter   = $dir . '/.count';
$n         = is_readable( $counter ) ? (int) file_get_contents( $counter ) + 1 : 1;
file_put_contents( $counter, (string) $n );
if ( ! empty( $config['notify'] ) && ( $confirmed || 0 === $n % 20 ) ) {
	$subject = $confirmed ? 'Briefing Dra. Irina: CONFIRMADO' : 'Briefing Dra. Irina: avance guardado';
	@mail( (string) $config['notify'], $subject, "Respuestas guardadas en el servidor.\n\n" . $json, "From: briefing@" . ( $_SERVER['SERVER_NAME'] ?? 'drairinagonzalez.com' ) );
}
echo json_encode( [ 'ok' => true, 'updatedAt' => $record['updatedAt'] ] );
