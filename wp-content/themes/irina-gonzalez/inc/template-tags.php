<?php
/**
 * Helpers de presentación. Nunca lógica de datos (eso vive en dra-irina-core).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

/**
 * Icono Lucide inline desde el sprite del tema.
 */
function irina_icon( string $name, string $css_class = 'di-icon', int $size = 24 ): string {
	return sprintf(
		'<svg class="%1$s" width="%2$d" height="%2$d" aria-hidden="true" focusable="false"><use href="%3$s#%4$s"></use></svg>',
		esc_attr( $css_class ),
		$size,
		esc_url( IRINA_THEME_URI . '/assets/icons/lucide.svg' ),
		esc_attr( $name )
	);
}

/**
 * Parte de template con variables.
 *
 * @param string               $name Nombre de la parte dentro de templates/parts.
 * @param array<string, mixed> $args Variables disponibles en la parte.
 */
function irina_part( string $name, array $args = [] ): void {
	get_template_part( 'templates/parts/' . $name, null, $args );
}

/**
 * Caja "Revisado médicamente por" para contenido clínico.
 */
function irina_medical_review_badge( int $post_id ): string {
	$date = (string) get_post_meta( $post_id, 'di_fecha_revision_medica', true );
	if ( '' === $date ) {
		return '';
	}
	$name = class_exists( '\DraIrina\Core\Settings\PracticeSettings' ) ? (string) \DraIrina\Core\Settings\PracticeSettings::get( 'nombre_profesional' ) : '';
	return sprintf(
		'<p class="di-review-badge">%s <strong>%s</strong> · %s <time datetime="%s">%s</time></p>',
		esc_html__( 'Revisado médicamente por', 'irina-gonzalez' ),
		esc_html( $name ),
		esc_html__( 'Última revisión:', 'irina-gonzalez' ),
		esc_attr( $date ),
		esc_html( wp_date( 'j \d\e F \d\e Y', (int) strtotime( $date ) ) )
	);
}
