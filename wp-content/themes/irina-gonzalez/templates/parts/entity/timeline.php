<?php
/**
 * Trayectoria desde el CPT `credencial`: curso, conferencia, publicacion, experiencia (mostrar=true), por año descendente.
 * $args: eyebrow, title.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a    = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => __( 'Trayectoria', 'irina-gonzalez' ),
		'title'   => 'Formación <b>continua</b> en <em>cirugía de sueño</em>',
	]
);
$irina_q    = get_posts(
	[
		'post_type'      => 'credencial',
		'posts_per_page' => 50,
		'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			[
	'key'   => 'di_mostrar',
	'value' => '1',
			],
			[
			'key'     => 'di_tipo',
			'value'   => [ 'curso', 'conferencia', 'publicacion', 'experiencia', 'trabajo' ],
			'compare' => 'IN',
			],
		],
	]
);
$irina_rows = [];
foreach ( $irina_q as $irina_c ) {
	$irina_tipo   = (string) get_post_meta( $irina_c->ID, 'di_tipo', true );
	$irina_anio   = (string) get_post_meta( $irina_c->ID, 'di_anio', true );
	$irina_inst   = (string) get_post_meta( $irina_c->ID, 'di_institucion', true );
	$irina_lug    = (string) get_post_meta( $irina_c->ID, 'di_lugar', true );
	$irina_pre    = [
		'conferencia' => __( 'Ponente: ', 'irina-gonzalez' ),
		'publicacion' => __( 'Coautora: ', 'irina-gonzalez' ),
		'trabajo'     => __( 'Trabajo presentado: ', 'irina-gonzalez' ),
	][ $irina_tipo ] ?? '';
	$irina_rows[] = [ '' !== $irina_anio ? $irina_anio : __( 'Docencia', 'irina-gonzalez' ), $irina_pre . $irina_c->post_title . ( '' !== $irina_inst ? ', ' . $irina_inst : '' ) . ( '' !== $irina_lug ? ', ' . $irina_lug : '' ) ];
}
if ( ! $irina_rows ) {
	return;
}
usort( $irina_rows, static fn( array $x, array $y ): int => strcmp( $y[0], $x[0] ) );
?>
<section class="di-section di-section--soft">
	<div class="di-container">
		<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p>
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
		</h2>
		<ul class="di-timeline di-reveal di-reveal--d2">
		<?php
		foreach ( $irina_rows as [ $irina_y, $irina_t ] ) :
			?>
			<li><span class="di-timeline__y"><?php echo esc_html( $irina_y ); ?></span><span><?php echo esc_html( $irina_t ); ?></span></li><?php endforeach; ?></ul>
	</div>
</section>
