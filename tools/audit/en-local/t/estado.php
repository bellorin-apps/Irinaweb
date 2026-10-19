<?php
// Instantánea de las entradas inglesas y sus parejas: estado, aprobación de página/ficha y fecha de modificación (JSON ordenado).
use DraIrina\Core\I18n\Language;
use DraIrina\Core\Workflow\MedicalReview;
$out = [];
$ids = get_posts( [ 'post_type' => [ 'page', 'condicion', 'tratamiento' ], 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => Language::META, 'meta_value' => 'en' ] );
$root = Language::root_id();
if ( $root ) {
	$ids = array_merge( $ids, [ $root ], get_posts( [ 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'post_parent' => $root ] ) );
}
$ids = array_values( array_unique( array_map( 'intval', $ids ) ) );
sort( $ids );
foreach ( $ids as $id ) {
	$p          = get_post( $id );
	$out[ $id ] = [
		'slug'     => $p->post_name,
		'type'     => $p->post_type,
		'status'   => $p->post_status,
		'modified' => $p->post_modified_gmt,
		'page_ok'  => (string) get_post_meta( $id, '_di_page_approved_at', true ),
		'medico'   => 'page' === $p->post_type ? '' : MedicalReview::state( $id ),
		'es'       => Language::translation( $id, 'es' ),
		'es_status'=> get_post_status( (int) Language::translation( $id, 'es' ) ),
	];
}
echo wp_json_encode( $out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), "\n";
