<?php
/**
 * Home · pasos de la consulta. $args: eyebrow, title, steps (array de [title, text]).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => '',
		'steps'   => [],
	]
);
?>
<section class="di-section" id="consulta">
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
					'b'  => [],
					'br' => [ 'class' => [] ],
				]
			);
			?>
			</h2><?php endif; ?>
		<ol class="di-steps">
			<?php
			$irina_d = 0;
			foreach ( (array) $irina_a['steps'] as $irina_s ) :
				$irina_s = wp_parse_args(
					(array) $irina_s,
					[
						'title' => '',
						'text'  => '',
					]
				);
				?>
				<li class="di-step di-reveal di-reveal--d<?php echo esc_attr( (string) min( 4, $irina_d++ ) ); ?>"><h4><?php echo esc_html( $irina_s['title'] ); ?></h4><p class="di-muted"><?php echo esc_html( $irina_s['text'] ); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
