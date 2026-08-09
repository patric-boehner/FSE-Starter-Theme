<?php
/**
 * Add content area post type
 *
 * @package    CoreFunctionality
 * @since      2.0.0
 * @copyright  Copyright (c) 2020, Patrick Boehner
 * @license    GPL-2.0+
 */


//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'cf_register_content_areas_post_type' ) ) {

    // Register Services post type
    add_action('init', 'cf_register_content_areas_post_type');
    function cf_register_content_areas_post_type() {

       $labels = array(
          'name'                => _x( 'Content Areas', 'Post Type General Name', 'core-functionality' ),
          'singular_name'       => _x( 'Content Area', 'Post Type Singular Name', 'core-functionality' ),
          'all_items'           => __( 'Content Areas', 'core-functionality' ),
          'menu_name'           => __( 'Content Areas', 'core-functionality' ),
          'name_admin_bar'      => __( 'Content Area', 'core-functionality' ),  // Singular
          'parent_item_colon'   => __( 'Parent Item:', 'core-functionality' ),
          'add_new_item'        => __( 'Add Content Area', 'core-functionality' ),
          'add_new'             => __( 'Add Content Area', 'core-functionality' ),
          'new_item'            => __( 'New Content Area', 'core-functionality' ),
          'edit_item'           => __( 'Edit Content Area', 'core-functionality' ),
          'update_item'         => __( 'Update Content Area', 'core-functionality' ),
          'view_item'           => __( 'View Content Area', 'core-functionality' ),
          'search_items'        => __( 'Search Content Areas', 'core-functionality' ),
          'not_found'           => __( 'No Content Areas found', 'core-functionality' ),
          'not_found_in_trash'  => __( 'No Content Areas found in Trash', 'core-functionality' ),
       );

       $rewrite = array(
          'slug'                => 'content-area',
          'with_front'          => false,
       );

       $args = array(
          'label'               => __( 'Content Area', 'core-functionality' ),
          'labels'              => $labels,
          'show_in_rest'        => true,
          'supports'            => array(
            'title',
            'editor',
            'revisions',
            'page-attributes'
   		   ),
          'hierarchical'        => false,
          'public'              => false,
          'has_archive'         => false,
          'publicly_queryable'  => is_admin(),
          'show_ui'             => true,
          'menu_icon'			  => 'dashicons-block-default',
          'can_export'          => true,
          'exclude_from_search' => true, // If set to true will remove the custom post type from search, but also from the main query on the taxonomy page
          'rewrite'             => false,
          'taxonomies'          => array( 'category' ),
          // Editors write CTA copy; placement stays admin-only via the _cf_slot
          // meta auth_callback in meta.php. Authors and Contributors stay out.
          'capability_type'     => 'page',
          'map_meta_cap'        => true,
       );

       register_post_type( 'content_area', $args );

    }
}


add_filter( 'post_row_actions', 'cf_remove_view_link_for_template_parts', 10, 2 );
function cf_remove_view_link_for_template_parts( $actions, $post ) {

    if ( $post->post_type == 'content_area' ) {
        unset($actions['view']);
    }

    return $actions;

}


// For WordPress core sitemaps (WP 5.5+)
add_filter( 'wp_sitemaps_post_types', 'cf_exclude_template_parts_from_sitemap' );
function cf_exclude_template_parts_from_sitemap( $post_types ) {

    unset($post_types['content_area']);
    return $post_types;

}


// Change the placeholder text for the title field
add_filter( 'enter_title_here', 'cf_change_placeholder_title_text' );
function cf_change_placeholder_title_text( $title ){

	$screen = get_current_screen();

	if( isset( $screen->post_type ) ) {

		if  ( 'content_area' == $screen->post_type ) {
         /* translators: title placeholder for post in the content area post type */
			$title = esc_html__( 'Add content area name', 'core-functionality' );
		}

	}

	return $title;

}


// Hide Categories from the content area post type admin menu
add_action( 'admin_menu', 'cf_remove_content_area_taxonomy_menu' );
function cf_remove_content_area_taxonomy_menu() {

    // Hide the Categories submenu for content_area post type
    global $submenu;
    if ( isset( $submenu['edit.php?post_type=content_area'] ) ) {

        foreach ( $submenu['edit.php?post_type=content_area'] as $key => $menu_item ) {

            if ( strpos( $menu_item[2], 'taxonomy=category' ) !== false ) {
                unset( $submenu['edit.php?post_type=content_area'][$key] );
            }

        }

    }

}