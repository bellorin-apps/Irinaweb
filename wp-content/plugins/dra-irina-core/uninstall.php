<?php
/**
 * Desinstalación: conserva todo el contenido médico (padecimientos, tratamientos, credenciales, ajustes).
 * Solo limpia transitorios propios. Borrar datos clínicos es una decisión del propietario, nunca automática.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", $wpdb->esc_like( '_transient_di_' ) . '%', $wpdb->esc_like( '_transient_timeout_di_' ) . '%' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
