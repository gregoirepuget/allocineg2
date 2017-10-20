<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themotion
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="themotion-page" class="themotion-site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'themotion-lite' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container container-header">
			<div class="header-inner">
				<div class="header-inner-site-branding">
					<div class="site-branding-wrap">
						<div class="site-branding">
							<?php themotion_brand(); ?>
						</div><!-- .site-branding -->
					</div>



				</div>
				<div class="main-navigation-wrap">
					<div class="main-navigation-wrap-inner">
						<nav id="site-navigation" class="main-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'menu_id' => 'primary-menu',
								)
							);
							?>
						</nav><!-- #site-navigation -->


						<div class="menu-toggle-button-wrap">
							<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="fa fa-bars"></i></button>
						</div>

						<div class="header-social-icons">
							<ul class="social-media-icons">
								<?php
								themotion_social_icons();
								themotion_search_icon();
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .container-header -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div class="container">
