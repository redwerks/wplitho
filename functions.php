<?php

add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );

// wp_register_script( 'jquery.animate-enhanced', get_template_directory_uri() . '/jquery.animate-enhanced.js', array( 'jquery' ) );
wp_register_script( 'hpanelweb', get_template_directory_uri() . '/hpanelweb.js', array( 'jquery'/*, 'jquery.animate-enhanced'*/ ) );

if ( !is_admin() ) {
	wp_enqueue_script( 'hpanelweb' );
}

require_once( dirname( __FILE__ ) . '/options.php' );
require_once( dirname( __FILE__ ) . '/admin.php' );

function theme_wplitho_init() {
	if ( !WPLithoThemeOptions::option( 'anchor_menu' ) ) {
		register_nav_menu( 'primary', __( 'Primary Menu', 'wplitho' ) );
	}
}
add_action( 'after_setup_theme', 'theme_wplitho_init' );

function theme_wplitho_head() {
	echo "<style>\n";
	echo WPLithoThemeOptions::option( 'css' );
	$padding = WPLithoThemeOptions::option( 'padding' );
	if ( $padding ) {
		echo "\n.post .entry-content { padding: " . htmlspecialchars( $padding ) . "; }\n";
	}
	foreach( array(
		'font_body' => 'body',
		'font_header' => '.post .entry-content h1',
		'font_navigation' =>  '#nav',
	) as $key => $selector ) {
		$font = WPLithoThemeOptions::option( $key );
		if ( $font ) {
			echo "$selector { font-family: " . htmlspecialchars( $font ) . "; }\n";
		}
	}
	$line_height = WPLithoThemeOptions::option( 'line_height' );
	if ( $line_height ) {
		echo ".post .entry-content { line-height: " . htmlspecialchars( $line_height ) . "; }\n";
	}

	echo "#logo {\n";
	if ( $logo = WPLithoThemeOptions::option( 'logo' ) ) {
		echo "	background-image: url($logo);\n";
		echo "	color: transparent;\n";
	} else {
		echo "	background-image: none;\n";
		echo "	color: inherit;\n";
	}
	echo "}\n";

	echo "@media screen and (max-width: 500px) {\n";
	echo "	#logo {\n";
	if ( $logo_mobile = WPLithoThemeOptions::option( 'logo_mobile' ) ) {
		echo "		background-image: url($logo_mobile);\n";
		echo "		color: transparent;\n";
	} else {
		echo "		background-image: none;\n";
		echo "		color: inherit;\n";
	}
	echo "	}\n";
	echo "}\n";

	echo "</style>";
}
add_action( 'wp_head', 'theme_wplitho_head' );

function theme_wplitho_foot() { ?>
<script>
jQuery( function( $ ) {
	if ( $( window ).width() > 500 ) {
		$( '#main' ).hpanelweb( 'article.post', {
			visibleSiblings: $( '#wpadminbar, #header' ),
			padding: <?php echo json_encode( WPLithoThemeOptions::option( 'gap' ) ); ?>,
			leftOverlap: <?php echo json_encode( WPLithoThemeOptions::option( 'overlap' ) ); ?>,
			unifiedScroll: true
		} );
		// $( 'html, body' ).css( 'overflow', 'hidden' );
	}
	$( '#nav .icon' ).click( function() {
		$( '#nav' ).toggleClass( 'visible' );
	} );
} );
</script>
<?php
}
add_action( 'wp_footer', 'theme_wplitho_foot' );

function theme_wplitho_anchor_menu() {
	global $wp_query;
	echo '<div class="menu">';
	echo '<ul>';
	while ( $wp_query->have_posts() ) {
		$post = $wp_query->next_post();
		echo '<li><a href="#post-' . esc_attr( $post->ID ) . '">' . esc_html( $post->post_title ) . '</a></li>';
	}
	echo '</ul>';
	echo '</div>';
	$wp_query->rewind_posts();
}


add_filter( 'post_background_content_selector', 'theme_wplitho_post_background_content_selector' );
function theme_wplitho_post_background_content_selector( $selector ) {
	return "$selector .entry-content";
}

add_shortcode( 'blankspace', 'theme_wplitho_shortcode_blankspace' );
function theme_wplitho_shortcode_blankspace( $params ) {
	$attrs = '';
	if ( !isset( $params['mobile'] ) || $params['mobile'] !== 'show' ) {
		$attrs .= ' class="nomobile"';
	}
	$attrs .= ' style="margin-top: ' . esc_attr( $params['height'] ) . ';"';
	return "<div{$attrs}></div>";
}
