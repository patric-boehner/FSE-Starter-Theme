<?php
/**
 * Create a query block varation for related posts
 *
 * @package    CoreFunctionality
 * @since      2.0.0
 * @copyright  Copyright (c) 2020, Patrick Boehner
 * @license    GPL-2.0+
 * 
 * @link https://wpfieldwork.com/modify-query-loop-block-to-filter-by-custom-field/
 * @link https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block
 */


 //* Block Acess
 //**********************
 if( !defined( 'ABSPATH' ) ) exit;


// Enqueue the block variation JavaScript
add_action( 'enqueue_block_editor_assets', 'wpfieldwork_editor_assets' );
function wpfieldwork_editor_assets() {
  wp_enqueue_script(
    'wpfieldwork-block-variations',
    CORE_URL . 'inc/pluggable/related-posts/related-posts.js',
    array( 'wp-blocks' )
  );
}


// Inject category filter and exclude current post in frontend query
add_filter('render_block_core/query', function ($block_content, $block) {
    if (($block['attrs']['name'] ?? '') === 'mytheme/related-posts') {
        if (is_single()) {
            $current_post_id = get_the_ID();
            $categories = get_the_category($current_post_id);
            if (!empty($categories)) {
                $main_category_id = $categories[0]->term_id;

                add_filter('pre_get_posts', function ($query) use ($main_category_id, $current_post_id) {
                    if (!is_admin() && $query->is_main_query()) {
                        $query->set('cat', $main_category_id);
                        $query->set('post__not_in', [$current_post_id]);
                    }
                }, 10, 1);
            }
        }
    }
    return $block_content;
}, 10, 2);



add_filter( 'pre_render_block', 'cf_related_posts_pre_render_block', 10, 2 );
function cf_related_posts_pre_render_block( $pre_render, $parsed_block ) {

    // Verify it's the block that should be modified using the namespace
    if ( !empty($parsed_block['attrs']['namespace']) && 'related-posts' === $parsed_block['attrs']['namespace'] ) {
        
        add_filter( 'query_loop_block_query_vars', function( $query, $block ) {

            // Get current post ID
            $post_id = get_the_ID();

            // Get the current post category
            $categories = get_the_category( $post_id );

            if ( ! empty( $categories ) ) {

                $query['tax_query'] = array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => array( $categories[0]->term_id ),
                    ),
                );

            }

            $query['post__not_in'] = array( $post_id );

            return $query;

        }, 10, 2 );
  }

  return $pre_render;

}


add_filter( 'rest_events_query', 'cf_rest_related_posts', 10, 2 );
function cf_rest_related_posts( $args, $request ) {

    // Only run if filterRelated is explicitly set
    if ( ! $request->get_param( 'filterRelated' ) ) {
        return $args;
    }

    $context = $request->get_param( 'context' );
    $current_post_id = isset( $context['postId'] ) ? (int) $context['postId'] : 0;

    // Fallback: show recent posts if no post context available
    if ( ! $current_post_id ) {
        return $args; // Leave default query intact (e.g., recent posts)
    }

    $categories = get_the_category( $current_post_id );

    if ( ! empty( $categories ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array( $categories[0]->term_id ),
            ),
        );
    }

    $args['post__not_in'] = array( $current_post_id );

    return $args;
}
