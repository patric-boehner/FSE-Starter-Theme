<?php
/**
 * Register the Content Areas Post Type
 *
 * @package    CoreFunctionality
 * @since      2.0.0
 * @copyright  Copyright (c) 2020, Patrick Boehner
 * @license    GPL-2.0+
 * 
 * @link: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * @link: https://wpfieldwork.com/a-guide-to-registering-a-custom-acf-block-with-block-json/
 * @link: https://www.modernwpdev.co/acf-blocks/registering-blocks/#block-data
 * @link: https://fullstackdigital.io/blog/acf-block-json-with-wordpress-scripts-the-ultimate-custom-block-development-workflow/
 */


 //* Block Acess
 //**********************
 if( !defined( 'ABSPATH' ) ) exit;


// Bring in related files
require_once( CORE_DIR . 'inc/pluggable/content-areas/post-type.php' );


// Register the block type
add_action( 'init', 'cf_register_block_area_block', 5 );
function cf_register_block_area_block() {

	// Check availability of block editor
    if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// adjust location(s) as needed for block.json
  	register_block_type( CORE_DIR . 'inc/pluggable/content-areas/block' );

}


/**
 * Get rendered content for a content area block
 * 
 * @param int|null $current_post_id Optional post ID for context (defaults to current post)
 * @return string|null Rendered HTML content or null if nothing to show
 */
function get_rendered_content_area( $location_term_id, $current_post_id = null ) {
    
    /**
     * Validate location
     */
    if ( empty( $location_term_id ) ) {
        return null;
    }
    
    /**
     * Check pagination awareness
     */
    // $location_term = get_term( $location_term_id, 'block_area_location' );
    // $is_menu_area = $location_term && str_contains( strtolower( $location_term->name ), 'menu' );
    
    // if (!$is_menu_area && is_paged()) {
    //     return null;
    // }
    
    /**
     * Get current post context if not provided
     */
    if ( !$current_post_id ) {
        $current_post_id = get_the_ID();
    }
    
    /**
     * Find the best content area
     */
    $content_area = get_content_area_content( $location_term_id, $current_post_id );
    
    if ( !$content_area ) {
        return null;
    }
    
    /**
     * Set up WordPress context and render content
     */
    $content = $content_area->post_content;
    
    // Save current post context
    global $post;
    $original_post = $post;
    $post = $content_area;
    setup_postdata($post);
    
    // Render the blocks
    $rendered_content = apply_filters( 'the_content', $content );
    
    // Restore original context
    $post = $original_post;
    wp_reset_postdata();
    
    return $rendered_content;

}


/**
 * Get the most appropriate content area for a given location
 * 
 * @param int $location The term ID of the location taxonomy
 * @param int|null $current_post_id The current post ID for context
 * @return WP_Post|null The best matching content area post or null if none found
 */
function get_content_area_content( $location, $current_post_id = null ) {

    /**
     * Query all content areas tagged with this location
     * Filters by the block_area_location taxonomy
     */
    $content_areas = new WP_Query(array(
        'post_type' => 'content_area',
        'posts_per_page' => 100,
        'tax_query' => array(
            array(
                'taxonomy' => 'block_area_location',
                'field' => 'term_id',
                'terms' => $location
            )
        ),
        'ignore_sticky_posts' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    ));
    
    /**
     * Early exit if no content areas found for this location
     */
    if ( !$content_areas->have_posts() ) {
        wp_reset_postdata();
        return null;
    }
    
    /**
     * Get the current post's categories for context matching
     * Defaults to empty array if no post ID provided
     */
    $current_categories = $current_post_id ? wp_get_post_categories( $current_post_id, array('fields' => 'slugs') ) : array();
    
    /**
     * Find the best content area to use
     * Pass all found content areas and current post categories and determine which is most appropriate
     */
    $prioritized_content = prioritize_content_areas( $content_areas->posts, $current_categories );
    
    /**
     * Clean up and return result
     */
    wp_reset_postdata();
    
    return $prioritized_content;

}


function prioritize_content_areas( $content_areas, $current_categories ) {

    $specific_matches = array();
    $default_content = null;
    
    foreach ( $content_areas as $content_area ) {
        $content_categories = wp_get_post_terms(
            $content_area->ID, 
            'category', 
            array('fields' => 'slugs')
        );
        
        if ( is_wp_error( $content_categories ) ) {
            $content_categories = array();
        }
        
        if ( empty( $content_categories ) ) {
            $default_content = $content_area;
        } else {
            $intersection = array_intersect( $content_categories, $current_categories );
            if (! empty( $intersection ) ) {
                $specific_matches[] = $content_area;
            }
        }
    }
    
    return !empty( $specific_matches ) ? $specific_matches[0] : $default_content;

}