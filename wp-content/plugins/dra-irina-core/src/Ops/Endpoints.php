<?php
/**
 * Operación remota por REST (D-018): sustituto de WP-CLI para la sesión cloud.
 * Solo administradores (manage_options) autenticados con contraseña de aplicación.
 * Alcance deliberadamente acotado: opciones, reescrituras, caché y purga. Nada de ficheros ni SQL.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Ops;

final class Endpoints {

	public const NS = 'dra-irina/v1';

	/** Opciones que nunca se exponen ni se escriben por aquí. */
	private const DENY = [ 'siteurl', 'home', 'admin_email', 'users_can_register', 'default_role', 'active_plugins', 'template', 'stylesheet', 'db_version' ];

	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'routes' ] );
	}

	private static function admin_only(): bool {
		return current_user_can( 'manage_options' );
	}

	public function routes(): void {
		register_rest_route(
			self::NS,
			'/ops/option/(?P<name>[A-Za-z0-9_\-]+)',
			[
				[
					'methods'             => 'GET',
					'permission_callback' => [ self::class, 'admin_only' ],
					'callback'            => static function ( \WP_REST_Request $r ) {
						$name = (string) $r['name'];
						if ( in_array( $name, self::DENY, true ) || str_contains( $name, 'secret' ) || str_contains( $name, 'password' ) ) {
							return new \WP_Error( 'di_denied', 'Opción no permitida.', [ 'status' => 403 ] );
						}
						return rest_ensure_response( [ 'name' => $name, 'value' => get_option( $name, null ) ] );
					},
				],
				[
					'methods'             => 'POST',
					'permission_callback' => [ self::class, 'admin_only' ],
					'callback'            => static function ( \WP_REST_Request $r ) {
						$name = (string) $r['name'];
						if ( in_array( $name, self::DENY, true ) ) {
							return new \WP_Error( 'di_denied', 'Opción no permitida.', [ 'status' => 403 ] );
						}
						$body = $r->get_json_params();
						if ( ! is_array( $body ) || ! array_key_exists( 'value', $body ) ) {
							return new \WP_Error( 'di_invalid', 'Falta "value".', [ 'status' => 400 ] );
						}
						$value = $body['value'];
						if ( ! empty( $body['merge'] ) && is_array( $value ) ) {
							$current = get_option( $name, [] );
							$value   = array_merge( is_array( $current ) ? $current : [], $value );
						}
						update_option( $name, $value );
						return rest_ensure_response( [ 'name' => $name, 'value' => get_option( $name ) ] );
					},
				],
			]
		);
		register_rest_route(
			self::NS,
			'/ops/flush-rewrite',
			[
				'methods'             => 'POST',
				'permission_callback' => [ self::class, 'admin_only' ],
				'callback'            => static function () {
					flush_rewrite_rules( false );
					return rest_ensure_response( [ 'ok' => true ] );
				},
			]
		);
		register_rest_route(
			self::NS,
			'/ops/purge-cache',
			[
				'methods'             => 'POST',
				'permission_callback' => [ self::class, 'admin_only' ],
				'callback'            => static function () {
					do_action( 'litespeed_purge_all' );
					wp_cache_flush();
					return rest_ensure_response( [ 'ok' => true, 'litespeed' => has_action( 'litespeed_purge_all' ) > 0 ] );
				},
			]
		);
		register_rest_route(
			self::NS,
			'/ops/info',
			[
				'methods'             => 'GET',
				'permission_callback' => [ self::class, 'admin_only' ],
				'callback'            => static function () {
					global $wp_version;
					return rest_ensure_response(
						[
							'wp'       => $wp_version,
							'php'      => PHP_VERSION,
							'theme'    => get_stylesheet(),
							'template' => get_template(),
							'plugins'  => array_values( (array) get_option( 'active_plugins', [] ) ),
							'core'     => DRA_IRINA_CORE_VERSION,
						]
					);
				},
			]
		);
	}
}
