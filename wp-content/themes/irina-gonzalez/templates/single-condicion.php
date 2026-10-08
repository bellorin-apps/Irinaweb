<?php
/**
 * Template single-condicion (Fase 6). Markup en templates/parts/medical-page.php.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
$irina_sleep = has_term( 'sueno', 'area', get_the_ID() );
echo '<main id="content" class="di-main' . ( $irina_sleep ? ' di-dark' : '' ) . '">';
while ( have_posts() ) {
	the_post();
	irina_part( 'medical-page' );
}
irina_part( 'cta-bleed' );
echo '</main>';
get_footer();
