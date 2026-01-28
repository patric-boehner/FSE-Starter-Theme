<?php

/**
 * Feature: Admin bar notice
 *
 * @package     Core Functionality
 * @subpackage  Pluggable Features
 * @author      Patrick Boehner
 * @link        https://patrickboehner.com
 * @copyright   Copyright (c) 2012-2025, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 *
 * Description: Adds a notice to the admin bar about the current environment.
 */


//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;;


//**********************

/**
* Forked from Server Press Local Admin Color Bar & 10up Experience
* URL: https://serverpress.com/plugins/local-admin-bar-color
* URL: https://github.com/10up/10up-experience
*/



/**
 * Add a visual environment indicator to the WordPress admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
add_action( 'admin_bar_menu', 'cf_add_environment_indicator_to_admin_bar', 7 );
function cf_add_environment_indicator_to_admin_bar( $wp_admin_bar ) {

	if ( ! is_admin_bar_showing() ) {
		return;
	}

	$environment = cf_get_environment_slug();
	$config      = cf_get_environment_display_config( $environment );

	$title = sprintf(
		'<span style="
			display: inline-flex;
			align-items: center;
			padding: 0px 8px;
			background-color: %1$s;
			color: %2$s;
			font-size: 13px;
		">
			<span class="dashicons-before dashicons-%3$s" style="margin-right: 4px; font: normal 20px/1 dashicons;"></span> %4$s
		</span>',
		esc_attr( $config['bg'] ),
		esc_attr( $config['text'] ),
		esc_attr( $config['icon'] ),
		esc_html( $config['label'] )
	);

	$wp_admin_bar->add_menu( [
		'id'     => 'cf-environment-indicator',
		'parent' => 'top-secondary',
		'title'  => $title,
	] );
}


/**
 * Map environment slug to label, icon, and color styles.
 *
 * @param string $environment
 *
 * @return array
 */
function cf_get_environment_display_config( $environment ) {
	switch ( $environment ) {
		case 'local':
			return [
				'label' => 'Local Development',
				'icon'  => 'admin-tools',
				'bg'    => '#34863b',      // green
				'text'  => '#ffffff',
			];
		case 'development-staging':
			return [
				'label' => 'Development Staging',
				'icon'  => 'admin-network',
				'bg'    => '#ffbb00d4',    // yellow
				'text'  => '#000000',
			];
		case 'staging':
			return [
				'label' => 'Staging Website',
				'icon'  => 'admin-generic',
				'bg'    => '#dc3232',      // red
				'text'  => '#ffffff',
			];
		default:
			return [
				'label' => 'Production',
				'icon'  => 'info',
				'bg'    => '#0073aa',      // WP blue
				'text'  => '#ffffff',
			];
	}
}
