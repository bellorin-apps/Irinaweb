<?php
/**
 * 404 útil (PLAN 8.7): cabecera a sangre, rutas de salida (pilares, primera consulta, contacto) y WhatsApp.
 * La respuesta HTTP 404 y el noindex de Rank Math evitan su indexación.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

get_header();
echo '<main id="content" class="di-main">';
irina_part(
	'bleed',
	[
		'title'   => __( 'Esta página <em>no existe</em>.', 'irina-gonzalez' ),
		'eyebrow' => __( 'Error 404', 'irina-gonzalez' ),
		'lead'    => __( 'Puede que el enlace haya cambiado o que tenga un error. Aquí tienes los caminos más útiles.', 'irina-gonzalez' ),
		'variant' => 'sand',
		'crumbs'  => [ [ __( 'Página no encontrada', 'irina-gonzalez' ), '' ] ],
		'cta'     => irina_whatsapp_button( '', __( 'Hola, llegué a una página que no existe en el sitio de la Dra. Irina González Sáez y quisiera agendar una consulta.', 'irina-gonzalez' ) ),
	]
);
$irina_routes = [
	[ home_url( '/' ), __( 'Inicio', 'irina-gonzalez' ), __( 'Otorrinolaringólogo en Monterrey.', 'irina-gonzalez' ) ],
	[ home_url( '/otorrinolaringologia/' ), __( 'Otorrinolaringología', 'irina-gonzalez' ), __( 'Oído, nariz y garganta, desde los 0 meses.', 'irina-gonzalez' ) ],
	[ home_url( '/sueno/' ), __( 'Ronquido y apnea del sueño', 'irina-gonzalez' ), __( 'Del ronquido al descanso.', 'irina-gonzalez' ) ],
	[ home_url( '/dra-irina-gonzalez-saez/' ), __( 'Dra. Irina González Sáez', 'irina-gonzalez' ), __( 'Formación, certificaciones y enfoque.', 'irina-gonzalez' ) ],
	[ home_url( '/primera-consulta/' ), __( 'Primera consulta', 'irina-gonzalez' ), __( 'Qué esperar, qué llevar y cómo agendar.', 'irina-gonzalez' ) ],
	[ home_url( '/contacto/' ), __( 'Contacto', 'irina-gonzalez' ), __( 'Consultorio, mapa y WhatsApp.', 'irina-gonzalez' ) ],
];
?>
<div class="di-container--narrow di-section">
	<ol class="di-path di-reveal">
		<?php
		$irina_n = 0;
		foreach ( $irina_routes as [ $irina_url, $irina_label, $irina_desc ] ) :
			// Solo rutas publicadas: una 404 no debe enviar a otra 404.
			$irina_page = get_page_by_path( trim( (string) wp_parse_url( $irina_url, PHP_URL_PATH ), '/' ) );
			if ( home_url( '/' ) !== $irina_url && ( ! $irina_page || 'publish' !== $irina_page->post_status ) ) :
				continue;
			endif;
			++$irina_n;
			?>
			<li><b><?php echo esc_html( sprintf( '%02d', $irina_n ) ); ?></b><span><a href="<?php echo esc_url( $irina_url ); ?>"><?php echo esc_html( $irina_label ); ?></a><br><span class="di-muted"><?php echo esc_html( $irina_desc ); ?></span></span></li>
		<?php endforeach; ?>
	</ol>
	<p class="di-muted di-reveal" style="margin-top:var(--space-lg)"><?php esc_html_e( 'Si llegaste desde un enlace antiguo de otorrino-monterrey.com, el contenido vive ahora en este sitio.', 'irina-gonzalez' ); ?></p>
</div>
<?php
irina_part( 'cta-bleed' );
echo '</main>';
get_footer();
