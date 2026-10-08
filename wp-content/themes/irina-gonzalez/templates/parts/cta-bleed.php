<?php
/**
 * CTA final a sangre. $args: eyebrow, title, text, message (WhatsApp).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => __( 'Agenda', 'irina-gonzalez' ),
		'title'   => __( '¿Hablamos de lo que te está quitando el descanso?', 'irina-gonzalez' ),
		'text'    => __( 'Escríbenos por WhatsApp y te ayudamos a elegir el tipo de consulta.', 'irina-gonzalez' ),
		'message' => '',
	]
);
?>
<section class="di-cta-bleed">
	<div class="di-cta-bleed__bg"></div>
	<div class="di-container">
		<?php
		if ( '' !== $irina_a['eyebrow'] ) :
			?>
			<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p><?php endif; ?>
		<h2 class="di-reveal di-reveal--d1"><?php echo esc_html( $irina_a['title'] ); ?></h2>
		<?php
		if ( '' !== $irina_a['text'] ) :
			?>
			<p class="di-reveal di-reveal--d2"><?php echo esc_html( $irina_a['text'] ); ?></p><?php endif; ?>
		<?php echo irina_whatsapp_button( '', (string) $irina_a['message'], 'di-btn di-btn--light di-reveal di-reveal--d3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
