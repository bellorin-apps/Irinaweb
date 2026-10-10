<?php
/**
 * Home · dos paneles de área. $args: eyebrow, title, orl (eyebrow,title,items,link,link_label,image_id), sleep (idem).
 * Con image_id el panel lleva la fotografía de fondo (propietario, tanda 2) y texto en blanco sobre un fundido inferior.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a     = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => '',
		'orl'     => [],
		'sleep'   => [],
	]
);
$irina_panel = static function ( array $p, string $mod ): void {
	$p         = wp_parse_args(
		$p,
		[
			'eyebrow'    => '',
			'title'      => '',
			'items'      => [],
			'link'       => '',
			'link_label' => '',
			'image_id'   => 0,
		]
	);
	$irina_img = (int) $p['image_id'];
	?>
	<a class="di-area di-area--<?php echo esc_attr( $mod ); ?><?php echo $irina_img ? ' di-area--photo' : ''; ?> di-reveal" href="<?php echo esc_url( $p['link'] ); ?>">
		<div class="di-area__fill">
		<?php
		echo $irina_img ? wp_get_attachment_image(
			$irina_img,
			'full',
			false,
			[
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(min-width: 900px) 50vw, 100vw',
			]
		) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapa su salida. 
		?>
									</div>
		<p class="di-eyebrow"><?php echo esc_html( $p['eyebrow'] ); ?></p>
		<h3><?php echo esc_html( $p['title'] ); ?></h3>
		<?php
		if ( $p['items'] ) :
			?>
			<ul>
			<?php
			foreach ( (array) $p['items'] as $irina_i ) :
				?>
			<li><?php echo esc_html( (string) $irina_i ); ?></li><?php endforeach; ?></ul><?php endif; ?>
		<span class="di-area__more"><?php echo esc_html( $p['link_label'] ); ?><?php echo irina_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</a>
	<?php
};
?>
<section class="di-section">
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
					'br' => [],
				]
			);
			?>
			</h2><?php endif; ?>
		<div class="di-areas">
		<?php
		$irina_panel( (array) $irina_a['orl'], 'orl' );
		$irina_panel( (array) $irina_a['sleep'], 'sleep' );
		?>
		</div>
	</div>
</section>
