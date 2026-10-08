<?php
/**
 * Template single-recurso: artículo editorial con autoría, revisión y fuentes.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
echo '<main id="content" class="di-main">';
while ( have_posts() ) {
	the_post();
	$irina_id = get_the_ID();
	irina_part(
		'bleed',
		[
			'title'    => get_the_title(),
			'eyebrow'  => __( 'Recursos para pacientes', 'irina-gonzalez' ),
			'lead'     => (string) get_the_excerpt(),
			'variant'  => 'default',
			'crumbs'   => [ [ __( 'Recursos', 'irina-gonzalez' ), (string) get_post_type_archive_link( 'recurso' ) ], [ get_the_title(), '' ] ],
			'image_id' => (int) get_post_thumbnail_id(),
		]
	);
	echo '<div class="di-container--narrow di-section">';
	echo irina_medical_review_badge( $irina_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<div class="di-prose">';
	the_content();
	echo '</div>';
	printf( '<div class="di-inline-cta di-reveal"><div><b>%s</b><br><span class="di-muted">%s</span></div>%s</div>', esc_html__( '¿Quieres una valoración?', 'irina-gonzalez' ), esc_html__( 'Agenda en un toque por WhatsApp.', 'irina-gonzalez' ), irina_whatsapp_button() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$irina_fuentes = get_post_meta( $irina_id, 'di_fuentes', true );
	if ( is_array( $irina_fuentes ) && $irina_fuentes ) {
		echo '<div class="di-sources"><h3 style="font-size:1.2rem">' . esc_html__( 'Fuentes', 'irina-gonzalez' ) . '</h3><ol>';
		foreach ( $irina_fuentes as $irina_f ) {
			if ( ! empty( $irina_f['titulo'] ) ) {
				printf( '<li>%s</li>', ! empty( $irina_f['url'] ) ? sprintf( '<a href="%s" rel="noopener nofollow" target="_blank">%s</a>', esc_url( (string) $irina_f['url'] ), esc_html( (string) $irina_f['titulo'] ) ) : esc_html( (string) $irina_f['titulo'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		echo '</ol></div>';
	}
	echo '</div>';
}
echo '</main>';
get_footer();
