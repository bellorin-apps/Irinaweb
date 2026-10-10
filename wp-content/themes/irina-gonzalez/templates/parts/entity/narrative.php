<?php
/**
 * Bloque narrativo centrado (ancho de lectura). $args: eyebrow, title, lead, text, draft (bool).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => '',
		'lead'    => '',
		'text'    => '',
		'draft'   => false,
	]
);
?>
<section class="di-section">
	<div class="di-container--narrow">
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
					'b'  => [],
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
		if ( '' !== $irina_a['text'] ) :
			?>
			<div class="di-prose di-reveal di-reveal--d3"><?php echo wp_kses_post( wpautop( $irina_a['text'] ) ); ?></div><?php endif; ?>
		<?php
		if ( $irina_a['draft'] ) :
			?>
			<p class="di-draft di-reveal"><?php esc_html_e( 'Borrador: pendiente de revisión de la Dra.', 'irina-gonzalez' ); ?></p><?php endif; ?>
	</div>
</section>
