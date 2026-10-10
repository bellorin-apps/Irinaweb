<?php
/**
 * Bloque narrativo centrado (ancho de lectura). $args: eyebrow, title, lead, text, draft (bool), image_id (int, opcional), image_alt.
 * Con imagen: el texto no se mueve (misma columna de lectura); la foto entra con animación al hacer scroll en el margen derecho
 * (image_pos=left para el izquierdo) en pantallas anchas y debajo del texto en el resto; se mezcla en multiplicar para que su fondo de
 * estudio se funda con el crema (propietario, 2026-10-10: endoscopio junto a «Escucharte»).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a   = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'   => '',
		'title'     => '',
		'lead'      => '',
		'text'      => '',
		'draft'     => false,
		'image_id'  => 0,
		'image_alt' => '',
		'image_pos' => 'right',
	]
);
$irina_img = (int) $irina_a['image_id'] > 0 ? irina_image_original(
	(int) $irina_a['image_id'],
	[
		'alt'      => (string) $irina_a['image_alt'],
		'loading'  => 'lazy',
		'decoding' => 'async',
		'sizes'    => '(max-width: 1199px) 60vw, 22vw',
	]
) : '';
?>
<section class="di-section<?php echo '' !== $irina_img ? ' di-narrative--aside di-narrative--img-' . esc_attr( 'left' === $irina_a['image_pos'] ? 'left' : 'right' ) : ''; ?>">
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
					'br' => [ 'class' => [] ],
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
	<?php if ( '' !== $irina_img ) : ?>
	<div class="di-narrative__aside di-reveal" aria-hidden="false"><?php echo $irina_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
</section>
