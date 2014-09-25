<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header <?php echo (get_query_var('pagename') == '') ? 'home' : ''; ?>" role="banner">
      <div class="top">
        <a href="/" class="koko-logo">
          <img src="/wp-content/themes/kokopelli/images/logo.jpg" />
        </a>

        <div class="social">
          <a href="https://www.facebook.com/kpembroidery" target="_blank">
            <img src="/wp-content/themes/kokopelli/images/facebook-icon.jpg" alt="Facebook" />
          </a>
        </div>

        <div class="contact">
          <h6>contact us today!</h6>
          <h2>747.999.KOKO</h2>
        </div>
      </div>
			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<h3 class="menu-toggle"><?php _e( 'Menu', 'twentythirteen' ); ?></h3>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentythirteen' ); ?>"><?php _e( 'Skip to content', 'twentythirteen' ); ?></a>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->

      <?php if(get_query_var('pagename') == '') : ?>
        <h1 class="site-title">We strive to make your ideas come to life.</h1>
      <?php endif ?>
		</header><!-- #masthead -->

		<div id="main" class="site-main">
