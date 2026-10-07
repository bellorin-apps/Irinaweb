<?php
/**
 * Eventos de conversión (MASTER_PROMPT §48): delega clics con data-di-event a GA4 si existe gtag.
 * No se envían datos sensibles; solo el nombre del evento y la ruta.
 *
 * @package DraIrina\Core
 */

declare( strict_types=1 );

namespace DraIrina\Core\Tracking;

final class Events {

	public const ALLOWED = [ 'appointment_click', 'whatsapp_click', 'phone_click', 'directions_click', 'contact_submit', 'doctoralia_click' ];

	public function register(): void {
		add_action( 'wp_footer', [ $this, 'inline_script' ], 99 );
	}

	public function inline_script(): void {
		$allowed = wp_json_encode( self::ALLOWED );
		$script  = "document.addEventListener('click',function(e){var a=e.target.closest('[data-di-event]');if(!a)return;var n=a.getAttribute('data-di-event');if({$allowed}.indexOf(n)<0)return;if(typeof window.gtag==='function'){window.gtag('event',n,{page_path:location.pathname});}});";
		wp_print_inline_script_tag( $script, [ 'id' => 'di-events' ] );
	}
}
