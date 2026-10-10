<?php
/**
 * Home · ubicación. Datos del consultorio desde PracticeSettings. $args: eyebrow, title, facts (array de [label, value]), map_embed (URL de iframe de Google Maps).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a   = wp_parse_args(
	$args ?? [],
	[
		'eyebrow'   => __( 'Consultorio', 'irina-gonzalez' ),
		'title'     => '',
		'facts'     => [],
		'map_embed' => '',
	]
);
$irina_tel = (string) irina_practice( 'telefono' );
$irina_fac = array_merge( [ [ __( 'Dirección', 'irina-gonzalez' ), irina_address_line() ] ], (array) $irina_a['facts'], '' !== $irina_tel ? [ [ __( 'Teléfono', 'irina-gonzalez' ), irina_phone_label( $irina_tel ) ] ] : [] );
// Título = nombre del centro en dos líneas: «CAB Medical,» / «Headquarters.» (última palabra en énfasis); la colonia es dirección (propietario, 2026-10-10).
$irina_centro = trim( (string) irina_practice( 'centro' ) );
$irina_words  = preg_split( '/\s+/', $irina_centro );
$irina_last   = (string) array_pop( $irina_words );
$irina_title  = '' !== $irina_a['title'] ? $irina_a['title'] : trim( implode( ' ', $irina_words ) . ',<br><em>' . $irina_last . '.</em>' );
?>
<section class="di-section" id="ubicacion">
	<div class="di-container di-location">
		<div>
			<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p>
			<h2 class="di-reveal di-reveal--d1">
			<?php
			echo wp_kses(
				$irina_title,
				[
					'em' => [],
					'br' => [],
				]
			);
			?>
			</h2>
			<dl class="di-facts di-reveal di-reveal--d2">
			<?php
			foreach ( $irina_fac as [ $irina_l, $irina_v ] ) :
				if ( '' === (string) $irina_v ) {
					continue; }
				?>
				<dt><?php echo esc_html( (string) $irina_l ); ?></dt><dd><?php echo esc_html( (string) $irina_v ); ?></dd><?php endforeach; ?></dl>
			<?php
			if ( '' !== irina_hours_note() ) :
				?>
				<p class="di-reveal di-reveal--d3"><b><?php esc_html_e( 'Horario', 'irina-gonzalez' ); ?>:</b> <?php echo esc_html( irina_hours_note() ); ?></p>
				<?php
			endif;
			$irina_rows = irina_hours_rows(); if ( $irina_rows ) :
				?>
				<table class="di-hours di-reveal di-reveal--d3">
				<?php
				foreach ( $irina_rows as [ $irina_day, $irina_h ] ) :
					?>
				<tr><th scope="row"><?php echo esc_html( $irina_day ); ?></th><td><?php echo esc_html( $irina_h ); ?></td></tr><?php endforeach; ?></table><?php endif; ?>
			<div class="di-hero-cta di-reveal di-reveal--d4"><?php echo irina_whatsapp_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			if ( '' !== (string) irina_practice( 'maps_url' ) ) :
				?>
				<a class="di-btn di-btn--ghost" href="<?php echo esc_url( (string) irina_practice( 'maps_url' ) ); ?>" target="_blank" rel="noopener" data-di-event="directions_click"><?php esc_html_e( 'Cómo llegar', 'irina-gonzalez' ); ?><?php echo irina_icon( 'arrow-right', 'di-icon di-icon--arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><?php endif; ?></div>
		</div>
		<div class="di-map di-reveal di-reveal--d1">
		<?php
		$irina_key = (string) irina_practice( 'maps_api_key' );
		$irina_lat = (string) irina_practice( 'geo_lat' );
		$irina_lng = (string) irina_practice( 'geo_lng' );
		if ( '' !== $irina_key && '' !== $irina_lat && '' !== $irina_lng ) :
			// Mapa con estilo propio (D-048): Maps JavaScript API + JSON de estilo (Snazzy Maps o paleta por defecto).
			irina_enqueue_map( $irina_key );
			?>
			<div class="di-map__canvas" id="di-map" data-lat="<?php echo esc_attr( $irina_lat ); ?>" data-lng="<?php echo esc_attr( $irina_lng ); ?>" data-zoom="16" data-title="<?php echo esc_attr( (string) irina_practice( 'centro' ) ); ?>" data-url="<?php echo esc_attr( (string) irina_practice( 'maps_url' ) ); ?>" role="img" aria-label="<?php esc_attr_e( 'Mapa del consultorio', 'irina-gonzalez' ); ?>"></div>
			<?php
		elseif ( '' !== $irina_a['map_embed'] ) :
			?>
			<iframe src="<?php echo esc_url( $irina_a['map_embed'] ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Mapa del consultorio', 'irina-gonzalez' ); ?>"></iframe>
			<?php
else :
	?>
			<?php esc_html_e( 'Mapa', 'irina-gonzalez' ); ?><?php endif; ?></div>
	</div>
</section>
