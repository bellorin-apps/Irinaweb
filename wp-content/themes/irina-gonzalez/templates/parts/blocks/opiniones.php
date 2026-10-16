<?php
/**
 * Opiniones de Doctoralia con su widget oficial (D-012, D-091). $args: eyebrow, title, text.
 * No se muestra nada si no está activado en Consultorio (doctoralia_opiniones), si falta el identificador del widget o el perfil.
 * El script de Doctoralia se carga solo tras pulsar el botón (assets/js/main.js, data-di-doctoralia).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_profile = (string) irina_practice( 'doctoralia_url' );
$irina_doctor  = (string) irina_practice( 'doctoralia_widget_id' );
if ( ! irina_practice( 'doctoralia_opiniones' ) || '' === $irina_profile || '' === $irina_doctor ) {
	return;
}
$irina_a  = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => __( 'Opiniones', 'irina-gonzalez' ),
		'title'   => __( 'Lo que dicen <em>sus pacientes</em>', 'irina-gonzalez' ),
		'text'    => '',
	]
);
$irina_id = 'di-opiniones-title';
?>
<section class="di-section di-opiniones" aria-labelledby="<?php echo esc_attr( $irina_id ); ?>">
	<div class="di-container di-container--narrow">
		<?php if ( '' !== $irina_a['eyebrow'] ) : ?>
			<p class="di-eyebrow"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h2 id="<?php echo esc_attr( $irina_id ); ?>">
		<?php
		echo wp_kses(
			$irina_a['title'],
			[
				'em' => [],
				'b'  => [],
				'br' => [ 'class' => [] ],
			]
		);
		?>
		</h2>
		<?php if ( '' !== $irina_a['text'] ) : ?>
			<p class="di-lead"><?php echo esc_html( $irina_a['text'] ); ?></p>
		<?php endif; ?>
		<div class="di-opiniones__widget" data-di-doctoralia role="region" aria-label="<?php esc_attr_e( 'Opiniones de Doctoralia', 'irina-gonzalez' ); ?>" tabindex="0">
			<p><?php esc_html_e( 'Al cargar las opiniones conectarás con Doctoralia, que puede utilizar sus propias cookies.', 'irina-gonzalez' ); ?></p>
			<button type="button" class="di-btn" data-di-doctoralia-load hidden><?php esc_html_e( 'Cargar opiniones de Doctoralia', 'irina-gonzalez' ); ?></button>
			<p role="status" data-di-doctoralia-status></p>
			<a id="zl-url" class="zl-url" href="<?php echo esc_url( $irina_profile ); ?>" rel="nofollow noopener" target="_blank" data-zlw-doctor="<?php echo esc_attr( $irina_doctor ); ?>" data-zlw-type="big" data-zlw-opinion="true" data-zlw-hide-branding="true" data-di-event="doctoralia_click"><?php esc_html_e( 'Ver opiniones en Doctoralia', 'irina-gonzalez' ); ?></a>
		</div>
	</div>
</section>
