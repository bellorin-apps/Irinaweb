<?php
/**
 * Plugin Name:       Dra. Irina Core
 * Plugin URI:        https://www.drairinagonzalez.com/
 * Description:       Funcionalidad y datos estructurados del sitio de la Dra. Irina González Sáez: tipos de contenido médico, campos, ajustes del consultorio, workflow de revisión médica y schema.org. Independiente del tema.
 * Version:           0.4.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Bellorin Apps
 * License:           GPL-2.0-or-later
 * Text Domain:       dra-irina-core
 * Domain Path:       /languages
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DRA_IRINA_CORE_VERSION', '0.4.0' );
define( 'DRA_IRINA_CORE_FILE', __FILE__ );
define( 'DRA_IRINA_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'DRA_IRINA_CORE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoloader PSR-4 mínimo para el namespace DraIrina\Core.
 */
spl_autoload_register(
	static function ( string $class_name ): void {
		$prefix = 'DraIrina\\Core\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$relative = str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) );
		$file     = DRA_IRINA_CORE_DIR . 'src/' . $relative . '.php';
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

add_action(
	'plugins_loaded',
	static function (): void {
		\DraIrina\Core\Plugin::instance()->boot();
	}
);

register_activation_hook(
	__FILE__,
	static function (): void {
		\DraIrina\Core\Plugin::instance()->activate();
	}
);

register_deactivation_hook(
	__FILE__,
	static function (): void {
		flush_rewrite_rules();
	}
);
