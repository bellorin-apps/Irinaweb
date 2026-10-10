<?php
/**
 * Formulario de contacto (PLAN 8.4, D-050). $args: eyebrow, title, lead, button.
 * Envía a admin-post.php (acción di_contact del plugin); con JS va por fetch y redirige a /gracias/; sin JS, el servidor redirige.
 * Datos mínimos: nombre, teléfono, motivo; correo y mensaje opcionales; consentimiento obligatorio. No se guarda nada en el sitio.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => __( 'Escríbenos', 'irina-gonzalez' ),
		'title'   => __( '¿Prefieres que <b>te</b> <em>llamemos</em>?', 'irina-gonzalez' ),
		'lead'    => __( 'Déjanos tu nombre y teléfono y te contactamos en horario de consultorio. Para agendar más rápido, WhatsApp.', 'irina-gonzalez' ),
		'button'  => __( 'Enviar solicitud', 'irina-gonzalez' ),
	]
);

$irina_core    = class_exists( '\DraIrina\Core\Contact\Form' );
$irina_action  = $irina_core ? \DraIrina\Core\Contact\Form::action_url() : '';
$irina_motivos = $irina_core ? \DraIrina\Core\Contact\Form::MOTIVOS : [];
$irina_thanks  = $irina_core ? \DraIrina\Core\Contact\Form::thanks_url() : home_url( '/' );
$irina_priv    = get_page_by_path( 'aviso-de-privacidad', OBJECT, 'page' );
$irina_priv_ur = $irina_priv ? (string) get_permalink( $irina_priv ) : home_url( '/aviso-de-privacidad/' );
$irina_error   = isset( $_GET['di_error'] ) ? sanitize_key( wp_unslash( $_GET['di_error'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- solo para mostrar un aviso tras la redirección sin JS.
$irina_errors  = [
	'name'    => __( 'Escribe tu nombre.', 'irina-gonzalez' ),
	'phone'   => __( 'Escribe un teléfono válido de 10 dígitos.', 'irina-gonzalez' ),
	'email'   => __( 'El correo no parece válido. Puedes dejarlo vacío.', 'irina-gonzalez' ),
	'motivo'  => __( 'Elige el motivo de tu consulta.', 'irina-gonzalez' ),
	'consent' => __( 'Necesitamos tu consentimiento para contactarte.', 'irina-gonzalez' ),
	'rate'    => __( 'Hemos recibido varios mensajes desde tu conexión. Espera unos minutos o escríbenos por WhatsApp.', 'irina-gonzalez' ),
	'mail'    => __( 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp y te atendemos enseguida.', 'irina-gonzalez' ),
];
if ( ! $irina_core ) {
	return;
}
?>
<section class="di-section di-section--form" id="di-form">
	<div class="di-container di-form__grid">
		<div class="di-form__intro">
			<?php if ( '' !== $irina_a['eyebrow'] ) : ?>
				<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $irina_a['title'] ) : ?>
				<h2 class="di-reveal di-reveal--d1">
				<?php
				echo wp_kses(
					$irina_a['title'],
					[
						'b'  => [],
						'em' => [],
						'br' => [ 'class' => [] ],
					]
				);
				?>
				</h2>
			<?php endif; ?>
			<?php if ( '' !== $irina_a['lead'] ) : ?>
				<p class="di-lead di-reveal di-reveal--d2"><?php echo esc_html( $irina_a['lead'] ); ?></p>
			<?php endif; ?>
			<?php echo irina_whatsapp_button( '', '', 'di-btn di-btn--whatsapp di-reveal di-reveal--d3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<form class="di-form di-reveal di-reveal--d2" method="post" action="<?php echo esc_url( $irina_action ); ?>" data-thanks="<?php echo esc_url( $irina_thanks ); ?>" novalidate>
			<input type="hidden" name="action" value="di_contact">
			<input type="hidden" name="di_t" value="<?php echo esc_attr( (string) time() ); ?>">
			<p class="di-form__hp" aria-hidden="true"><label>Sitio web<input type="text" name="di_web" tabindex="-1" autocomplete="off"></label></p>
			<div class="di-form__status" role="alert" aria-live="assertive"<?php echo '' === $irina_error ? ' hidden' : ''; ?>>
				<?php echo esc_html( $irina_errors[ $irina_error ] ?? ( '' !== $irina_error ? __( 'Revisa los datos e inténtalo de nuevo.', 'irina-gonzalez' ) : '' ) ); ?>
			</div>
			<div class="di-form__row">
				<label class="di-field"><span class="di-field__label"><?php esc_html_e( 'Nombre', 'irina-gonzalez' ); ?></span><input type="text" name="di_name" required maxlength="80" autocomplete="name"></label>
				<label class="di-field"><span class="di-field__label"><?php esc_html_e( 'Teléfono o WhatsApp', 'irina-gonzalez' ); ?></span><input type="tel" name="di_phone" required inputmode="tel" autocomplete="tel" placeholder="81 0000 0000"></label>
			</div>
			<div class="di-form__row">
				<label class="di-field"><span class="di-field__label"><?php esc_html_e( 'Motivo', 'irina-gonzalez' ); ?></span>
					<select name="di_motivo" required>
						<option value=""><?php esc_html_e( 'Elige una opción', 'irina-gonzalez' ); ?></option>
						<?php foreach ( $irina_motivos as $irina_k => $irina_label ) : ?>
							<option value="<?php echo esc_attr( (string) $irina_k ); ?>"><?php echo esc_html( $irina_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="di-field"><span class="di-field__label"><?php esc_html_e( 'Correo (opcional)', 'irina-gonzalez' ); ?></span><input type="email" name="di_email" maxlength="120" autocomplete="email"></label>
			</div>
			<label class="di-field"><span class="di-field__label"><?php esc_html_e( 'Mensaje (opcional)', 'irina-gonzalez' ); ?></span><textarea name="di_message" rows="3" maxlength="800" placeholder="<?php esc_attr_e( 'Cuéntanos brevemente. No es necesario incluir detalles clínicos: los vemos en consulta.', 'irina-gonzalez' ); ?>"></textarea></label>
			<label class="di-check"><input type="checkbox" name="di_consent" value="1" required><span>
			<?php
			printf(
				/* translators: %s: enlace al aviso de privacidad */
				esc_html__( 'Acepto que se usen mis datos para contactarme y agendar, conforme al %s.', 'irina-gonzalez' ),
				'<a href="' . esc_url( $irina_priv_ur ) . '" target="_blank" rel="noopener">' . esc_html__( 'aviso de privacidad', 'irina-gonzalez' ) . '</a>'
			);
			?>
			</span></label>
			<p class="di-form__note"><?php esc_html_e( 'Datos mínimos: no guardamos tu mensaje en el sitio; llega directo al consultorio. Si es una urgencia, acude a un servicio de urgencias.', 'irina-gonzalez' ); ?></p>
			<button type="submit" class="di-btn di-btn--primary di-form__submit"><span class="di-form__label"><?php echo esc_html( $irina_a['button'] ); ?></span><span class="di-form__sending" hidden><?php esc_html_e( 'Enviando…', 'irina-gonzalez' ); ?></span></button>
		</form>
	</div>
</section>
