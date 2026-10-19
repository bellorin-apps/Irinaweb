<?php
// Router del servidor embebido de PHP para la copia local: archivos reales tal cual; lo demás, a WordPress.
$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
if ( '/' !== $path && is_file( __DIR__ . '/site' . $path ) && ! str_ends_with( $path, '.php' ) ) { return false; }
if ( str_ends_with( (string) $path, '.php' ) && is_file( __DIR__ . '/site' . $path ) ) { require __DIR__ . '/site' . $path; return; }
$_SERVER['SCRIPT_NAME'] = '/index.php'; $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/site/index.php';
require __DIR__ . '/site/index.php';
