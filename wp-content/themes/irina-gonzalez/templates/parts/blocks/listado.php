<?php
/**
 * Listado numerado de condiciones/tratamientos. $args: eyebrow, title, note, post_type, area, zona, items (manual: [label, sub, url]), tight (bool).
 * Si hay entradas publicadas del CPT con esa área/zona, se listan; si no, los items manuales (enlaces a URLs futuras).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a    = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'   => '',
		'title'     => '',
		'note'      => '',
		'post_type' => 'condicion',
		'area'      => '',
		'zona'      => '',
		'items'     => [],
		'tight'     => false,
	]
);
$irina_rows = [];
if ( post_type_exists( (string) $irina_a['post_type'] ) ) {
	$irina_tax = [];
	if ( '' !== $irina_a['area'] ) {
		$irina_tax[] = [
			'taxonomy' => 'area',
			'field'    => 'slug',
			'terms'    => (string) $irina_a['area'],
		];
	}
	if ( '' !== $irina_a['zona'] ) {
		$irina_tax[] = [
			'taxonomy' => 'zona',
			'field'    => 'slug',
			'terms'    => (string) $irina_a['zona'],
		];
	}
	$irina_q = get_posts(
		[
			'post_type'      => (string) $irina_a['post_type'],
			'posts_per_page' => 40,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'tax_query'      => $irina_tax,
		]
	); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	foreach ( $irina_q as $irina_p ) {
		$irina_rows[] = [ $irina_p->post_title, (string) get_post_meta( $irina_p->ID, 'di_resumen_paciente', true ), get_permalink( $irina_p ) ];
	}
}
if ( ! $irina_rows ) {
	foreach ( (array) $irina_a['items'] as $irina_i ) {
		$irina_i = wp_parse_args(
			(array) $irina_i,
			[
				'label' => '',
				'sub'   => '',
				'url'   => '#',
			]
		);
		if ( '' !== $irina_i['label'] ) {
			$irina_rows[] = [ $irina_i['label'], $irina_i['sub'], $irina_i['url'] ];
		}
	}
}
if ( ! $irina_rows ) {
	return;
}
?>
<section class="di-section<?php echo $irina_a['tight'] ? ' di-section--tight' : ''; ?>">
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
		<?php
		if ( '' !== $irina_a['note'] ) :
			?>
			<p class="di-muted di-reveal di-reveal--d2"><?php echo esc_html( $irina_a['note'] ); ?></p><?php endif; ?>
		<ul class="di-symptoms di-reveal di-reveal--d2">
			<?php
			$irina_n = 0;
			foreach ( $irina_rows as [ $irina_l, $irina_s, $irina_u ] ) :
				++$irina_n;
				?>
				<li><a class="di-symptom" href="<?php echo esc_url( (string) $irina_u ); ?>"><span class="di-symptom__n"><?php echo esc_html( sprintf( '%02d', $irina_n ) ); ?></span><span><b><?php echo esc_html( (string) $irina_l ); ?></b>
				<?php
				if ( '' !== (string) $irina_s ) :
					?>
					<small><?php echo esc_html( (string) $irina_s ); ?></small><?php endif; ?></span><?php echo irina_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
