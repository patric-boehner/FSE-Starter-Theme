<?php
/**
 * WordPress Cleanup
 *
 * @package pb-starter
 **/


/*
 * Strip out Comments RSS feed
 */
remove_action( 'wp_head','feed_links', 2 );
remove_action( 'wp_head','feed_links_extra', 3 );


/*
 * Remove RSD link from header
 */
remove_action ('wp_head', 'rsd_link');


/*
 * Add custom body classes to the archive pages.
 *
 * @param array $classes The existing body classes.
 * @return array The modified body classes.
 */
add_filter( 'body_class', 'fse_add_archive_style_body_class' );
function fse_add_archive_style_body_class( $classes ) {
	
    if ( is_archive() || is_home() || is_search() ) {
        $classes[] = 'archive';
    }

    return $classes;

}


/*
 * Remove inline CSS for emoji.
 * Remove WP emoji DNS prefetch
 */
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');	
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_filter('comment_text_rss', 'wp_staticize_emoji');	
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

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


/**
 * Remove welcome dashboard
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );


/**
 * Remove dashboard meta boxes
 */
add_action('wp_dashboard_setup', 'fse_remove_dashboard_widgets' );
function fse_remove_dashboard_widgets() {
	
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


/**
 * Disable Post by Email feature
 */
add_filter('enable_post_by_email_configuration', '__return_false');