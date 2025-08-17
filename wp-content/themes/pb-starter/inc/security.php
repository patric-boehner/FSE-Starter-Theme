<?php
/**
 * WordPress Security
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


/**
 * Remove generator meta tags.
 *
 * @see https://developer.wordpress.org/reference/functions/the_generator/
 */
add_filter( 'the_generator', '__return_false' );


/**
 * Disable XML RPC.
 *
 * @see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
 */
add_filter( 'xmlrpc_enabled', '__return_false' );


/*
 * Disable REST API users endpoint for non-admins
 */
add_filter( 'rest_endpoints', 'fse_disable_rest_api_users_endpoint' );
function fse_disable_rest_api_users_endpoint( $endpoints ) {

    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }

    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }

    return $endpoints;

};


/*
 * Block direct ?author= enumeration attempts
 */
add_action( 'parse_request', 'fse_block_author_enumeration' );
function fse_block_author_enumeration( $query ) {

    if ( isset( $query->query_vars['author'] ) && !is_admin() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
    
};