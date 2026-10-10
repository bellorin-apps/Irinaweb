<?php
/**
 * Credenciales desde el CPT `credencial` (solo mostrar=true). $args: eyebrow, title, tags_title, extra_tags (array).
 * Grid: formacion, especialidad, certificacion. Etiquetas: membresia + extra_tags (hospitales).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a      = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'    => __( 'Formación y certificaciones', 'irina-gonzalez' ),
		'title'      => 'Credenciales <em>verificables</em>',
		'tags_title' => __( 'Membresías y hospitales', 'irina-gonzalez' ),
		'extra_tags' => [],
	]
);
$irina_q      = get_posts(
	[
		'post_type'      => 'credencial',
		'posts_per_page' => 50,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_key'       => 'di_mostrar', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
	]
);
$irina_labels = [
	'formacion'       => __( 'Medicina', 'irina-gonzalez' ),
	'especialidad'    => __( 'Especialidad', 'irina-gonzalez' ),
	'subespecialidad' => __( 'Subespecialización', 'irina-gonzalez' ),
	'certificacion'   => __( 'Certificación', 'irina-gonzalez' ),
];
$irina_cards  = [];
$irina_tags   = [];
foreach ( $irina_q as $irina_c ) {
	$irina_tipo = (string) get_post_meta( $irina_c->ID, 'di_tipo', true );
	$irina_inst = (string) get_post_meta( $irina_c->ID, 'di_institucion', true );
	$irina_lug  = (string) get_post_meta( $irina_c->ID, 'di_lugar', true );
	$irina_anio = (string) get_post_meta( $irina_c->ID, 'di_anio', true );
	if ( 'membresia' === $irina_tipo ) {
		$irina_tags[] = $irina_c->post_title;
	} elseif ( in_array( $irina_tipo, [ 'formacion', 'especialidad', 'certificacion' ], true ) ) {
		$irina_label = $irina_labels[ $irina_tipo ];
		if ( 'especialidad' === $irina_tipo && str_contains( strtolower( $irina_c->post_title ), 'subespecial' ) ) {
			$irina_label = $irina_labels['subespecialidad'];
		}
		$irina_cards[] = [ $irina_label, $irina_c->post_title, trim( implode( ', ', array_filter( [ $irina_inst, $irina_lug ] ) ) . ( '' !== $irina_anio ? '. ' . $irina_anio . '.' : '' ) ) ];
	}
}
$irina_extra = array_map( 'strval', (array) $irina_a['extra_tags'] );
$irina_tags  = array_merge( $irina_tags, $irina_extra ? $irina_extra : irina_hospitals() );
if ( ! $irina_cards && ( ! $irina_tags || '' === $irina_a['tags_title'] ) ) {
	return;
}
?>
<section class="di-section di-section--tight">
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
		<?php if ( $irina_cards ) : ?>
			<div class="di-cred-grid">
			<?php
			$irina_d = 0; foreach ( $irina_cards as [ $irina_l, $irina_t, $irina_s ] ) :
				?>
				<div class="di-cred di-reveal di-reveal--d<?php echo esc_attr( (string) min( 4, $irina_d++ ) ); ?>"><p class="di-eyebrow"><?php echo esc_html( $irina_l ); ?></p><h4><?php echo esc_html( $irina_t ); ?></h4><p class="di-muted"><?php echo esc_html( $irina_s ); ?></p></div><?php endforeach; ?></div>
		<?php endif; ?>
		<?php if ( $irina_tags && '' !== $irina_a['tags_title'] ) : // Con tags_title vacío, las membresías y hospitales van en la sección de logos (di-logos). ?>
			<h3 class="di-reveal" style="margin-top:var(--space-2xl)"><?php echo esc_html( $irina_a['tags_title'] ); ?></h3>
			<ul class="di-tags di-reveal di-reveal--d1">
			<?php
			foreach ( $irina_tags as $irina_t ) :
				?>
				<li><?php echo esc_html( $irina_t ); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
	</div>
</section>
