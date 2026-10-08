<?php
/**
 * Páginas estáticas sin Elementor (legales, FAQ con bloques): cabecera a sangre + ancho de lectura.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
echo '<main id="content" class="di-main">';
while ( have_posts() ) {
	the_post();
	// Páginas compuestas con Elementor sin plantilla «Theme» (p. ej. /links/): solo el contenido, sin cabecera a sangre.
	if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) {
		echo '<div class="di-elementor-page">';
		the_content();
		echo '</div>';
		continue;
	}
	irina_part(
		'bleed',
		[
			'title'    => get_the_title(),
			'eyebrow'  => (string) get_post_meta( get_the_ID(), 'di_eyebrow', true ),
			'lead'     => has_excerpt() ? (string) get_the_excerpt() : '',
			'variant'  => 'sand',
			'crumbs'   => [ [ get_the_title(), '' ] ],
			'image_id' => (int) get_post_thumbnail_id(),
		]
	);
	echo '<div class="di-container--narrow di-section"><div class="di-prose">';
	the_content();
	echo '</div></div>';
}
echo '</main>';
get_footer();
