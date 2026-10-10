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
		esc_url( IRINA_THEME_URI . '/assets/icons/lucide.svg?v=' . irina_asset_version( 'assets/icons/lucide.svg' ) ),
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

/** Evita 404 desde Home mientras los pilares y fichas esperan aprobación médica. */
function irina_public_content_url( string $url ): string {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ( $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host ) || ! preg_match( '#^/(otorrinolaringologia|sueno|padecimientos|tratamientos)(/|$)#', $path ) ) {
		return $url;
	}
	$slug  = basename( rtrim( $path, '/' ) );
	$posts = get_posts(
		[
			'post_type'      => [ 'page', 'condicion', 'tratamiento' ],
			'post_name__in'  => [ $slug ],
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		]
	);
	if ( $posts ) {
		return (string) get_permalink( $posts[0] );
	}
	foreach ( [ 'primera-consulta', 'contacto' ] as $fallback ) {
		$page = get_page_by_path( $fallback );
		if ( $page && 'publish' === $page->post_status ) {
			return (string) get_permalink( $page );
		}
	}
	return home_url( '/' );
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
	if ( irina_practice( 'horario_oculto' ) ) {
		return [];
	}
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
 * SVG de marca inline desde assets/brand/<nombre>.svg, saneado (sin scripts, eventos ni referencias externas) y con clase/aria.
 * Devuelve '' si el archivo no existe.
 */
function irina_brand_svg( string $name, string $css_class ): string {
	$file = IRINA_THEME_DIR . '/assets/brand/' . sanitize_file_name( $name ) . '.svg';
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$svg = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- archivo local del tema.
	$svg = (string) preg_replace( '/<\?xml[^>]*>|<!DOCTYPE[^>]*>|<!--.*?-->|<script.*?<\/script>|<title>.*?<\/title>|<desc>.*?<\/desc>/is', '', $svg );
	$svg = (string) preg_replace( '/\son[a-z]+="[^"]*"|\sxlink:href="(?!#)[^"]*"|\shref="(?!#)[^"]*"/i', '', $svg );
	$svg = (string) preg_replace_callback(
		'/<svg\b[^>]*>/i',
		static fn( array $m ): string => str_replace( '<svg', sprintf( '<svg class="%s" aria-hidden="true" focusable="false"', esc_attr( $css_class ) ), (string) preg_replace( '/\s(class|width|height)="[^"]*"/i', '', $m[0] ) ),
		$svg,
		1
	);
	return trim( $svg );
}

/**
 * Isotipo de marca: assets/brand/isotipo.svg si existe; anillos provisionales si no.
 */
function irina_brand_mark(): string {
	$svg = irina_brand_svg( 'isotipo', 'di-brand__mark' );
	if ( '' !== $svg ) {
		return $svg;
	}
	return '<svg class="di-brand__mark" viewBox="0 0 40 40" aria-hidden="true" focusable="false"><circle cx="20" cy="20" r="17" fill="none" stroke="currentColor" stroke-width="2.5" opacity=".9"/><circle cx="20" cy="20" r="10" fill="none" stroke="#3CA1A7" stroke-width="2.5"/><circle cx="20" cy="20" r="3" fill="currentColor"/></svg>';
}

/**
 * Botón de WhatsApp con evento de medición.
 */
function irina_whatsapp_button( string $label = '', string $message = '', string $css_class = 'di-btn di-btn--whatsapp', string $short = '' ): string {
	$url = irina_whatsapp_url( $message );
	if ( '' === $url ) {
		return '';
	}
	$label = '' !== $label ? $label : __( 'Agendar por WhatsApp', 'irina-gonzalez' );
	$text  = '' !== $short
		? sprintf( '<span class="di-btn__long">%s</span><span class="di-btn__short">%s</span>', esc_html( $label ), esc_html( $short ) )
		: esc_html( $label );
	return sprintf(
		'<a class="%s" href="%s" target="_blank" rel="noopener" data-di-event="appointment_click">%s%s</a>',
		esc_attr( $css_class ),
		esc_url( $url ),
		irina_icon( 'brand-whatsapp', 'di-icon di-icon--brand' ),
		$text
	);
}

/**
 * Redes y Doctoralia como botones circulares (glifos oficiales; Doctoralia provisional hasta recibir su SVG).
 */
function irina_social_circles( string $css_class = 'di-social' ): string {
	$links = [
		'instagram'  => [ (string) irina_practice( 'instagram' ), 'Instagram', '' ],
		'tiktok'     => [ (string) irina_practice( 'tiktok' ), 'TikTok', '' ],
		'facebook'   => [ (string) irina_practice( 'facebook' ), 'Facebook', '' ],
		'doctoralia' => [ (string) irina_practice( 'doctoralia_url' ), 'Doctoralia', ' data-di-event="doctoralia_click"' ],
	];
	$out   = '';
	foreach ( $links as $key => [ $url, $name, $extra ] ) {
		if ( '' === $url ) {
			continue;
		}
		$out .= sprintf(
			'<a class="di-social__btn" href="%s" target="_blank" rel="noopener" aria-label="%s" title="%s"%s>%s</a>',
			esc_url( $url ),
			esc_attr( $name ),
			esc_attr( $name ),
			$extra, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- literal.
			irina_icon( 'brand-' . $key, 'di-icon di-icon--brand', 18 )
		);
	}
	return '' === $out ? '' : sprintf( '<div class="%s">%s</div>', esc_attr( $css_class ), $out );
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

/**
 * Bloque de marca de la cabecera/pie: logotipo completo (SVG claro + blanco, el CSS muestra uno u otro) o isotipo + nombre en texto,
 * según Ajustes → Consultorio. El nombre siempre va en texto (visible o solo para lectores de pantalla).
 */
function irina_brand_block( string $context = 'header' ): string {
	$name = (string) irina_practice( 'nombre_profesional', get_bloginfo( 'name' ) );
	$sub  = trim( (string) irina_practice( 'especialidad', '' ) . ( 'header' === $context ? ' · ' . (string) irina_practice( 'ciudad', '' ) : '' ), ' ·' );
	$full = irina_brand_svg( 'logotipo', 'di-brand__logo di-brand__logo--color' );
	if ( ! irina_practice( 'marca_isotipo_texto' ) && '' !== $full ) {
		$white = irina_brand_svg( 'logotipo-blanco', 'di-brand__logo di-brand__logo--white' );
		return $full . $white . sprintf( '<span class="screen-reader-text">%s · %s</span>', esc_html( $name ), esc_html( $sub ) );
	}
	return irina_brand_mark() . sprintf( '<span class="di-brand__name">%s<small>%s</small></span>', esc_html( $name ), esc_html( $sub ) );
}

/**
 * Texto que sustituye al horario cuando no se publica.
 */
function irina_hours_note(): string {
	return irina_practice( 'horario_oculto' ) ? (string) apply_filters( 'irina_hours_note', __( 'Previa cita. Agenda por WhatsApp.', 'irina-gonzalez' ) ) : '';
}

/**
 * Hospitales publicables (Ajustes → Consultorio), uno por línea.
 *
 * @return array<int, string>
 */
function irina_hospitals(): array {
	return array_values( array_filter( array_map( 'trim', (array) preg_split( '/\r\n|\r|\n/', (string) irina_practice( 'hospitales' ) ) ) ) );
}

/**
 * Las páginas con la plantilla «Elementor Header/Footer» no pasan por page.php: el tema abre/cierra <main> en header.php y footer.php
 * para que exista el punto de referencia y funcione el enlace «Ir al contenido» (PLAN 10.4).
 */
function irina_elementor_main(): bool {
	return is_singular() && 'elementor_header_footer' === get_page_template_slug( get_queried_object_id() );
}
