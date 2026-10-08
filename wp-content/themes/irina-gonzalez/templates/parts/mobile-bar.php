<?php
/**
 * Barra inferior móvil: WhatsApp · Llamar · Cómo llegar (§55).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_tel  = (string) irina_practice( 'telefono' );
$irina_wa   = irina_whatsapp_url();
$irina_maps = (string) irina_practice( 'maps_url' );
if ( '' === $irina_wa && '' === $irina_tel ) {
	return;
}
?>
<nav class="di-mobile-bar" aria-label="<?php esc_attr_e( 'Acciones rápidas', 'irina-gonzalez' ); ?>">
	<?php
	if ( '' !== $irina_wa ) :
		?>
		<a href="<?php echo esc_url( $irina_wa ); ?>" target="_blank" rel="noopener" data-di-event="appointment_click"><?php echo irina_icon( 'message-circle', 'di-icon', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>WhatsApp</a><?php endif; ?>
	<?php
	if ( '' !== $irina_tel ) :
		?>
		<a href="tel:<?php echo esc_attr( $irina_tel ); ?>" data-di-event="phone_click"><?php echo irina_icon( 'phone', 'di-icon', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Llamar', 'irina-gonzalez' ); ?></a><?php endif; ?>
	<?php
	if ( '' !== $irina_maps ) :
		?>
		<a href="<?php echo esc_url( $irina_maps ); ?>" target="_blank" rel="noopener" data-di-event="directions_click"><?php echo irina_icon( 'map-pin', 'di-icon', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Cómo llegar', 'irina-gonzalez' ); ?></a><?php endif; ?>
</nav>
