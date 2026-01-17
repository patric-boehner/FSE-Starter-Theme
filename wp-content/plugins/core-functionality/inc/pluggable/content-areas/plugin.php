<?php

/**
 * Feature: Content Areas
 *
 * @package     Core Functionality
 * @subpackage  Pluggable Features
 * @author      Patrick Boehner
 * @link        https://patrickboehner.com
 * @copyright   Copyright (c) 2012-2025, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 *
 * =============================================================================
 * ARCHITECTURE OVERVIEW
 * =============================================================================
 *
 * Content Areas provides reusable, conditional block content that can be inserted
 * into templates via an ACF block. It's conceptually similar to FSE template parts
 * but designed as a content management feature rather than a theme feature.
 *
 * KEY COMPONENTS:
 * - `content_area` CPT: Stores block content, restricted to administrators
 * - `block_area_location` taxonomy: Defines WHERE content appears (e.g., "Header CTA", "Footer")
 * - `category` taxonomy: Enables conditional display based on current post's category
 * - `cf/block-area` ACF block: Inserts content areas into templates by location
 *
 * DATA FLOW:
 * 1. Editor places `cf/block-area` block in template, selects a location
 * 2. On render, get_rendered_content_area() queries content_area posts by location
 * 3. prioritize_content_areas() selects the best match based on category context
 * 4. Matching content is rendered through the_content filter
 *
 * PRIORITIZATION LOGIC:
 * - Content areas WITH categories assigned: shown only when current post matches
 * - Content areas WITHOUT categories: serve as defaults/fallbacks
 * - First specific match wins if multiple exist
 *
 * =============================================================================
 * DESIGN DECISIONS
 * =============================================================================
 *
 * WHY A CUSTOM POST TYPE INSTEAD OF TEMPLATE PARTS?
 *
 * 1. Client UX: Content Areas appear as their own admin menu, keeping clients
 *    out of the Site Editor. Template parts live under Appearance and encourage
 *    wandering into theme editing.
 *
 * 2. Mental model: These are "content that appears in locations" not "parts of
 *    the theme". The distinction matters for content governance.
 *
 * 3. Conditional logic: Template parts don't natively support category-based
 *    display rules. This would require the same custom block regardless.
 *
 * ALTERNATIVE CONSIDERED: Migrating to `wp_template_part` CPT
 *
 * Pros:
 * - Closer to WordPress core
 * - Template parts get core improvements automatically
 * - Could use Site Editor UI (with controlled entry/exit points like
 *   Matt Cromwell's Synced Pattern Popups plugin approach)
 *
 * Cons:
 * - Clients would edit in Site Editor context
 * - Theme-bundled template parts (files) wouldn't participate in conditional system
 * - Would still need custom admin UI for location management
 * - Current system works fine
 *
 * Decision: Keep as CPT for now. Re-evaluate when extending for popups/banners.
 *
 * =============================================================================
 * FUTURE EXTENSION POSSIBILITIES
 * =============================================================================
 *
 * POPUPS & BANNERS (potential features):
 * - Trigger type: click, delay, scroll position, exit intent
 * - Trigger target: CSS class, element ID, timer value
 * - Display rules: show once, show until dismissed, scheduling
 * - Would add ACF fields for trigger configuration
 * - Frontend JS for popup/banner rendering
 *
 * Reference: Matt Cromwell's Synced Pattern Popups plugin demonstrates building
 * popup functionality on top of WordPress primitives (synced patterns) rather
 * than creating a parallel system. Same philosophy could apply here.
 * @link https://wordpress.org/plugins/synced-pattern-popups/
 * @link https://www.mattcromwell.com/product-thinking-synced-pattern-popups/
 *
 * IF MIGRATING TO TEMPLATE PARTS:
 * - Register block_area_location taxonomy on wp_template_part
 * - Build custom admin page listing template parts by location
 * - Control edit links to return to custom admin (not Site Editor nav)
 * - Update cf/block-area to query wp_template_part instead
 * - Keep prioritize_content_areas() logic as-is
 *
 * =============================================================================
 * FILE STRUCTURE
 * =============================================================================
 *
 * content-areas/
 * ├── plugin.php      - This file: block registration, rendering functions
 * ├── post-type.php   - CPT & taxonomy registration, admin customizations
 * └── block/
 *     ├── block.json  - ACF block metadata
 *     ├── render.php  - Block render callback
 *     └── template.php - HTML output template
 *
 * =============================================================================
 * REFERENCES
 * =============================================================================
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * @link https://wpfieldwork.com/a-guide-to-registering-a-custom-acf-block-with-block-json/
 * @link https://www.modernwpdev.co/acf-blocks/registering-blocks/#block-data
 * @link https://fullstackdigital.io/blog/acf-block-json-with-wordpress-scripts-the-ultimate-custom-block-development-workflow/
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


/**
 * Select the most appropriate content area based on category matching
 *
 * This is the core conditional logic that makes Content Areas more powerful
 * than standard template parts. It enables category-specific content with
 * automatic fallback to defaults.
 *
 * MATCHING RULES:
 * 1. Content areas with categories that match the current post = specific match
 * 2. Content areas with no categories assigned = default/fallback
 * 3. If multiple specific matches exist, first one wins (consider adding priority)
 * 4. If no specific match, use the default
 * 5. If no default either, return null
 *
 * EXAMPLE:
 * Location "Header CTA" has three content areas:
 * - "Summer Sale Banner" (category: uncategorized) ← default
 * - "Tech Promo" (category: technology)
 * - "Sports Promo" (category: sports)
 *
 * Viewing a post in "technology" category → shows "Tech Promo"
 * Viewing a post in "lifestyle" category → shows "Summer Sale Banner" (default)
 *
 * @param array $content_areas Array of WP_Post objects to choose from
 * @param array $current_categories Slugs of the current post's categories
 * @return WP_Post|null The best matching content area or null
 */
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