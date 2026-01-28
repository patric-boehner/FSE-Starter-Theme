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
          'capabilities'        => array(
            'edit_post'          => 'manage_options',
            'read_post'          => 'manage_options',
            'delete_post'        => 'manage_options',
            'edit_posts'         => 'manage_options',
            'edit_others_posts'  => 'manage_options',
            'delete_posts'       => 'manage_options',
            'publish_posts'      => 'manage_options',
            'read_private_posts' => 'manage_options'
         )
       );

       register_post_type( 'content_area', $args );

    }
}


add_action( 'init', 'register_block_area_location_taxonomy' );
function register_block_area_location_taxonomy() {
	register_taxonomy(
		'block_area_location',
		'content_area',
		array(
			'labels' => array(
				'name'                       => __( 'Locations', 'core-functionality' ),
				'singular_name'              => __( 'Location', 'core-functionality' ),
				'search_items'               => __( 'Search Locations', 'core-functionality' ),
				'all_items'                  => __( 'All Locations', 'core-functionality' ),
				'parent_item'                => __( 'Parent Location', 'core-functionality' ),
				'parent_item_colon'          => __( 'Parent Location:', 'core-functionality' ),
				'edit_item'                  => __( 'Edit Location', 'core-functionality' ),
				'update_item'                => __( 'Update Location', 'core-functionality' ),
				'add_new_item'               => __( 'Add New Location', 'core-functionality' ),
				'new_item_name'              => __( 'New Location Name', 'core-functionality' ),
				'menu_name'                  => __( 'Locations', 'core-functionality' ),
				'not_found'                  => __( 'No Locations found', 'core-functionality' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
         'show_in_menu'      => false,
			'show_in_rest'      => true,
			'public'            => false,
			'rewrite'           => array( 'slug' => 'block-area' ),
         'sort'              => true,
		)
	);
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


// Make the block_area_location taxonomy column sortable
add_filter( 'manage_edit-content_area_sortable_columns', 'cf_make_location_column_sortable' );
function cf_make_location_column_sortable( $columns ) {
    $columns['taxonomy-block_area_location'] = 'taxonomy-block_area_location';
    return $columns;
}


// Handle the sorting when clicked
add_action( 'pre_get_posts', 'cf_handle_location_column_sorting' );
function cf_handle_location_column_sorting( $query ) {

    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading query param for display purposes only
    $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';
    if ( 'content_area' !== $post_type ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'taxonomy-block_area_location' === $orderby ) {
        $query->set( 'orderby', 'title' );
        $query->set( 'meta_key', '' );
    }

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