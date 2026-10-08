<?php
/**
 * Cabecera a sangre. $args: title (HTML permitido en em/br), eyebrow, lead, variant (warm|night|sand|default), short (bool), crumbs (array), image_id, cta (HTML), breath (bool).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

$irina_a       = wp_parse_args(
	$args ?? [],
	[
		'title'    => '',
		'eyebrow'  => '',
		'lead'     => '',
		'variant'  => 'warm',
		'short'    => true,
		'crumbs'   => [],
		'image_id' => 0,
		'cta'      => '',
		'breath'   => false,
	]
);
$irina_allowed = [
	'em' => [],
	'br' => [],
];
?>
<section class="di-bleed<?php echo $irina_a['short'] ? ' di-bleed--short' : ''; ?>">
	<div class="di-bleed__bg di-bleed__bg--<?php echo esc_attr( $irina_a['variant'] ); ?>"><?php echo $irina_a['image_id'] ? wp_get_attachment_image( (int) $irina_a['image_id'], 'irina-hero', false, [ 'fetchpriority' => 'high' ] ) : ''; ?></div>
	<?php
	if ( $irina_a['breath'] ) :
		?>
		<div class="di-breath" aria-hidden="true"></div>
		<?php
else :
	?>
		<div class="di-bleed__shade"></div><?php endif; ?>
	<div class="di-container">
		<?php
		if ( $irina_a['crumbs'] ) {
			echo irina_breadcrumbs( (array) $irina_a['crumbs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado en la función.
		}
		?>
		<?php
		if ( '' !== $irina_a['eyebrow'] ) :
			?>
			<p class="di-eyebrow di-reveal is-visible"><?php echo esc_html( $irina_a['eyebrow'] ); ?></p><?php endif; ?>
		<h1 class="di-reveal di-reveal--d1 is-visible"><?php echo wp_kses( $irina_a['title'], $irina_allowed ); ?></h1>
		<?php
		if ( '' !== $irina_a['lead'] ) :
			?>
			<p class="di-lead di-reveal di-reveal--d2 is-visible"><?php echo esc_html( $irina_a['lead'] ); ?></p><?php endif; ?>
		<?php
		if ( '' !== $irina_a['cta'] ) :
			?>
			<div class="di-hero-cta di-reveal di-reveal--d3 is-visible"><?php echo $irina_a['cta']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML construido con helpers escapados. ?></div><?php endif; ?>
	</div>
</section>
