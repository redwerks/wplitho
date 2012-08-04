<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'wplitho' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper">

	<div id="header">

		<div id="home">
			<a href="<?php echo esc_attr( home_url() ); ?>"></a>
		</div>

		<div id="nav">
			<span class="icon"></span>
<?php
			if ( WPLithoThemeOptions::option( 'anchor_menu' ) ) {
				theme_hweb_anchor_menu();
			} else {
				wp_nav_menu( array( 'theme_location' => 'primary' ) );
			} ?>
		</div>

		<div id="logo"><?php bloginfo( 'sitename' ); ?></div>

		<div style="clear: both;"></div>
	</div>

	<div id="main">
