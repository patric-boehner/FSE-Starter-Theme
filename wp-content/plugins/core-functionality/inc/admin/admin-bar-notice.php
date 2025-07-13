<?php

/**
* Admin Bar Filters
*
* @author      Patrick Boehner
* @link        http://www.patrickboehner.com
* @package     CoreFunctionality
* @copyright   Copyright (c) 2022, Patrick Boehner
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/


//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;;


//**********************

/**
* Forked from Server Press Local Admin Color Bar
* Plugin URL: https://serverpress.com/plugins/local-admin-bar-color
* Author: Gregg Franklin
*/

add_action( 'admin_bar_menu', 'cf_admin_bar_enviroment' );
function cf_admin_bar_enviroment() {

	if ( is_admin_bar_showing() ) {
		if ( cf_is_local_dev_site() || cf_is_staging_site() || cf_is_development_staging_site()) {

			// Add admin notice
			add_action( 'wp_before_admin_bar_render', 'cf_admin_bar_notice' );
			
		}
	}

}


function cf_admin_bar_notice() {

	// Check which site we are one & change text accordingly
	if ( cf_is_local_dev_site() ) {
		$admin_text = 'Local Development';
	} elseif ( cf_is_development_staging_site() ) {
		$admin_text = 'Development Staging';
	} elseif ( cf_is_staging_site() ) {
		$admin_text = 'Staging Website';
	}else {
		$admin_text = '';
	}

	$admin_notice = array(
		'parent'	=> 'top-secondary', /** puts it on the right side. */
		'id'		=> 'environment-notice',
		'title'		=> '<span>' . $admin_text . '</span>',
	);

	global $wp_admin_bar;
	$wp_admin_bar->add_menu( $admin_notice );

}