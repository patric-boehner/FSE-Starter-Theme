<?php
/**
 * Plugin Name: Core Functionality
 * Plugin URI: https://example.com.com
 * Description: This custom plugin is a companion to your websites. It contains all your site's core functionality so that it is independent of your theme. For your site to have all its intended functionality, this plugin must be active.
 * Version: 1.5.0
 * Author: Patrick Boehner
 * Author URI: https://patrickboehner.com
 * Update URI: false
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
	define( 'CORE_VERSION', '1.5.0' );
}


//* Version Numbers
//**********************

//* Cache Busting
function cf_version_id() {

    if (defined('WP_DEBUG')) {
        return current_time('timestamp');
    } else {
        return CORE_VERSION;
    }

}


//* Include Plugin Files
//**********************

// Admin
require_once( CORE_DIR . 'inc/admin/admin-bar-notice.php' );


// Functions
require_once( CORE_DIR . 'inc/functions/custom-meta.php' );
require_once( CORE_DIR . 'inc/functions/custom-functions.php' );
require_once( CORE_DIR . 'inc/functions/user-profile.php' );
require_once( CORE_DIR . 'inc/functions/acf.php' );


// Plugin
require_once( CORE_DIR . 'inc/pluggable/content-areas/plugin.php' );
require_once( CORE_DIR . 'inc/pluggable/related-posts/plugin.php' );
require_once( CORE_DIR . 'inc/pluggable/icon-block/plugin.php' );
require_once( CORE_DIR . 'inc/pluggable/toggles-block/plugin.php' );
require_once( CORE_DIR . 'inc/pluggable/toggle-item-block/plugin.php' );
// require_once( CORE_DIR . 'inc/pluggable/fatal-error-emails/plugin.php' );
require_once( CORE_DIR . 'inc/pluggable/email-testing/plugin.php' );
// require_once( CORE_DIR . 'inc/pluggable/email-template/plugin.php' );



// Plugin Activation hook
register_activation_hook( __FILE__, 'cf_core_functionality_activate_hook' );
function cf_core_functionality_activate_hook() {

	// Add Cron Events
	// cf_add_cron_event_email_test();

	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();

}


// Plugin Deactivation Hook
register_deactivation_hook(  __FILE__, 'cf_core_functionality_deactivation_hook' );
function cf_core_functionality_deactivation_hook() {

	// Remove testing cron schedual
	// wp_clear_scheduled_hook( 'cf_cron_email_test' );

	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();

}