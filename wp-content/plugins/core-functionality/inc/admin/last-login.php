<?php
/**
 * Last Login Tracking for Core Functionality Plugin
 *
 * Tracks and displays the last login time for users in the admin area.
 *
 * @package CoreFunctionality
 * @since 1.5.0
 */

// Block Access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Track last login time
add_action( 'wp_login', 'cf_track_user_last_login', 10, 2 );
function cf_track_user_last_login( $user_login, $user ) {

	update_user_meta( $user->ID, 'last_login', time() );

}


// 2FA login tracking (overwrites the wp_login timestamp)
add_action( 'two_factor_user_authenticated', 'cf_track_two_factor_login' );
function cf_track_two_factor_login( $user ) {

	update_user_meta( $user->ID, 'last_login', time() );

}


// Add Last Login column to users list
add_filter( 'manage_users_columns', 'cf_add_last_login_column' );
function cf_add_last_login_column( $columns ) {

	$columns['last_login'] = __( 'Last Login', 'core-functionality' );
	return $columns;

}


// Display last login data
add_action( 'manage_users_custom_column', 'cf_show_last_login_column_content', 10, 3 );
function cf_show_last_login_column_content( $value, $column_name, $user_id ) {

	if ( $column_name !== 'last_login' ) {
		return $value;
	}
	
	$user_id = absint( $user_id ); // Sanitize user ID
	$last_login = get_user_meta( $user_id, 'last_login', true );
	
	if ( empty( $last_login ) ) {
		return esc_html__( 'Never', 'core-functionality' );
	}
	
	return wp_date( 'M j, Y @ g:i a', intval( $last_login ) );
    
}


// Make the column sortable
add_filter( 'manage_users_sortable_columns', 'cf_make_last_login_column_sortable' );
function cf_make_last_login_column_sortable( $columns ) {

	$columns['last_login'] = 'last_login';
	return $columns;

}


// Handle sorting for last login column
add_action( 'pre_get_users', 'cf_handle_last_login_sorting' );
function cf_handle_last_login_sorting( $query ) {

	if ( ! is_admin() ) {
		return;
	}
	
	$orderby = $query->get( 'orderby' );
	if ( $orderby === 'last_login' ) {
		$query->set( 'meta_key', 'last_login' );
		$query->set( 'orderby', 'meta_value_num' );
	}

}