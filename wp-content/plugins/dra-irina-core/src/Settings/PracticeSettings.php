<?php
/**
 * Fuente única de verdad del consultorio (MASTER_PROMPT §72, §104).
 * Opción: dra_irina_practice. Accesible por dra_irina_practice() y por REST (solo lectura, campos públicos).
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Settings;

final class PracticeSettings {

	public const OPTION = 'dra_irina_practice';

	/**
	 * Campos: clave => [label, tipo, público].
	 *
	 * @return array<string, array{0:string,1:string,2:bool}>
	 */
	public static function fields(): array {
		return [
			'nombre_profesional'  => [ 'Nombre profesional', 'text', true ],
			'especialidad'        => [ 'Especialidad (como se muestra)', 'text', true ],
			'subespecialidad'     => [ 'Subespecialidad (como se muestra)', 'text', true ],
			'centro'              => [ 'Nombre del centro médico', 'text', true ],
			'calle'               => [ 'Calle y número', 'text', true ],
			'interior'            => [ 'Consultorio / piso', 'text', true ],
			'colonia'             => [ 'Colonia', 'text', true ],
			'cp'                  => [ 'Código postal', 'text', true ],
			'ciudad'              => [ 'Ciudad', 'text', true ],
			'estado'              => [ 'Estado', 'text', true ],
			'geo_lat'             => [ 'Latitud', 'text', true ],
			'geo_lng'             => [ 'Longitud', 'text', true ],
			'maps_url'            => [ 'Enlace de Google Maps', 'url', true ],
			'telefono'            => [ 'Teléfono del consultorio (E.164, ej. +528115695744)', 'text', true ],
			'whatsapp'            => [ 'WhatsApp (E.164, ej. +528123685381)', 'text', true ],
			'whatsapp_mensaje'    => [ 'Mensaje prellenado de WhatsApp', 'textarea', true ],
			'email'               => [ 'Correo', 'email', true ],
			'horario'             => [ 'Horario (una línea por día: "Lunes|09:00-14:00,16:00-19:00")', 'textarea', true ],
			'instagram'           => [ 'Instagram (URL)', 'url', true ],
			'facebook'            => [ 'Facebook (URL)', 'url', true ],
			'linkedin'            => [ 'LinkedIn (URL)', 'url', true ],
			'tiktok'              => [ 'TikTok (URL)', 'url', true ],
			'doctoralia_url'      => [ 'Perfil de Doctoralia (URL)', 'url', true ],
			'gbp_url'             => [ 'Ficha de Google (URL)', 'url', true ],
			'cedula_medicina'     => [ 'Cédula profesional (medicina)', 'text', false ],
			'cedula_especialidad' => [ 'Cédula de especialidad', 'text', false ],
			'publicar_cedulas'    => [ 'Publicar cédulas en el sitio', 'checkbox', false ],
			'consejo_certificado' => [ 'Certificación de Consejo (texto a mostrar)', 'text', true ],
			'responsable_datos'   => [ 'Responsable de datos personales', 'text', true ],
			'correo_arco'         => [ 'Correo para derechos ARCO', 'email', true ],
		];
	}

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_init', [ $this, 'settings' ] );
		add_action( 'rest_api_init', [ $this, 'rest' ] );
	}

	public function menu(): void {
		add_menu_page(
			__( 'Consultorio', 'dra-irina-core' ),
			__( 'Consultorio', 'dra-irina-core' ),
			'manage_options',
			'dra-irina-practice',
			[ $this, 'render' ],
			'dashicons-location',
			20
		);
	}

	public function settings(): void {
		register_setting(
			'dra_irina_practice_group',
			self::OPTION,
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize' ],
				'default'           => [],
			]
		);
	}

	/**
	 * @param mixed $input Valor crudo.
	 */
	public function sanitize( $input ): array {
		$out = [];
		if ( ! is_array( $input ) ) {
			return $out;
		}
		foreach ( self::fields() as $key => [ , $type ] ) {
			$raw = $input[ $key ] ?? '';
			switch ( $type ) {
				case 'url':
					$out[ $key ] = esc_url_raw( (string) $raw );
					break;
				case 'email':
					$out[ $key ] = sanitize_email( (string) $raw );
					break;
				case 'checkbox':
					$out[ $key ] = ! empty( $raw );
					break;
				case 'textarea':
					$out[ $key ] = sanitize_textarea_field( (string) $raw );
					break;
				default:
					$out[ $key ] = sanitize_text_field( (string) $raw );
			}
		}
		foreach ( [ 'telefono', 'whatsapp' ] as $tel ) {
			$out[ $tel ] = preg_replace( '/[^+\d]/', '', $out[ $tel ] ?? '' ) ?? '';
		}
		return $out;
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$values = self::all();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Datos del consultorio', 'dra-irina-core' ); ?></h1>
			<p><?php esc_html_e( 'Estos datos alimentan header, footer, contacto, CTA, barra móvil y schema.org. Edítalos solo aquí.', 'dra-irina-core' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'dra_irina_practice_group' ); ?>
				<table class="form-table" role="presentation">
					<?php foreach ( self::fields() as $key => [ $label, $type ] ) : ?>
						<tr>
							<th scope="row"><label for="di-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
							<td>
								<?php
								$name = self::OPTION . '[' . $key . ']';
								$val  = $values[ $key ] ?? '';
								?>
								<?php if ( 'textarea' === $type ) : ?>
									<textarea id="di-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="4" class="large-text"><?php echo esc_textarea( (string) $val ); ?></textarea>
								<?php elseif ( 'checkbox' === $type ) : ?>
									<input type="checkbox" id="di-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( (bool) $val ); ?> />
								<?php else : ?>
									<input type="<?php echo esc_attr( 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : 'text' ) ); ?>" id="di-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $val ); ?>" class="regular-text" />
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/** @return array<string, mixed> */
	public static function all(): array {
		$opt = get_option( self::OPTION, [] );
		return is_array( $opt ) ? $opt : [];
	}

	/** @return mixed */
	public static function get( string $key, $fallback = '' ) {
		$all = self::all();
		return $all[ $key ] ?? $fallback;
	}

	/** Campos públicos para REST y schema. */
	public static function public_data(): array {
		$out = [];
		foreach ( self::fields() as $key => [ , , $public ] ) {
			if ( $public ) {
				$out[ $key ] = self::get( $key );
			}
		}
		if ( self::get( 'publicar_cedulas' ) ) {
			$out['cedula_medicina']     = self::get( 'cedula_medicina' );
			$out['cedula_especialidad'] = self::get( 'cedula_especialidad' );
		}
		return $out;
	}

	public static function whatsapp_url( string $message = '' ): string {
		$number = preg_replace( '/\D/', '', (string) self::get( 'whatsapp' ) ) ?? '';
		$text   = '' !== $message ? $message : (string) self::get( 'whatsapp_mensaje' );
		if ( '' === $number ) {
			return '';
		}
		return 'https://wa.me/' . $number . ( '' !== $text ? '?text=' . rawurlencode( $text ) : '' );
	}

	public function rest(): void {
		register_rest_route(
			'dra-irina/v1',
			'/practice',
			[
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => static fn() => rest_ensure_response( self::public_data() ),
			]
		);
	}
}
