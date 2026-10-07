<?php
/**
 * Template archive-tratamiento — se construye en Fase 6 (núcleo visual). Mientras tanto delega en el parent.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
echo '<main id="content" class="di-container di-section">';
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_title( '<h1>', '</h1>' );
		the_content();
	}
}
echo '</main>';
get_footer();
