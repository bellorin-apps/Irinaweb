<?php
/**
 * Bloque narrativo centrado (ancho de lectura). $args: eyebrow, title, lead, text, draft (bool), image_id (int, opcional), image_alt.
 * Con imagen (propuesta del propietario, 2026-10-10, «Escucharte» + endoscopio): el texto va en una tarjeta de cristal blanco
 * desplazada a la derecha y la foto, grande, asoma por su lado izquierdo saliendo de debajo de la tarjeta a medida que se baja
 * (main.js fija --endo-p 0→1 según el scroll). La foto va en multiplicar y con desvanecido para fundir su fondo de estudio con el
 * crema. image_pos=left invierte los lados.
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
		'sizes'    => '(max-width: 999px) 80vw, 52vw',
	]
) : '';
?>
<section class="di-section<?php echo '' !== $irina_img ? ' di-narrative--card di-narrative--img-' . esc_attr( 'left' === $irina_a['image_pos'] ? 'left' : 'right' ) : ''; ?>">
	<?php if ( '' !== $irina_img ) : ?>
	<div class="di-container di-narrative__wrap">
		<div class="di-narrative__aside"><?php echo $irina_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="di-narrative__card di-reveal">
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
