<?php
/**
 * Sección morada · logos de hospitales y membresías en blanco, carrusel continuo (propietario, 2026-10-10). $args: eyebrow, title, items (array de name/slug/url).
 * Archivos en assets/brand/logos/<slug>.svg (o .png). Blancos por CSS (brightness/invert, sirve para SVG blanco o PNG negro); pista duplicada para el
 * desplazamiento continuo (como la marquesina), estática con prefers-reduced-motion.
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a = wp_parse_args(
	$args ?? [],
	[
		'eyebrow' => '',
		'title'   => __( 'Membresías y <em>hospitales</em>', 'irina-gonzalez' ),
		'items'   => [],
	]
);
$irina_logos = [];
foreach ( (array) $irina_a['items'] as $irina_it ) {
	$irina_slug = sanitize_key( (string) ( $irina_it['slug'] ?? '' ) );
	if ( '' === $irina_slug ) {
		continue;
	}
	foreach ( [ 'svg', 'png', 'webp' ] as $irina_ext ) {
		$irina_rel = 'assets/brand/logos/' . $irina_slug . '.' . $irina_ext;
		if ( is_readable( IRINA_THEME_DIR . '/' . $irina_rel ) ) {
			$irina_logos[] = [
				'name' => (string) ( $irina_it['name'] ?? $irina_slug ),
				'url'  => (string) ( $irina_it['url'] ?? '' ),
				'src'  => IRINA_THEME_URI . '/' . $irina_rel . '?v=' . irina_asset_version( $irina_rel ),
			];
			break;
		}
	}
}
if ( ! $irina_logos ) {
	return;
}
?>
<section class="di-section di-logos">
	<div class="di-container">
		<?php if ( '' !== $irina_a['eyebrow'] ) : ?>
			<p class="di-eyebrow di-reveal"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $irina_a['title'] ) : ?>
			<h2 class="di-reveal di-reveal--d1">
			<?php
			echo wp_kses(
				$irina_a['title'],
				[
					'b'  => [],
					'em' => [],
					'br' => [ 'class' => [] ],
				]
			);
			?>
			</h2>
		<?php endif; ?>
	</div>
	<div class="di-logos__rail di-reveal di-reveal--d2" role="list" aria-label="<?php echo esc_attr( wp_strip_all_tags( (string) $irina_a['title'] ) ); ?>">
		<div class="di-logos__track">
		<?php
		foreach ( [ 0, 1 ] as $irina_copy ) :
			foreach ( $irina_logos as $irina_l ) :
				$irina_tag  = '' !== $irina_l['url'] ? 'a' : 'span';
				$irina_attr = '' !== $irina_l['url'] ? ' href="' . esc_url( $irina_l['url'] ) . '" target="_blank" rel="noopener"' : '';
				?>
			<<?php echo $irina_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="di-logos__item"<?php echo $irina_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $irina_copy ? ' aria-hidden="true"' : ' role="listitem"'; ?>><img src="<?php echo esc_url( $irina_l['src'] ); ?>" alt="<?php echo esc_attr( $irina_copy ? '' : $irina_l['name'] ); ?>" loading="lazy" decoding="async" height="56"></<?php echo $irina_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php
			endforeach;
		endforeach;
		?>
		</div>
	</div>
</section>
