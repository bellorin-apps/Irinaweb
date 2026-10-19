<?php
// SOLO COPIA LOCAL: aprobaciones SINTÉTICAS de las 8 páginas (como la guía: _di_page_approved_at/_by) y de las 5 fichas
// (como la app: estado medically_approved + _di_approved_at/_by). Ejecutar con --user=revisora-local.
use DraIrina\Core\I18n\Language;
$root  = Language::root_id();
$slugs = [ 'dr-irina-gonzalez-saez', 'ent', 'sleep', 'first-visit', 'contact', 'thank-you', 'faq' ];
$ids   = [ $root ];
foreach ( $slugs as $s ) {
	$ids[] = (int) ( get_page_by_path( 'en/' . $s )->ID ?? 0 );
}
foreach ( $ids as $id ) {
	update_post_meta( $id, '_di_page_approved_at', current_time( 'mysql' ) );
	update_post_meta( $id, '_di_page_approved_by', get_current_user_id() );
}
$f = get_posts( [ 'post_type' => [ 'condicion', 'tratamiento' ], 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'meta_key' => Language::META, 'meta_value' => 'en' ] );
foreach ( $f as $id ) {
	wp_set_object_terms( $id, 'medically_approved', 'estado_medico' );
	update_post_meta( $id, '_di_approved_by', get_current_user_id() );
	update_post_meta( $id, '_di_approved_at', current_time( 'mysql' ) );
}
echo count( array_filter( $ids ) ), ' páginas y ', count( $f ), " fichas aprobadas (sintético, local)\n";
