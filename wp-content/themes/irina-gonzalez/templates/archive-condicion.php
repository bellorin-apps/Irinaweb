<?php
/**
 * Archivo de condicion: hub con lista numerada.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
echo '<main id="content" class="di-main">';
irina_part(
	'bleed',
	[
		'title'   => post_type_archive_title( '', false ),
		'eyebrow' => __( 'Otorrinolaringología', 'irina-gonzalez' ),
		'lead'    => (string) get_the_post_type_description(),
		'variant' => 'default',
		'crumbs'  => [ [ post_type_archive_title( '', false ), '' ] ],
	]
);
echo '<div class="di-container di-section"><ul class="di-symptoms">';
$irina_n = 0;
while ( have_posts() ) {
	the_post();
	++$irina_n;
	printf( '<li><a class="di-symptom di-reveal" href="%s"><span class="di-symptom__n">%02d</span><span><b>%s</b><small>%s</small></span>%s</a></li>', esc_url( get_permalink() ), $irina_n, esc_html( get_the_title() ), esc_html( (string) get_post_meta( get_the_ID(), 'di_resumen_paciente', true ) ), irina_icon( 'arrow-right' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
echo '</ul></div>';
irina_part( 'cta-bleed' );
echo '</main>';
get_footer();
