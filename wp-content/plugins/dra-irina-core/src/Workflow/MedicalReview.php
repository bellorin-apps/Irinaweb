<?php
/**
 * Workflow de aprobación médica (MASTER_PROMPT §109):
 * DRAFT → TECHNICAL_REVIEW → MEDICAL_REVIEW_REQUIRED → MEDICALLY_APPROVED → READY → PUBLISHED.
 * Solo el rol revisor_medico puede asignar "medically_approved". Sin ese estado no se publica contenido clínico.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Workflow;

use DraIrina\Core\Taxonomies\EstadoMedico;

final class MedicalReview {

	public const ROLE     = 'revisor_medico';
	public const CAP      = 'approve_medical_content';
	public const CLINICAL = [ 'condicion', 'tratamiento', 'recurso' ];

	public function register(): void {
		add_action( 'init', [ self::class, 'map_capabilities' ], 5 );
		add_action( 'add_meta_boxes', [ $this, 'meta_box' ] );
		add_action( 'save_post', [ $this, 'save_state' ], 10, 2 );
		add_filter( 'wp_insert_post_data', [ $this, 'block_unapproved_publish' ], 10, 2 );
		add_action( 'admin_notices', [ $this, 'notice' ] );
	}

	public static function ensure_role(): void {
		if ( ! get_role( self::ROLE ) ) {
			add_role( self::ROLE, __( 'Revisor médico', 'dra-irina-core' ), [ 'read' => true ] );
		}
		$role = get_role( self::ROLE );
		if ( $role ) {
			$role->add_cap( self::CAP );
			foreach ( [
				'condicion'   => 'condiciones',
				'tratamiento' => 'tratamientos',
				'recurso'     => 'recursos',
			] as $s => $p ) {
				foreach ( [ "edit_$s", "edit_{$p}", "edit_others_{$p}", "edit_published_{$p}", "read_$s", "read_private_{$p}" ] as $cap ) {
					$role->add_cap( $cap );
				}
			}
		}
	}

	/** Da a administradores y editores las capacidades de los CPT (sin la de aprobación médica para editores). */
	public static function map_capabilities(): void {
		$types = [
			'condicion'   => 'condiciones',
			'tratamiento' => 'tratamientos',
			'recurso'     => 'recursos',
			'credencial'  => 'credenciales',
		];
		foreach ( [ 'administrator', 'editor' ] as $role_name ) {
			$role = get_role( $role_name );
			if ( ! $role ) {
				continue;
			}
			foreach ( $types as $s => $p ) {
				foreach ( [ "edit_$s", "read_$s", "delete_$s", "edit_{$p}", "edit_others_{$p}", "publish_{$p}", "read_private_{$p}", "delete_{$p}", "delete_private_{$p}", "delete_published_{$p}", "delete_others_{$p}", "edit_private_{$p}", "edit_published_{$p}" ] as $cap ) {
					$role->add_cap( $cap );
				}
			}
		}
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			// El administrador técnico NO aprueba contenido médico (§109). Se retira explícitamente por si existiera.
			$admin->remove_cap( self::CAP );
		}
	}

	public static function state( int $post_id ): string {
		$terms = wp_get_object_terms( $post_id, EstadoMedico::SLUG, [ 'fields' => 'slugs' ] );
		return is_array( $terms ) && $terms ? (string) $terms[0] : EstadoMedico::DRAFT;
	}

	public static function is_publishable( int $post_id ): bool {
		return in_array( self::state( $post_id ), EstadoMedico::PUBLISHABLE, true );
	}

	public function meta_box(): void {
		foreach ( self::CLINICAL as $type ) {
			add_meta_box( 'di-medical-review', __( 'Revisión médica', 'dra-irina-core' ), [ $this, 'render_box' ], $type, 'side', 'high' );
		}
	}

	public function render_box( \WP_Post $post ): void {
		$current  = self::state( $post->ID );
		$can_appr = current_user_can( self::CAP );
		$terms    = get_terms(
			[
				'taxonomy'   => EstadoMedico::SLUG,
				'hide_empty' => false,
			]
		);
		wp_nonce_field( 'di_medical_review', 'di_medical_review_nonce' );
		echo '<p>' . esc_html__( 'Solo la revisora médica puede marcar "Aprobado médicamente". Sin aprobación no se puede publicar.', 'dra-irina-core' ) . '</p>';
		foreach ( (array) $terms as $term ) {
			if ( ! $term instanceof \WP_Term ) {
				continue;
			}
			$locked   = in_array( $term->slug, EstadoMedico::PUBLISHABLE, true ) && ! $can_appr;
			$disabled = $locked ? ' disabled' : '';
			printf(
				'<label style="display:block;margin:4px 0"><input type="radio" name="di_estado_medico" value="%1$s"%2$s%3$s /> %4$s%5$s</label>',
				esc_attr( $term->slug ),
				checked( $current, $term->slug, false ),
				esc_attr( $disabled ),
				esc_html( $term->name ),
				$locked ? ' <span class="dashicons dashicons-lock" aria-hidden="true"></span>' : ''
			);
		}
		$approved_by = (int) get_post_meta( $post->ID, '_di_approved_by', true );
		$approved_at = (string) get_post_meta( $post->ID, '_di_approved_at', true );
		if ( $approved_by && $approved_at ) {
			$user = get_userdata( $approved_by );
			printf( '<p><small>%s %s · %s</small></p>', esc_html__( 'Aprobado por', 'dra-irina-core' ), esc_html( $user ? $user->display_name : '#' . $approved_by ), esc_html( $approved_at ) );
		}
	}

	public function save_state( int $post_id, \WP_Post $post ): void {
		if ( ! in_array( $post->post_type, self::CLINICAL, true ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['di_medical_review_nonce'] ) || ! wp_verify_nonce( sanitize_key( (string) wp_unslash( $_POST['di_medical_review_nonce'] ) ), 'di_medical_review' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) || ! isset( $_POST['di_estado_medico'] ) ) {
			return;
		}
		$new = sanitize_key( (string) wp_unslash( $_POST['di_estado_medico'] ) );
		if ( in_array( $new, EstadoMedico::PUBLISHABLE, true ) && ! current_user_can( self::CAP ) ) {
			return; // Un no-revisor no puede elevar a aprobado.
		}
		$old = self::state( $post_id );
		wp_set_object_terms( $post_id, $new, EstadoMedico::SLUG, false );
		if ( EstadoMedico::MEDICALLY_APPROVED === $new && $old !== $new ) {
			update_post_meta( $post_id, '_di_approved_by', get_current_user_id() );
			update_post_meta( $post_id, '_di_approved_at', current_time( 'mysql' ) );
		}
	}

	/**
	 * Bloquea el paso a "publish" si el contenido clínico no está aprobado.
	 *
	 * @param array $data    Datos a insertar.
	 * @param array $postarr Datos originales.
	 */
	public function block_unapproved_publish( array $data, array $postarr ): array {
		if ( ! in_array( $data['post_type'] ?? '', self::CLINICAL, true ) || 'publish' !== ( $data['post_status'] ?? '' ) ) {
			return $data;
		}
		$post_id = (int) ( $postarr['ID'] ?? 0 );
		$state   = $post_id ? self::state( $post_id ) : EstadoMedico::DRAFT;
		// Si el formulario trae un estado nuevo válido y el usuario puede aprobar, se respeta.
		if ( isset( $_POST['di_estado_medico'], $_POST['di_medical_review_nonce'] ) && wp_verify_nonce( sanitize_key( (string) wp_unslash( $_POST['di_medical_review_nonce'] ) ), 'di_medical_review' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$posted = sanitize_key( (string) wp_unslash( $_POST['di_estado_medico'] ) );
			if ( in_array( $posted, EstadoMedico::PUBLISHABLE, true ) && current_user_can( self::CAP ) ) {
				$state = $posted;
			}
		}
		if ( ! in_array( $state, EstadoMedico::PUBLISHABLE, true ) ) {
			$data['post_status'] = 'pending';
			set_transient( 'di_blocked_publish_' . get_current_user_id(), 1, 60 );
		}
		return $data;
	}

	public function notice(): void {
		if ( get_transient( 'di_blocked_publish_' . get_current_user_id() ) ) {
			delete_transient( 'di_blocked_publish_' . get_current_user_id() );
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Este contenido médico se guardó como "Pendiente": requiere aprobación de la revisora médica antes de publicarse.', 'dra-irina-core' ) . '</p></div>';
		}
	}
}
