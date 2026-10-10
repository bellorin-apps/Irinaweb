<?php
/**
 * Bloque narrativo centrado (ancho de lectura). $args: eyebrow, title, lead, text, draft (bool), image_id (int, opcional), image_alt.
 * Con imagen: dos columnas en escritorio (foto a la derecha por defecto, image_pos=left para la izquierda) y foto arriba en móvil; la foto se mezcla en
 * multiplicar para que un fondo blanco de producto se funda con el crema (propietario, 2026-10-10: endoscopio junto a «Escucharte»).
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
		'sizes'    => '(max-width: 899px) 70vw, 40vw',
	]
) : '';
?>
<section class="di-section<?php echo '' !== $irina_img ? ' di-narrative--split di-narrative--img-' . esc_attr( 'left' === $irina_a['image_pos'] ? 'left' : 'right' ) : ''; ?>">
	<?php if ( '' !== $irina_img ) : ?>
	<div class="di-container di-narrative__grid">
		<div class="di-narrative__media di-reveal"><?php echo $irina_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="di-narrative__body">
	<?php else : ?>
	<div class="di-container--narrow">
	<?php endif; ?>
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
	<?php if ( '' !== $irina_img ) : ?>
		</div>
	<?php endif; ?>
	</div>
</section>
