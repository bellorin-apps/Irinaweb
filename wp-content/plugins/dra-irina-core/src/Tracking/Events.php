<?php
/**
 * Eventos de conversión (MASTER_PROMPT §48): delega clics con data-di-event a GA4 si existe gtag.
 * No se envían datos sensibles; solo el nombre del evento y la ruta.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Tracking;

use DraIrina\Core\Settings\PracticeSettings;

final class Events {

	public const ALLOWED = [ 'appointment_click', 'whatsapp_click', 'phone_click', 'directions_click', 'contact_submit', 'doctoralia_click' ];

	public function register(): void {
		add_action( 'wp_head', [ $this, 'gtag' ], 5 );
		add_action( 'wp_footer', [ $this, 'inline_script' ], 99 );
	}

	/** ID de medición GA4 válido o cadena vacía. */
	public static function ga4_id(): string {
		$id = strtoupper( trim( (string) PracticeSettings::get( 'ga4_id' ) ) );
		return preg_match( '/^G-[A-Z0-9]{6,12}$/', $id ) ? $id : '';
	}

	/**
	 * Carga gtag.js (GA4) con configuración respetuosa: sin señales de Google ni personalización de anuncios, IP truncada,
	 * beacon para los eventos de salida, sin medir a quienes editan el sitio ni a navegadores con «Do Not Track».
	 * En /gracias/ registra la conversión contact_submit (más fiable que hacerlo antes de la redirección).
	 */
	public function gtag(): void {
		$id = self::ga4_id();
		if ( '' === $id || ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) ) {
			return;
		}
		$thanks = is_page( 'gracias' ) ? "gtag('event','contact_submit',{page_path:location.pathname});" : '';
		$script = "if(navigator.doNotTrack!=='1'&&window.doNotTrack!=='1'){var s=document.createElement('script');s.async=true;s.src='https://www.googletagmanager.com/gtag/js?id={$id}';document.head.appendChild(s);"
			. "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}window.gtag=gtag;gtag('js',new Date());"
			. "gtag('config','{$id}',{anonymize_ip:true,allow_google_signals:false,allow_ad_personalization_signals:false,transport_type:'beacon'});{$thanks}}";
		wp_print_inline_script_tag( $script, [ 'id' => 'di-ga4' ] );
	}

	public function inline_script(): void {
		$allowed = wp_json_encode( self::ALLOWED );
		$script  = "document.addEventListener('click',function(e){var a=e.target.closest('[data-di-event]');if(!a)return;var n=a.getAttribute('data-di-event');if({$allowed}.indexOf(n)<0)return;if(typeof window.gtag==='function'){window.gtag('event',n,{page_path:location.pathname});}});";
		wp_print_inline_script_tag( $script, [ 'id' => 'di-events' ] );
	}
}
