<?php
/**
 * Footer del child theme (D-027). Todos los datos salen de PracticeSettings.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
	wp_footer();
	echo '</body></html>';
	return;
}
$irina_tel   = (string) irina_practice( 'telefono' );
$irina_wa    = (string) irina_practice( 'whatsapp' );
$irina_mail  = (string) irina_practice( 'email' );
$irina_rows  = irina_hours_rows();
$irina_cedul = irina_practice( 'publicar_cedulas' ) ? trim( (string) irina_practice( 'cedula_medicina' ) . ' · ' . (string) irina_practice( 'cedula_especialidad' ), ' ·' ) : '';
?>
<footer class="di-footer" id="di-footer">
	<div class="di-container">
		<p class="di-footer__big di-reveal"><?php echo esc_html( apply_filters( 'irina_footer_claim', __( 'Respirar bien, dormir bien, oír bien.', 'irina-gonzalez' ) ) ); ?></p>
		<div class="di-footer__cols">
			<div>
				<div class="di-footer__brand">
					<a class="di-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo irina_brand_block( 'footer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<p class="di-footer__tag"><?php echo esc_html( (string) irina_practice( 'subespecialidad' ) ); ?></p>
				</div>
				<?php if ( '' !== $irina_cedul ) : ?>
					<p class="di-footer__creds"><?php esc_html_e( 'Cédulas:', 'irina-gonzalez' ); ?> <?php echo esc_html( $irina_cedul ); ?><br><?php echo esc_html( (string) irina_practice( 'consejo_certificado' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Atención', 'irina-gonzalez' ); ?></h4>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => 'irina_nav_fallback',
					]
				);
				?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Consultorio', 'irina-gonzalez' ); ?></h4>
				<address class="di-address">
					<ul>
						<li><?php echo esc_html( (string) irina_practice( 'centro' ) ); ?><?php echo '' !== (string) irina_practice( 'interior' ) ? ', ' . esc_html( (string) irina_practice( 'interior' ) ) : ''; ?></li>
						<li><?php echo esc_html( (string) irina_practice( 'calle' ) ); ?></li>
						<li><?php echo esc_html( trim( (string) irina_practice( 'colonia' ) . ', ' . (string) irina_practice( 'cp' ) . ' ' . (string) irina_practice( 'ciudad' ) . ', ' . (string) irina_practice( 'estado' ), ', ' ) ); ?></li>
						<?php
						if ( '' !== $irina_tel ) :
							?>
							<li><a href="tel:<?php echo esc_attr( $irina_tel ); ?>" data-di-event="phone_click"><?php echo esc_html( irina_phone_label( $irina_tel ) ); ?></a></li><?php endif; ?>
						<?php
						if ( '' !== $irina_wa ) :
							?>
							<li><a href="<?php echo esc_url( irina_whatsapp_url() ); ?>" data-di-event="appointment_click">WhatsApp <?php echo esc_html( irina_phone_label( $irina_wa ) ); ?></a></li><?php endif; ?>
						<?php
						if ( '' !== $irina_mail ) :
							?>
							<li><a href="mailto:<?php echo esc_attr( $irina_mail ); ?>"><?php echo esc_html( $irina_mail ); ?></a></li><?php endif; ?>
					</ul>
				</address>
			</div>
			<div>
				<h4><?php esc_html_e( 'Horario', 'irina-gonzalez' ); ?></h4>
				<?php
				if ( '' !== irina_hours_note() ) :
					?>
					<p><?php echo esc_html( irina_hours_note() ); ?></p><?php endif; ?>
				<?php if ( $irina_rows ) : ?>
					<table class="di-hours">
					<?php
					foreach ( $irina_rows as [ $irina_day, $irina_h ] ) :
						?>
						<tr><th scope="row"><?php echo esc_html( $irina_day ); ?></th><td><?php echo esc_html( $irina_h ); ?></td></tr><?php endforeach; ?></table>
				<?php endif; ?>
				<?php echo irina_social_circles( 'di-social di-social--footer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado en la función (redes como logos, propietario 2026-10-10). ?>
			</div>
		</div>
		<div class="di-footer__legal">
			<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( (string) irina_practice( 'nombre_profesional' ) ); ?>. <?php esc_html_e( 'Todos los derechos reservados.', 'irina-gonzalez' ); ?></span>
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'legal',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => '__return_empty_string',
				]
			);
			?>
			<span><?php esc_html_e( 'La información de este sitio es educativa y no sustituye la consulta médica. No es un canal de urgencias.', 'irina-gonzalez' ); ?></span>
			<?php
			if ( '' !== (string) irina_practice( 'aviso_publicidad' ) ) :
				?>
				<span><?php esc_html_e( 'Aviso de Publicidad COFEPRIS n.º', 'irina-gonzalez' ); ?> <?php echo esc_html( (string) irina_practice( 'aviso_publicidad' ) ); ?>
				<?php
				if ( '' !== (string) irina_practice( 'aviso_funcionamiento' ) ) :
					?>
				· <?php esc_html_e( 'Aviso de Funcionamiento n.º', 'irina-gonzalez' ); ?> <?php echo esc_html( (string) irina_practice( 'aviso_funcionamiento' ) ); ?><?php endif; ?></span><?php endif; ?>
		</div>
	</div>
</footer>
<?php irina_part( 'mobile-bar' ); ?>
<?php wp_footer(); ?>
</body>
</html>
