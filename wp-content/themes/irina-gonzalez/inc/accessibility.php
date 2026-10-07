<?php
/**
 * Accesibilidad integrada (WCAG 2.2 AA): atributos de navegación, foco visible y preferencia de movimiento.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

// Añade aria-current a los enlaces del menú activo.
add_filter(
	'nav_menu_link_attributes',
	static function ( array $atts, \WP_Post $item ): array {
		if ( in_array( 'current-menu-item', $item->classes ?? [], true ) ) {
			$atts['aria-current'] = 'page';
		}
		return $atts;
	},
	10,
	2
);

// Lenguaje del documento explícito.
add_filter( 'language_attributes', static fn( string $output ): string => str_contains( $output, 'lang=' ) ? $output : $output . ' lang="es-MX"' );
