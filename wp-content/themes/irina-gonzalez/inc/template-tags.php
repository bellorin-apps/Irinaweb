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

/**
 * Dato del consultorio desde la fuente única de verdad (plugin). Vacío si el plugin no está.
 *
 * @return mixed
 */
function irina_practice( string $key, $fallback = '' ) {
	return class_exists( '\DraIrina\Core\Settings\PracticeSettings' ) ? \DraIrina\Core\Settings\PracticeSettings::get( $key, $fallback ) : $fallback;
}

/**
 * URL de WhatsApp con mensaje contextual.
 */
function irina_whatsapp_url( string $message = '' ): string {
	return class_exists( '\DraIrina\Core\Settings\PracticeSettings' ) ? \DraIrina\Core\Settings\PracticeSettings::whatsapp_url( $message ) : '';
}

/**
 * Teléfono legible.
 */
function irina_phone_label( string $e164 ): string {
	return class_exists( '\DraIrina\Core\Rendering\Shortcodes' ) ? \DraIrina\Core\Rendering\Shortcodes::format_phone( $e164 ) : $e164;
}

/**
 * Horario como filas [día, horas] a partir de "Día|HH:MM-HH:MM[,HH:MM-HH:MM]".
 *
 * @return array<int, array{0:string,1:string}>
 */
function irina_hours_rows(): array {
	$rows = [];
	foreach ( (array) preg_split( '/\r\n|\r|\n/', (string) irina_practice( 'horario' ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 === count( $parts ) && '' !== $parts[0] ) {
			$rows[] = [ $parts[0], str_replace( [ '-', ',' ], [ '–', ' y ' ], $parts[1] ) ];
		}
	}
	return $rows;
}

/**
 * Dirección en una línea.
 */
function irina_address_line(): string {
	$p = [ irina_practice( 'calle' ), irina_practice( 'interior' ), irina_practice( 'colonia' ), trim( irina_practice( 'cp' ) . ' ' . irina_practice( 'ciudad' ) ), irina_practice( 'estado' ) ];
	return implode( ', ', array_filter( array_map( 'strval', $p ) ) );
}

/**
 * Marca (anillos) hasta que se exporte el logotipo definitivo en SVG.
 */
function irina_brand_mark(): string {
	return '<svg class="di-brand__mark" viewBox="0 0 40 40" aria-hidden="true" focusable="false"><circle cx="20" cy="20" r="17" fill="none" stroke="currentColor" stroke-width="2.5" opacity=".9"/><circle cx="20" cy="20" r="10" fill="none" stroke="#3CA1A7" stroke-width="2.5"/><circle cx="20" cy="20" r="3" fill="currentColor"/></svg>';
}

/**
 * Botón de WhatsApp con evento de medición.
 */
function irina_whatsapp_button( string $label = '', string $message = '', string $css_class = 'di-btn di-btn--whatsapp' ): string {
	$url = irina_whatsapp_url( $message );
	if ( '' === $url ) {
		return '';
	}
	return sprintf(
		'<a class="%s" href="%s" target="_blank" rel="noopener" data-di-event="appointment_click">%s%s</a>',
		esc_attr( $css_class ),
		esc_url( $url ),
		irina_icon( 'message-circle', 'di-icon' ),
		esc_html( '' !== $label ? $label : __( 'Agendar por WhatsApp', 'irina-gonzalez' ) )
	);
}

/**
 * Migas de pan visibles (el BreadcrumbList lo emite Rank Math / el core).
 *
 * @param array<int, array{0:string,1:string}> $trail Pares [etiqueta, url]; el último sin url.
 */
function irina_breadcrumbs( array $trail ): string {
	$items = [ sprintf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Inicio', 'irina-gonzalez' ) ) ];
	foreach ( $trail as [ $label, $url ] ) {
		$items[] = '' !== $url ? sprintf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) ) : sprintf( '<li aria-current="page">%s</li>', esc_html( $label ) );
	}
	return '<nav class="di-crumbs" aria-label="' . esc_attr__( 'Migas de pan', 'irina-gonzalez' ) . '"><ol>' . implode( '', $items ) . '</ol></nav>';
}
