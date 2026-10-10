<?php
/**
 * Archivo de recurso (/recursos/): centro de recursos para pacientes, lista numerada con resumen.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

// Sin artículos publicados el archivo existe pero no se indexa (noindex) hasta que haya contenido aprobado.
if ( ! have_posts() ) {
	add_filter( 'wp_robots', 'wp_robots_no_robots' );
}
get_header();
echo '<main id="content" class="di-main">';
irina_part(
	'bleed',
	[
		'title'   => __( 'Recursos para <b>cuidarte</b> en <em>casa</em>', 'irina-gonzalez' ),
		'eyebrow' => __( 'Centro de recursos', 'irina-gonzalez' ),
		'lead'    => __( 'Guías breves, revisadas por la Dra., para resolver las dudas más frecuentes de consulta: cómo cuidar oídos y nariz y qué esperar después de una cirugía.', 'irina-gonzalez' ),
		'variant' => 'default',
		'crumbs'  => [ [ __( 'Recursos', 'irina-gonzalez' ), '' ] ],
	]
);
echo '<div class="di-container di-section">';
if ( have_posts() ) {
	echo '<ul class="di-symptoms">';
	$irina_n = 0;
	while ( have_posts() ) {
		the_post();
		++$irina_n;
		printf( '<li><a class="di-symptom di-reveal" href="%s"><span class="di-symptom__n">%02d</span><span><b>%s</b><small>%s</small></span>%s</a></li>', esc_url( get_permalink() ), $irina_n, esc_html( get_the_title() ), esc_html( wp_strip_all_tags( (string) get_the_excerpt() ) ), irina_icon( 'arrow-right' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</ul>';
	the_posts_pagination( [ 'mid_size' => 1 ] );
} else {
	echo '<p class="di-muted">' . esc_html__( 'Pronto publicaremos las primeras guías.', 'irina-gonzalez' ) . '</p>';
}
echo '</div>';
irina_part( 'cta-bleed' );
echo '</main>';
get_footer();
