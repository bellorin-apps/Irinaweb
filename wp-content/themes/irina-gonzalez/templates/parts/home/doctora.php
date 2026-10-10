<?php
/**
 * Home · bloque de la Dra. $args: eyebrow, quote, text, creds (array), image_id, bg_id, bg_tone (dark|light), link, link_label.
 * Con bg_id la sección lleva una fotografía de fondo; el retrato (image_id) va sin tarjeta, pegado al borde inferior (propietario, 2026-10-10).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'    => '',
		'quote'      => '',
		'text'       => '',
		'creds'      => [],
		'image_id'   => 0,
		'bg_id'      => 0,
		'bg_tone'    => 'dark',
		'link'       => home_url( '/dra-irina-gonzalez-saez/' ),
		'link_label' => __( 'Conocer a la Dra. Irina', 'irina-gonzalez' ),
	]
);
?>
<?php $irina_bg = (int) $irina_a['bg_id']; ?>
<section class="di-section di-section--doctor<?php echo $irina_bg ? ' di-section--doctor-bg di-section--doctor-' . esc_attr( 'light' === $irina_a['bg_tone'] ? 'light' : 'dark' ) : ' di-section--soft'; ?>">
	<?php if ( $irina_bg ) : ?>
		<div class="di-doctor__bg">
		<?php
		echo wp_get_attachment_image(
			$irina_bg,
			'full',
			false,
			[
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '100vw',
				'alt'      => '',
			]
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapa su salida. 
		?>
									</div>
	<?php endif; ?>
	<div class="di-container di-doctor">
		<div class="di-doctor__portrait di-reveal">
		<?php
		echo $irina_a['image_id'] ? wp_get_attachment_image(
			(int) $irina_a['image_id'],
			'full',
			false,
			[
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(min-width: 900px) 40vw, 100vw',
			]
		) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapa su salida. 
		?>
		</div>
		<div class="di-doctor__text">
			<?php
			if ( '' !== $irina_a['eyebrow'] ) :
				?>
				<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p><?php endif; ?>
			<?php
			if ( '' !== $irina_a['quote'] ) :
				?>
				<p class="di-quote di-reveal di-reveal--d1"><?php echo esc_html( $irina_a['quote'] ); ?></p><?php endif; ?>
			<?php
			if ( '' !== $irina_a['text'] ) :
				?>
				<div class="di-doctor__lead di-reveal di-reveal--d2"><?php echo wp_kses_post( wpautop( $irina_a['text'] ) ); ?></div><?php endif; ?>
			<?php
			if ( $irina_a['creds'] ) :
				?>
				<ul class="di-creds di-reveal di-reveal--d3">
				<?php
				foreach ( (array) $irina_a['creds'] as $irina_c ) :
					?>
				<li><?php echo irina_icon( 'check-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( (string) $irina_c ); ?></li><?php endforeach; ?></ul><?php endif; ?>
			<?php
			if ( '' !== $irina_a['link'] ) :
				?>
				<a class="di-btn di-btn--primary di-reveal di-reveal--d4" href="<?php echo esc_url( $irina_a['link'] ); ?>"><?php echo esc_html( $irina_a['link_label'] ); ?><?php echo irina_icon( 'arrow-right', 'di-icon di-icon--arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><?php endif; ?>
		</div>
	</div>
</section>
