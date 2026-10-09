<?php
/**
 * Header del child theme (D-027: header/footer en PHP; Theme Builder de Elementor tiene prioridad si define la ubicación).
 *
 * @package IrinaGonzalez
 */

declare( strict_types=1 );

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	return;
}
?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Ir al contenido', 'irina-gonzalez' ); ?></a>
<header class="di-header" id="di-header">
	<div class="di-container di-header__inner">
		<a class="di-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php echo irina_brand_block( 'header' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG del tema y texto escapado. ?>
		</a>
		<nav class="di-nav" aria-label="<?php esc_attr_e( 'Principal', 'irina-gonzalez' ); ?>">
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'di-nav__list',
					'depth'          => 1,
					'fallback_cb'    => 'irina_nav_fallback',
				]
			);
			echo irina_whatsapp_button( '', '', 'di-btn di-btn--whatsapp', __( 'WhatsApp', 'irina-gonzalez' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado en la función.
			echo irina_social_circles(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escapado en la función.
			?>
		</nav>
		<button class="di-burger" type="button" aria-expanded="false" aria-controls="di-menu-panel" aria-label="<?php esc_attr_e( 'Abrir menú', 'irina-gonzalez' ); ?>"><?php echo irina_icon( 'menu', 'di-icon', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
	</div>
</header>
<div class="di-menu-panel" id="di-menu-panel">
	<?php
	wp_nav_menu(
		[
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'di-menu-panel__list',
			'depth'          => 2,
			'fallback_cb'    => 'irina_nav_fallback',
		]
	);
	echo irina_whatsapp_button(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo irina_social_circles( 'di-social di-social--panel' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>
