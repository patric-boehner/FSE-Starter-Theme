<?php
/**
 * Plugin Name: Core Functionality
 * Plugin URI: https://example.com.com
 * Description: This custom plugin is a companion to your websites. It contains all your site's core functionality so that it is independent of your theme. For your site to have all its intended functionality, this plugin must be active.
 * Version: 1.5.2
 * Author: Patrick Boehner
 * Author URI: https://patrickboehner.com
 * Update URI: false
 * License: @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This plugin is inspired and originaly came from Bill Erickson's own example core plugin.
 * Original Project: https://github.com/billerickson/Core-Functionality
 *
 */

//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;


//* Setup
//**********************

// Plugin Path
if ( !defined( 'CORE_DIR' ) ) {
	define( 'CORE_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin URL
if ( !defined( 'CORE_URL' ) ) {
	define( 'CORE_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( !defined( 'CORE_FILE' ) ) {
	define( 'CORE_FILE', __FILE__ );
}

// Plugin Version
if ( !defined( 'CORE_VERSION' ) ) {
	define( 'CORE_VERSION', '1.5.2' );
}


//* Version Numbers
//**********************

//* Cache Busting
function cf_version_id() {

    if ( WP_DEBUG ) {
        return time();
    } else {
        return CORE_VERSION;
    }

}


//* Load Languages
//**********************

add_action( 'init', 'cf_plugin_load_textdomain' );
function cf_plugin_load_textdomain() {

    load_plugin_textdomain(
        'core-functionality',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );

}


//* Include Plugin Files
//**********************

// Functions
require_once( CORE_DIR . 'inc/functions/helper-functions.php' );
require_once( CORE_DIR . 'inc/functions/site-health.php' );
require_once( CORE_DIR . 'inc/functions/acf.php' );


/**
 * Pluggable Features Registration Array
 */
$cf_features = [
    'last-login-column',
    'admin-bar-notice',
    'content-areas',
    'related-posts',
    'icon-block',
    'recovery-mode-emails',
    'email-testing',
];

/**
 * Allow themes/plugins to modify the feature list
 */
$cf_features = apply_filters( 'core_functionality_features', $cf_features );

foreach ( $cf_features as $feature ) {

    $feature_file = CORE_DIR . "inc/pluggable/{$feature}/plugin.php";

    if ( file_exists( $feature_file ) ) {
        require_once $feature_file;
    }

}



// Plugin Activation hook
register_activation_hook( __FILE__, 'cf_core_functionality_activate_hook' );
function cf_core_functionality_activate_hook() {

	// Add Cron Events
	if ( function_exists( 'cf_add_cron_event_email_test' ) ) {
		cf_add_cron_event_email_test();
	}

	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();

}


// Plugin Deactivation Hook
register_deactivation_hook(  __FILE__, 'cf_core_functionality_deactivation_hook' );
function cf_core_functionality_deactivation_hook() {

	// Remove testing cron schedual
	wp_clear_scheduled_hook( 'cf_cron_email_test' );

	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();

}


// Plugin Uninstall Hook
register_uninstall_hook( __FILE__, 'cf_remove_core_functionality_hook' );
function cf_remove_core_functionality_hook() {

    wp_clear_scheduled_hook( 'cf_cron_email_test' );

}
