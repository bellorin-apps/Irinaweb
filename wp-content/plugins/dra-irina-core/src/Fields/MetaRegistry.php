<?php
/**
 * Registro de campos (post meta) con schema REST. Fuente del content model (ARCHITECTURE.md §4).
 * La UI de edición (meta boxes) se implementa en MetaBoxes.php en la Fase 5.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Fields;

final class MetaRegistry {

	public function register(): void {
		add_action( 'init', [ $this, 'register_meta' ] );
		add_filter( 'post_type_link', [ $this, 'area_permalink' ], 10, 2 );
		add_action( 'init', [ $this, 'area_rewrites' ] );
	}

	/**
	 * Definición declarativa de campos por tipo.
	 *
	 * @return array<string, array<string, array{type:string, single?:bool, items?:string, props?:array, default?:mixed}>>
	 */
	public static function definitions(): array {
		$list     = [
			'type'  => 'array',
			'items' => 'string',
		];
		$rich     = [ 'type' => 'string' ];
		$faq      = [
			'type'  => 'array',
			'items' => 'object',
			'props' => [
				'pregunta'  => [ 'type' => 'string' ],
				'respuesta' => [ 'type' => 'string' ],
			],
		];
		$fuente   = [
			'type'  => 'array',
			'items' => 'object',
			'props' => [
				'titulo' => [ 'type' => 'string' ],
				'autor'  => [ 'type' => 'string' ],
				'anio'   => [ 'type' => 'string' ],
				'url'    => [
					'type'   => 'string',
					'format' => 'uri',
				],
				'tipo'   => [
					'type' => 'string',
					'enum' => [ 'guia', 'sociedad', 'articulo', 'organismo', 'libro', 'otro' ],
				],
			],
		];
		$review   = [
			'revisor_id'            => [ 'type' => 'integer' ],
			'fecha_revision_medica' => [
				'type'   => 'string',
				'format' => 'date',
			],
			'proxima_revision'      => [
				'type'   => 'string',
				'format' => 'date',
			],
			'fuentes'               => $fuente,
			'faq'                   => $faq,
		];
		$clinical = [
			'resumen_paciente' => [ 'type' => 'string' ],
			'oferta'           => [
				'type'    => 'string',
				'enum'    => [ 'ofrece', 'refiere', 'no' ],
				'default' => 'ofrece',
			],
			'enfoque_dra'      => $rich,
			'relacionados'     => [
				'type'  => 'array',
				'items' => 'integer',
			],
		];

		return [
			'condicion'   => array_merge(
				$clinical,
				$review,
				[
					'sintomas'                  => $list,
					'causas'                    => $list,
					'cuando_consultar'          => $list,
					'diagnostico'               => $rich,
					'tratamientos_relacionados' => [
						'type'  => 'array',
						'items' => 'integer',
					],
				]
			),
			'tratamiento' => array_merge(
				$clinical,
				$review,
				[
					'tipo'                   => [
						'type' => 'string',
						'enum' => [ 'consulta', 'procedimiento', 'cirugia', 'estudio', 'terapia' ],
					],
					'que_resuelve'           => [
						'type'  => 'array',
						'items' => 'integer',
					],
					'candidatos'             => $list,
					'como_se_realiza'        => $rich,
					'recuperacion'           => $rich,
					'riesgos_y_alternativas' => $rich,
					'estudio_previo'         => $rich,
				]
			),
			'recurso'     => $review,
			'credencial'  => [
				'tipo'        => [
					'type' => 'string',
					'enum' => [ 'formacion', 'especialidad', 'certificacion', 'membresia', 'publicacion', 'curso', 'experiencia', 'conferencia', 'trabajo' ],
				],
				'institucion' => [ 'type' => 'string' ],
				'lugar'       => [ 'type' => 'string' ],
				'anio'        => [ 'type' => 'string' ],
				'anio_fin'    => [ 'type' => 'string' ],
				'url'         => [
					'type'   => 'string',
					'format' => 'uri',
				],
				'verificado'  => [
					'type'    => 'boolean',
					'default' => false,
				],
				'mostrar'     => [
					'type'    => 'boolean',
					'default' => false,
				],
			],
		];
	}

	public function register_meta(): void {
		foreach ( self::definitions() as $post_type => $fields ) {
			foreach ( $fields as $key => $def ) {
				register_post_meta(
					$post_type,
					'di_' . $key,
					[
						'type'              => $def['type'],
						'single'            => true,
						'default'           => $def['default'] ?? ( 'array' === $def['type'] ? [] : ( 'boolean' === $def['type'] ? false : ( 'integer' === $def['type'] ? 0 : '' ) ) ),
						'show_in_rest'      => [ 'schema' => self::rest_schema( $def ) ],
						'sanitize_callback' => static fn( $value ) => self::sanitize( $value, $def ),
						'auth_callback'     => static fn( $allowed, $meta_key, $post_id ): bool => current_user_can( 'edit_post', (int) $post_id ),
					]
				);
			}
		}
	}

	/** @param array $def */
	private static function rest_schema( array $def ): array {
		$schema = [ 'type' => $def['type'] ];
		if ( isset( $def['enum'] ) ) {
			$schema['enum'] = $def['enum'];
		}
		if ( isset( $def['format'] ) ) {
			$schema['format'] = $def['format'];
		}
		if ( 'array' === $def['type'] ) {
			if ( 'object' === ( $def['items'] ?? 'string' ) ) {
				$props = [];
				foreach ( $def['props'] as $name => $p ) {
					$props[ $name ] = self::rest_schema( $p );
				}
				$schema['items'] = [
					'type'       => 'object',
					'properties' => $props,
				];
			} else {
				$schema['items'] = [ 'type' => $def['items'] ?? 'string' ];
			}
		}
		return $schema;
	}

	/**
	 * Sanitización recursiva según definición.
	 *
	 * @param mixed $value Valor.
	 * @param array $def   Definición.
	 * @return mixed
	 */
	public static function sanitize( $value, array $def ) {
		switch ( $def['type'] ) {
			case 'boolean':
				return (bool) $value;
			case 'integer':
				return absint( $value );
			case 'array':
				if ( ! is_array( $value ) ) {
					return [];
				}
				$items = $def['items'] ?? 'string';
				$out   = [];
				foreach ( $value as $item ) {
					if ( 'object' === $items ) {
						$row = [];
						foreach ( $def['props'] as $name => $p ) {
							$row[ $name ] = self::sanitize( $item[ $name ] ?? '', $p );
						}
						$out[] = $row;
					} elseif ( 'integer' === $items ) {
						$out[] = absint( $item );
					} else {
						$out[] = sanitize_text_field( (string) $item );
					}
				}
				return $out;
			case 'string':
			default:
				if ( isset( $def['enum'] ) ) {
					return in_array( $value, $def['enum'], true ) ? $value : ( $def['default'] ?? '' );
				}
				if ( ( $def['format'] ?? '' ) === 'uri' ) {
					return esc_url_raw( (string) $value );
				}
				if ( ( $def['format'] ?? '' ) === 'date' ) {
					$v = sanitize_text_field( (string) $value );
					if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $v, $date ) ) {
						return '';
					}
					return checkdate( (int) $date[2], (int) $date[3], (int) $date[1] ) ? $v : '';
				}
				return wp_kses_post( (string) $value );
		}
	}

	/**
	 * Las entradas del área "sueno" viven bajo /sueno/{slug}/ en lugar de /padecimientos|/tratamientos.
	 */
	public function area_permalink( string $permalink, \WP_Post $post ): string {
		if ( ! in_array( $post->post_type, [ 'condicion', 'tratamiento' ], true ) ) {
			return $permalink;
		}
		if ( has_term( 'sueno', 'area', $post ) ) {
			return home_url( user_trailingslashit( 'sueno/' . $post->post_name ) );
		}
		return $permalink;
	}

	public function area_rewrites(): void {
		add_rewrite_rule( '^sueno/([^/]+)/?$', 'index.php?di_sueno_slug=$matches[1]', 'top' );
		add_filter(
			'query_vars',
			static function ( array $vars ): array {
				$vars[] = 'di_sueno_slug';
				return $vars;
			}
		);
		add_action(
			'pre_get_posts',
			static function ( \WP_Query $q ): void {
				if ( ! $q->is_main_query() ) {
					return;
				}
				$slug = $q->get( 'di_sueno_slug' );
				if ( ! $slug ) {
					return;
				}
				$q->set( 'post_type', [ 'condicion', 'tratamiento' ] );
				$q->set( 'name', sanitize_title( $slug ) );
				$q->set(
					'tax_query',
					[
						[
							'taxonomy' => 'area',
							'field'    => 'slug',
							'terms'    => 'sueno',
						],
					]
				); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$q->is_single   = true;
				$q->is_singular = true;
				$q->is_home     = false;
			}
		);
	}
}
