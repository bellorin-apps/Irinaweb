<?php
/**
 * Home · pasos de la consulta. $args: eyebrow, title, steps (array de [title, text, img]).
 * img (opcional): slug de un adjunto; ilustración decorativa ENCIMA del paso, y debajo lombriz, número, título y texto
 * (propietario, 2026-10-12/13).
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
		<?php
		// Ilustraciones por slug de adjunto, resueltas en una sola consulta.
		$irina_slugs = array_filter( array_map( static fn( $s ): string => sanitize_title( (string) ( ( (array) $s )['img'] ?? '' ) ), (array) $irina_a['steps'] ) );
		$irina_imgs  = [];
		if ( $irina_slugs ) {
			foreach ( get_posts(
				[
					'post_type'      => 'attachment',
					'post_status'    => 'inherit',
					'post_name__in'  => array_values( $irina_slugs ),
					'posts_per_page' => count( $irina_slugs ),
				]
			) as $irina_att ) {
				$irina_imgs[ $irina_att->post_name ] = (int) $irina_att->ID;
			}
		}
		?>
		<ol class="di-steps">
			<?php
			$irina_d = 0;
			foreach ( (array) $irina_a['steps'] as $irina_s ) :
				$irina_s   = wp_parse_args(
					(array) $irina_s,
					[
						'title' => '',
						'text'  => '',
						'img'   => '',
					]
				);
				$irina_img = $irina_imgs[ sanitize_title( (string) $irina_s['img'] ) ] ?? 0;
				?>
				<li class="di-step<?php echo $irina_img ? ' di-step--img' : ''; ?> di-reveal di-reveal--d<?php echo esc_attr( (string) min( 4, $irina_d++ ) ); ?>">
				<?php
				// Con ilustración (propietario, 2026-10-13): ilustración, lombriz, número, título y texto, en ese orden.
				if ( $irina_img ) :
					?>
					<div class="di-step__img" aria-hidden="true">
					<?php
					echo wp_get_attachment_image(
						$irina_img,
						'full',
						false,
						[
							'alt'      => '',
							'loading'  => 'lazy',
							'decoding' => 'async',
							'sizes'    => '(min-width: 900px) 200px, 160px',
						]
					);
					?>
					</div>
					<div class="di-step__body"><h4><?php echo esc_html( $irina_s['title'] ); ?></h4><p class="di-muted"><?php echo esc_html( $irina_s['text'] ); ?></p></div>
				<?php else : ?>
					<h4><?php echo esc_html( $irina_s['title'] ); ?></h4><p class="di-muted"><?php echo esc_html( $irina_s['text'] ); ?></p>
				<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
