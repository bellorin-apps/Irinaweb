<?php
/**
 * Home · motivos de consulta. $args: eyebrow, title, note, items (array de [label, sub, url]).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => '',
		'note'    => '',
		'items'   => [],
	]
);
?>
<section class="di-section di-section--tight">
	<div class="di-container">
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
		if ( '' !== $irina_a['note'] ) :
			?>
			<p class="di-muted di-reveal di-reveal--d2"><?php echo esc_html( $irina_a['note'] ); ?></p><?php endif; ?>
		<ul class="di-symptoms di-reveal di-reveal--d2">
			<?php
			$irina_n = 0;
			foreach ( (array) $irina_a['items'] as $irina_i ) :
				$irina_i = wp_parse_args(
					(array) $irina_i,
					[
						'label' => '',
						'sub'   => '',
						'url'   => '#',
					]
				);
				if ( '' === $irina_i['label'] ) {
					continue;
				} ++$irina_n;
				?>
				<li><a class="di-symptom" href="<?php echo esc_url( $irina_i['url'] ); ?>"><span class="di-symptom__n"><?php echo esc_html( sprintf( '%02d', $irina_n ) ); ?></span><span><b><?php echo esc_html( $irina_i['label'] ); ?></b><small><?php echo esc_html( $irina_i['sub'] ); ?></small></span><?php echo irina_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></li>
						<?php endforeach; ?>
		</ul>
	</div>
</section>
