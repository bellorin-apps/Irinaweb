<?php
/**
 * Bootstrap del plugin.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core;

use DraIrina\Core\Fields\MetaRegistry;
use DraIrina\Core\Contact\Form as ContactForm;
use DraIrina\Core\Media\WebpUpload;
use DraIrina\Core\Ops\Endpoints as OpsEndpoints;
use DraIrina\Core\Security\Headers as SecurityHeaders;
use DraIrina\Core\PostTypes\Condicion;
use DraIrina\Core\PostTypes\Credencial;
use DraIrina\Core\PostTypes\Recurso;
use DraIrina\Core\PostTypes\Tratamiento;
use DraIrina\Core\Rendering\Elementor\Widgets as ElementorWidgets;
use DraIrina\Core\Rendering\Shortcodes;
use DraIrina\Core\Schema\Graph;
use DraIrina\Core\Settings\PracticeSettings;
use DraIrina\Core\Taxonomies\Area;
use DraIrina\Core\Taxonomies\EstadoMedico;
use DraIrina\Core\Taxonomies\Zona;
use DraIrina\Core\Tracking\Events;
use DraIrina\Core\Workflow\MedicalReview;

/**
 * Punto único de registro de hooks.
 */
final class Plugin {

	private static ?Plugin $instance = null;

	/** @var array<int, object> */
	private array $modules = [];

	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->modules = [
			new Area(),
			new Zona(),
			new EstadoMedico(),
			new Condicion(),
			new Tratamiento(),
			new Recurso(),
			new Credencial(),
			new MetaRegistry(),
			new PracticeSettings(),
			new MedicalReview(),
			new Graph(),
			new Shortcodes(),
			new ElementorWidgets(),
			new Events(),
			new OpsEndpoints(),
			new ContactForm(),
			new SecurityHeaders(),
			new WebpUpload(),
		];
	}

	public function boot(): void {
		load_plugin_textdomain( 'dra-irina-core', false, dirname( plugin_basename( DRA_IRINA_CORE_FILE ) ) . '/languages' );
		foreach ( $this->modules as $module ) {
			if ( method_exists( $module, 'register' ) ) {
				$module->register();
			}
		}
	}

	/**
	 * Activación: registra tipos, crea rol de revisor médico y refresca reglas de reescritura.
	 */
	public function activate(): void {
		$this->boot();
		do_action( 'init' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- se dispara init de forma controlada para registrar CPT antes del flush.
		MedicalReview::ensure_role();
		flush_rewrite_rules();
	}
}
