<?php
// Importa a la copia LOCAL las 5 fichas españolas aprobadas (tools/content/en/fichas-es-aprobadas.json): publicadas y aprobadas,
// como en producción. Los relacionados guardados con IDs de producción se remapean por slug; los que no existen aquí se omiten.
$d   = json_decode( file_get_contents( $args[0] ), true );
$map = [];
foreach ( $d['fichas'] as $f ) {
	$old = get_posts( [ 'post_type' => [ 'condicion', 'tratamiento' ], 'post_name__in' => [ $f['slug'] ], 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ] );
	foreach ( $old as $o ) {
		wp_delete_post( $o, true ); // solo datos locales
	}
	$id = wp_insert_post( [ 'post_type' => $f['post_type'], 'post_status' => 'draft', 'post_title' => $f['post_title'], 'post_name' => $f['slug'], 'post_content' => wp_slash( $f['post_content'] ), 'post_excerpt' => wp_slash( $f['post_excerpt'] ) ], true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $f['slug'] . ': ' . $id->get_error_message() );
	}
	$map[ (int) $f['ID'] ] = $id;
	foreach ( $f['terms'] as $tax => $slugs ) {
		wp_set_object_terms( $id, $slugs, $tax );
	}
	foreach ( $f['meta'] as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	update_post_meta( $id, '_di_approved_at', $f['_di_approved_at'] );
}
foreach ( $d['fichas'] as $f ) {
	$id = $map[ (int) $f['ID'] ];
	foreach ( [ 'di_relacionados', 'di_tratamientos_relacionados', 'di_que_resuelve' ] as $k ) {
		if ( isset( $f['meta'][ $k ] ) && is_array( $f['meta'][ $k ] ) ) {
			update_post_meta( $id, $k, array_values( array_filter( array_map( static fn( $x ) => $map[ (int) $x ] ?? 0, $f['meta'][ $k ] ) ) ) );
		}
	}
	$r = wp_update_post( [ 'ID' => $id, 'post_status' => 'publish' ], true );
	echo $f['slug'], ' → ', $id, ' ', is_wp_error( $r ) ? 'ERROR ' . $r->get_error_message() : get_post_status( $id ), ' · ', \DraIrina\Core\Workflow\MedicalReview::state( $id ), "\n";
}
