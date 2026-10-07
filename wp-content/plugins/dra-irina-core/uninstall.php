<?php
/**
 * Desinstalación: conserva todo el contenido médico (padecimientos, tratamientos, credenciales, ajustes).
 * Solo limpia transitorios propios. Borrar datos clínicos es una decisión del propietario, nunca automática.
 *
 * @package DraIrina\Core
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_di_%' OR option_name LIKE '_transient_timeout_di_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
