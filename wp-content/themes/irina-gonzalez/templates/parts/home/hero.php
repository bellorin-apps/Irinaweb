<?php
/**
 * Home · hero a sangre. $args: eyebrow, title, lead, image_id, trust (array de strings), secondary_label, secondary_url.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a   = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'         => __( 'Tu otorrino|en Monterrey', 'irina-gonzalez' ), // «|» = salto de línea solo en móvil.
		'title'           => __( 'Respirar bien,<br><b>dormir bien,</b><br><em>oír bien.</em>', 'irina-gonzalez' ),
		'lead'            => '',
		'lead_mobile'     => '',
		'image_id'        => 0,
		'trust'           => [],
		'secondary_label' => __( 'Conocer a la Dra. Irina', 'irina-gonzalez' ),
		'secondary_url'   => home_url( '/dra-irina-gonzalez-saez/' ),
		'secondary_short' => __( 'La Dra. Irina', 'irina-gonzalez' ),
	]
);
$irina_cta = irina_whatsapp_button( '', '', 'di-btn di-btn--whatsapp', __( 'WhatsApp', 'irina-gonzalez' ) );
if ( '' !== $irina_a['secondary_label'] && '' !== $irina_a['secondary_url'] ) {
	$irina_cta .= sprintf( '<a class="di-btn di-btn--ghost" href="%s"><span class="di-btn__long">%s</span><span class="di-btn__short">%s</span>%s</a>', esc_url( $irina_a['secondary_url'] ), esc_html( $irina_a['secondary_label'] ), esc_html( $irina_a['secondary_short'] ?? __( 'La Dra. Irina', 'irina-gonzalez' ) ), irina_icon( 'arrow-right', 'di-icon di-icon--arrow' ) );
}
?>
<section class="di-bleed">
	<div class="di-bleed__bg di-bleed__bg--warm"><?php echo $irina_a['image_id'] ? irina_hero_image( (int) $irina_a['image_id'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapa su salida. ?></div>
	<div class="di-bleed__shade"></div>
	<div class="di-container">
		<p class="di-eyebrow"><span class="di-eyebrow__t"><?php echo str_replace( '|', '<span class="di-brm"></span> ', esc_html( $irina_a['eyebrow'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado antes del reemplazo. ?></span></p>
		<h1>
		<?php
		echo wp_kses(
			$irina_a['title'],
			[
				'em' => [],
				'b'  => [],
				'br' => [],
			]
		);
		?>
		</h1>
		<?php
		if ( '' !== $irina_a['lead'] ) :
			?>
			<p class="di-lead di-lead--desktop"><?php echo esc_html( $irina_a['lead'] ); ?></p><?php endif; ?>
		<?php
		if ( '' !== $irina_a['lead_mobile'] ) :
			?>
			<p class="di-lead di-lead--mobile"><?php echo esc_html( $irina_a['lead_mobile'] ); ?></p><?php endif; ?>
		<div class="di-hero-cta"><?php echo $irina_cta; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php if ( $irina_a['trust'] ) : ?>
			<ul class="di-hero-trust">
			<?php
			foreach ( (array) $irina_a['trust'] as $irina_t ) :
				?>
				<li><?php echo irina_icon( 'check-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( (string) $irina_t ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
	</div>
	<div class="di-scroll-hint" aria-hidden="true"></div>
</section>
<?php if ( ! empty( $irina_a['ticker'] ) ) : ?>
	<div class="di-ticker" aria-hidden="true"><div class="di-ticker__track">
	<?php
	$irina_items = (array) $irina_a['ticker']; foreach ( array_merge( $irina_items, $irina_items ) as $irina_t ) :
		?>
		<span><?php echo esc_html( (string) $irina_t ); ?></span><?php endforeach; ?></div></div>
<?php endif; ?>
