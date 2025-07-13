<?php
/**
 * WordPress Cleanup
 *
 * @package pb-starter
 **/


/**
 * Headers class
 */
add_action( 'wp_headers', 'fse_set_frame_option_header', 99, 1 );
function fse_set_frame_option_header( $headers ) {

	// Allow omission of this header
	if ( true === apply_filters( 'fse_disable_x_frame_options', false ) ) {
		return $headers;
	}

	// Valid header values are `SAMEORIGIN` (allow iframe on same domain) | `DENY` (do not allow anywhere)
	$header_value               = apply_filters( 'fse_x_frame_options', 'SAMEORIGIN' );
	$headers['X-Frame-Options'] = $header_value;
	return $headers;

}


// Strip out Comments RSS feed
remove_action( 'wp_head','feed_links', 2 );
remove_action( 'wp_head','feed_links_extra', 3 );


// Remove RSD link from header
remove_action ('wp_head', 'rsd_link');


/*
 * Add custom body classes to the archive pages.
 *
 * @param array $classes The existing body classes.
 * @return array The modified body classes.
 */
add_filter( 'body_class', 'add_archive_style_body_class' );
function add_archive_style_body_class( $classes ) {
	
    if ( is_archive() || is_home() || is_search() ) {
        $classes[] = 'archive';
    }

    return $classes;

}


// Remove inline CSS for emoji.
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');	
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_filter('comment_text_rss', 'wp_staticize_emoji');	
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Remove WP emoji DNS prefetch
add_filter('emoji_svg_url', '__return_false');


/**
 * Remove Admin bar logo
 */
add_action( 'wp_before_admin_bar_render', 'fse_remove_admin_wp_logo' );
function fse_remove_admin_wp_logo() {

	global $wp_admin_bar;
	$wp_admin_bar->remove_node('wp-logo');
	
}


/**
 * Remove Comments from Admin Bar
 *
 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance.
 */
add_action( 'admin_bar_menu', 'fse_remove_admin_bar_comments', 999 );
function fse_remove_admin_bar_comments( $wp_admin_bar ) {

    $wp_admin_bar->remove_node( 'comments' );

}


// Remove welcome dashboard
remove_action( 'welcome_panel', 'wp_welcome_panel' );


// Remove dashboard meta boxes
add_action('wp_dashboard_setup', 'fse_remove_dashboard_widgets' );
function fse_remove_dashboard_widgets() 
{
	global $wp_meta_boxes;

	// WordpRess
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// Gravity Forms
	// unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);

}