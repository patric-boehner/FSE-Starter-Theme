<?php

/**
 * Adjustments to the user profile
 *
 * @package pb-starter
*/


//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;



// Disable color options
add_action( 'admin_init', 'cf_remove_user_profile_color_settings', 10 );
function cf_remove_user_profile_color_settings() {

	remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

}


// Disable application passwords
add_filter( 'wp_is_application_passwords_available', '__return_false' );