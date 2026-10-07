<?php
/**
 * Shortcodes de solo lectura sobre la fuente única de verdad.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Rendering;

use DraIrina\Core\Settings\PracticeSettings;

final class Shortcodes {

	public function register(): void {
		add_shortcode( 'di_phone', [ $this, 'phone' ] );
		add_shortcode( 'di_whatsapp', [ $this, 'whatsapp' ] );
		add_shortcode( 'di_address', [ $this, 'address' ] );
		add_shortcode( 'di_email', [ $this, 'email' ] );
		add_shortcode( 'di_hours', [ $this, 'hours' ] );
	}

	public static function format_phone( string $e164 ): string {
		$d = preg_replace( '/\D/', '', $e164 ) ?? '';
		if ( 12 === strlen( $d ) && str_starts_with( $d, '52' ) ) {
			return sprintf( '(%s) %s %s', substr( $d, 2, 2 ), substr( $d, 4, 4 ), substr( $d, 8 ) );
		}
		return $e164;
	}

	/** @param array|string $atts */
	public function phone( $atts = [] ): string {
		$tel = (string) PracticeSettings::get( 'telefono' );
		if ( '' === $tel ) {
			return '';
		}
		$a     = shortcode_atts(
			[
				'link'  => '1',
				'class' => '',
			],
			(array) $atts
		);
		$label = esc_html( self::format_phone( $tel ) );
		return '1' === $a['link']
			? sprintf( '<a href="tel:%s" class="%s" data-di-event="phone_click">%s</a>', esc_attr( $tel ), esc_attr( $a['class'] ), $label )
			: $label;
	}

	/** @param array|string $atts */
	public function whatsapp( $atts = [], ?string $content = null ): string {
		$a   = shortcode_atts(
			[
				'message' => '',
				'class'   => 'di-btn di-btn--whatsapp',
				'label'   => __( 'Agendar por WhatsApp', 'dra-irina-core' ),
			],
			(array) $atts
		);
		$url = PracticeSettings::whatsapp_url( (string) $a['message'] );
		if ( '' === $url ) {
			return '';
		}
		$label = $content ? wp_kses_post( $content ) : esc_html( (string) $a['label'] );
		return sprintf( '<a href="%s" class="%s" target="_blank" rel="noopener" data-di-event="appointment_click">%s</a>', esc_url( $url ), esc_attr( (string) $a['class'] ), $label );
	}

	public function address(): string {
		$p     = PracticeSettings::public_data();
		$lines = array_filter( [ $p['centro'] ?? '', trim( ( $p['calle'] ?? '' ) . ( ! empty( $p['interior'] ) ? ', ' . $p['interior'] : '' ) ), $p['colonia'] ?? '', trim( ( $p['cp'] ?? '' ) . ' ' . ( $p['ciudad'] ?? '' ) . ( ! empty( $p['estado'] ) ? ', ' . $p['estado'] : '' ) ) ] );
		if ( ! $lines ) {
			return '';
		}
		$html = '<address class="di-address">' . implode( '<br>', array_map( 'esc_html', $lines ) ) . '</address>';
		if ( ! empty( $p['maps_url'] ) ) {
			$html .= sprintf( ' <a href="%s" target="_blank" rel="noopener" data-di-event="directions_click">%s</a>', esc_url( $p['maps_url'] ), esc_html__( 'Cómo llegar', 'dra-irina-core' ) );
		}
		return $html;
	}

	public function email(): string {
		$e = (string) PracticeSettings::get( 'email' );
		return $e ? sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( antispambot( $e ) ) ) : '';
	}

	public function hours(): string {
		$raw = (string) PracticeSettings::get( 'horario' );
		if ( '' === $raw ) {
			return '';
		}
		$rows = '';
		foreach ( (array) preg_split( '/\r?\n/', $raw ) as $line ) {
			$parts = array_map( 'trim', explode( '|', $line ) );
			if ( 2 === count( $parts ) ) {
				$rows .= sprintf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $parts[0] ), esc_html( str_replace( ',', ' y ', $parts[1] ) ) );
			}
		}
		return $rows ? '<table class="di-hours"><tbody>' . $rows . '</tbody></table>' : '';
	}
}
