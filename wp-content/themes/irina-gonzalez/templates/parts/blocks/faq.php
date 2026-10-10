<?php
/**
 * Preguntas frecuentes. $args: eyebrow, title, items (array de [pregunta, respuesta]), narrow (bool).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a     = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => '',
		'items'   => [],
		'narrow'  => true,
	]
);
$irina_items = array_filter( (array) $irina_a['items'], static fn( $i ): bool => ! empty( $i['pregunta'] ) );
if ( ! $irina_items ) {
	return;
}
?>
<section class="di-section di-section--tight">
	<div class="<?php echo $irina_a['narrow'] ? 'di-container--narrow' : 'di-container'; ?>">
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
		<div class="di-faq di-reveal di-reveal--d2" style="margin-top:var(--space-xl)">
			<?php foreach ( $irina_items as $irina_i ) : ?>
				<details><summary><?php echo esc_html( (string) $irina_i['pregunta'] ); ?></summary><div class="di-muted"><?php echo wp_kses_post( wpautop( (string) ( $irina_i['respuesta'] ?? '' ) ) ); ?></div></details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
