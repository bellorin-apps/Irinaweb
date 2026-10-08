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
