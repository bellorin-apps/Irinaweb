<?php
// Importa a la copia local las opciones exportadas (sin claves), adaptando URLs al host local.
$o = json_decode( file_get_contents( $args[0] ), true );
foreach ( $o as $k => $v ) {
	if ( null === $v || false === $v ) { continue; }
	update_option( $k, $v );
}
echo count( $o ), " opciones importadas\n";
