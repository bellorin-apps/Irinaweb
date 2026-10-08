<?php
/**
 * Home · bloque Sueño en registro claro. $args: eyebrow, title, lead, link, link_label, steps (array de strings).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'    => '',
		'title'      => '',
		'lead'       => '',
		'link'       => home_url( '/sueno/' ),
		'link_label' => __( 'Explorar la ruta de tratamiento', 'irina-gonzalez' ),
		'steps'      => [],
	]
);
?>
<section class="di-section di-sleep-light">
	<div class="di-container di-sleep-light__grid">
		<div>
			<?php
			if ( '' !== $irina_a['eyebrow'] ) :
				?>
				<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p><?php endif; ?>
			<?php
			if ( '' !== $irina_a['title'] ) :
				?>
				<h2 class="di-reveal di-reveal--d1">
				<?php
				echo wp_kses(
					$irina_a['title'],
					[
						'em' => [],
						'br' => [],
					]
				);
				?>
				</h2><?php endif; ?>
			<?php
			if ( '' !== $irina_a['lead'] ) :
				?>
				<p class="di-lead di-reveal di-reveal--d2"><?php echo esc_html( $irina_a['lead'] ); ?></p><?php endif; ?>
			<?php
			if ( '' !== $irina_a['link'] ) :
				?>
				<a class="di-btn di-btn--primary di-reveal di-reveal--d3" href="<?php echo esc_url( $irina_a['link'] ); ?>"><?php echo esc_html( $irina_a['link_label'] ); ?><?php echo irina_icon( 'arrow-right', 'di-icon di-icon--arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><?php endif; ?>
		</div>
		<?php if ( $irina_a['steps'] ) : ?>
			<ol class="di-path di-reveal di-reveal--d2">
			<?php
			$irina_n = 0; foreach ( (array) $irina_a['steps'] as $irina_s ) :
				?>
				<li><b><?php echo esc_html( sprintf( '%02d', ++$irina_n ) ); ?></b><span><?php echo esc_html( (string) $irina_s ); ?></span></li><?php endforeach; ?></ol>
		<?php endif; ?>
	</div>
</section>
