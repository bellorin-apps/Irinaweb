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
 * Isotipo oficial (arcos + «i»), tomado del SVG exportado por el propietario desde Illustrator (assets/brand/isotipo.svg).
 * Un solo trazado con fill="currentColor": púrpura (#9b589f) sobre fondo claro, blanco sobre hero/pie (ver components.css).
 * Versiones completas en assets/brand/ (isotipo*.svg y logotipo*.svg: base bicolor, -inverso, -mono, -blanco).
 */
function irina_brand_mark(): string {
	return '<svg class="di-brand__mark" viewBox="0 37 83 130" aria-hidden="true" focusable="false"><path fill="currentColor" d="M41.31 83.83v-.07a5.16 5.16 0 0 1 8.81-3.65c.93.93 1.51 2.22 1.51 3.65v.07a5.16 5.16 0 1 1-10.32 0m-5.9 17.41c-6.09-3.86-10.02-10.82-9.57-18.67.59-10.34 8.94-18.74 19.27-19.4 6.87-.44 13.08 2.49 17.14 7.29 1.95 2.3 5.44 2.44 7.58.31 1.9-1.9 2.03-4.94.29-6.99-5.89-6.95-14.79-11.27-24.69-10.95-16.09.53-29.23 13.54-29.91 29.63-.55 13.23 7.2 24.72 18.46 29.68 1.02.45 2.16-.28 2.16-1.39v-8.2c0-.53-.29-1.02-.74-1.31Zm11.06 49.67a5.16 5.16 0 0 0 5.16-5.16V99.28c0-1.43-.58-2.72-1.51-3.65a5.14 5.14 0 0 0-3.65-1.51 5.16 5.16 0 0 0-5.16 5.16v46.47a5.16 5.16 0 0 0 5.16 5.16m34.55-98.2c-8.61-9.57-21.15-15.55-35.08-15.39C20.9 37.61.39 58.01 0 83.05c-.31 20.06 12.09 37.26 29.66 44.08q2.22.87 4.56 1.5c.97.26 1.92-.48 1.92-1.48v-7.61c0-.66-.43-1.23-1.05-1.44-14.68-4.86-25.18-18.88-24.75-35.28.49-18.82 15.63-34.28 34.44-35.14 11.35-.52 21.61 4.2 28.57 11.95 1.96 2.18 5.37 2.24 7.45.17 1.93-1.93 2.04-5.05.21-7.08Zm-5.01 25.92H61.96c-1.42 0-2.71.58-3.65 1.51-.94.94-1.51 2.22-1.51 3.65a5.16 5.16 0 0 0 5.16 5.16h4.53a20.7 20.7 0 0 1-8.98 12.31c-.44.28-.7.78-.7 1.3v8.18c0 1.11 1.14 1.84 2.16 1.39 9.38-4.14 16.32-12.79 18.06-23.19.29-1.68.43-3.4.43-5.16 0-1.41-.09-2.8-.28-4.16-.08-.57-.58-.99-1.16-.99Zm-16.87 45.06c-1.01-.65-2.34.1-2.34 1.3v9.96c0 .32.1.62.28.88 1.45 2.09 2.3 4.62 2.3 7.35 0 7.09-5.72 12.84-12.79 12.9-2.68.02-5.01 2.01-5.26 4.67a5.16 5.16 0 0 0 5.14 5.65c8.05 0 15.15-4.1 19.32-10.32 2.47-3.69 3.92-8.13 3.92-12.91 0-6.22-2.45-11.88-6.43-16.04-1.24-1.3-2.63-2.45-4.14-3.44"/></svg>';
}

/**
 * Logotipo completo como <img> (archivo SVG del tema). $version: bicolor | inverso | mono | blanco.
 */
function irina_brand_logo( string $version = 'bicolor', string $css_class = 'di-brand__logo' ): string {
	$version = in_array( $version, [ 'bicolor', 'inverso', 'mono', 'blanco' ], true ) ? $version : 'bicolor';
	return sprintf(
		'<img class="%s" src="%s" width="276" height="144" alt="%s" decoding="async">',
		esc_attr( $css_class ),
		esc_url( IRINA_THEME_URI . '/assets/brand/logotipo' . ( 'bicolor' === $version ? '' : '-' . $version ) . '.svg' ),
		esc_attr( (string) irina_practice( 'nombre_profesional', get_bloginfo( 'name' ) ) )
	);
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
