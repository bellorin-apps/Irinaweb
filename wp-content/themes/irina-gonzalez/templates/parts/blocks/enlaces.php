<?php
/**
 * Enlaces para redes (/links/): tarjeta centrada con isotipo, nombre y botones. Datos de PracticeSettings + enlaces extra.
 * $args: title, subtitle, extra (array de [label, url]).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a     = wp_parse_args(
	$args ?? [],
	[
		'title'    => '',
		'subtitle' => '',
		'extra'    => [],
	]
);
$irina_links = [];
if ( '' !== irina_whatsapp_url() ) {
	$irina_links[] = [ __( 'Agendar por WhatsApp', 'irina-gonzalez' ), irina_whatsapp_url(), 'message-circle', 'appointment_click', true ];
}
foreach ( [
	'instagram' => 'Instagram',
	'tiktok'    => 'TikTok',
	'facebook'  => 'Facebook',
] as $irina_k => $irina_l ) {
	if ( '' !== (string) irina_practice( $irina_k ) ) {
		$irina_links[] = [ $irina_l, (string) irina_practice( $irina_k ), 'arrow-right', '', false ];
	}
}
if ( '' !== (string) irina_practice( 'doctoralia_url' ) ) {
	$irina_links[] = [ __( 'Opiniones en Doctoralia', 'irina-gonzalez' ), (string) irina_practice( 'doctoralia_url' ), 'check-circle', 'doctoralia_click', false ];
}
if ( '' !== (string) irina_practice( 'maps_url' ) ) {
	$irina_links[] = [ __( 'Cómo llegar al consultorio', 'irina-gonzalez' ), (string) irina_practice( 'maps_url' ), 'map-pin', 'directions_click', false ];
}
$irina_links[] = [ __( 'Sitio web', 'irina-gonzalez' ), home_url( '/' ), 'arrow-right', '', false ];
foreach ( (array) $irina_a['extra'] as $irina_e ) {
	$irina_e = wp_parse_args(
		(array) $irina_e,
		[
			'label' => '',
			'url'   => '',
		]
	);
	if ( '' !== $irina_e['label'] && '' !== $irina_e['url'] ) {
		$irina_links[] = [ $irina_e['label'], $irina_e['url'], 'arrow-right', '', false ];
	}
}
?>
<section class="di-section di-links">
	<div class="di-links__card">
		<?php echo irina_brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<h1 class="di-links__title"><?php echo esc_html( '' !== $irina_a['title'] ? $irina_a['title'] : (string) irina_practice( 'nombre_profesional' ) ); ?></h1>
		<p class="di-muted"><?php echo esc_html( '' !== $irina_a['subtitle'] ? $irina_a['subtitle'] : trim( (string) irina_practice( 'especialidad' ) . ' · ' . (string) irina_practice( 'ciudad' ), ' ·' ) ); ?></p>
		<ul class="di-links__list">
			<?php foreach ( $irina_links as [ $irina_l, $irina_u, $irina_i, $irina_ev, $irina_primary ] ) : ?>
				<li><a class="di-btn <?php echo $irina_primary ? 'di-btn--whatsapp' : 'di-btn--ghost'; ?>" href="<?php echo esc_url( $irina_u ); ?>" target="_blank" rel="noopener"<?php echo '' !== $irina_ev ? ' data-di-event="' . esc_attr( $irina_ev ) . '"' : ''; ?>><?php echo irina_icon( $irina_i ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $irina_l ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
