<?php
/**
 * WordPress Editor Customizations
 *
 * @package fse-starter
 **/


// Disable the block directory in the editor
remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');



// add_filter( 'allowed_block_types_all', 'fse_control_block_types', 12, 2 );
function fse_control_block_types( $allowed_blocks, $block_editor_context ) {

    // Only apply to posts and pages
    if ( ! isset( $block_editor_context->post ) ) {
        return $allowed_blocks;
    }

    $post_type = $block_editor_context->post->post_type;

    if ( ! in_array( $post_type, [ 'post', 'page' ], true ) ) {
        return $allowed_blocks;
    }

    // Define categories or blocks to exclude
    $excluded_categories = [ 'theme', 'widgets' ];
    $deny_list = [
        'core/code',
        'core/preformatted',
        'core/verse',
        'core/classic',
        'core/audio',
        'core/file',
        'core/media-text',
        'core/rss',
        'core/tag-cloud',
        // Add more if needed
    ];

    $all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

    $filtered = array_filter( $all_blocks, function( $block, $name ) use ( $excluded_categories, $deny_list ) {
        if ( in_array( $name, $deny_list, true ) ) {
            return false;
        }
        if ( ! isset( $block->category ) ) {
            return false;
        }
        if ( in_array( $block->category, $excluded_categories, true ) ) {
            return false;
        }
        return true;
    }, ARRAY_FILTER_USE_BOTH );

    return array_keys( $filtered );
}


/**
 * Get the allowed blocks using a dey list
 *
 * @return string[] Array of block slugs that are allowed in the editor.
 */
// function fse_deny_list_blocks() {

//     $blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

//     // Text
//     unset( $blocks['core/code'] );
//     unset( $blocks['core/preformatted'] );
//     unset( $blocks['core/pullquote'] );
//     unset( $blocks['core/verse'] );
//     unset( $blocks['core/classic'] );

//     // Media
//     unset( $blocks['core/audio'] );
//     unset( $blocks['core/file'] );
//     unset( $blocks['core/video'] );
//     unset( $blocks['core/media-text'] );

//     // Design
//     unset( $blocks['core/more'] );
//     unset( $blocks['core/nextpage'] );
//     unset( $blocks['core/spacer'] );

//     // Widgets
//     unset( $blocks['core/archives'] );
//     unset( $blocks['core/calendar'] );
//     unset( $blocks['core/categories'] );
//     unset( $blocks['core/latest-posts'] );
//     unset( $blocks['core/latest-comments'] );
//     unset( $blocks['core/page-list'] );
//     unset( $blocks['core/rss'] );
//     unset( $blocks['core/shortcode'] );
//     unset( $blocks['core/tag-cloud'] );

//     // Embeds
//     // unset( $blocks['core/embed'] );

//     return array_keys( $blocks );

// }


/**
 * Filter to allow only specific block types in the editor.
 * 
 * @param array $allowed_blocks The allowed blocks.
 * @param WP_Block_Editor_Context $block_editor_context The block editor context.
 * @return array The filtered allowed blocks.
 */
// add_filter( 'allowed_block_types_all', 'fse_allowed_block_types', 10, 2 );
// function fse_allowed_block_types( $allowed_blocks, $block_editor_context ) {

//     return fse_deny_list_blocks();

// }


/**
 * Filter to control block types in the editor.
 * This function allows only blocks that are not in the 'theme' or 'widgets' categories.
 * @param array $allowed_blocks The allowed blocks.
 * @param WP_Block_Editor_Context $block_editor_context The block editor context.
 * @return array The filtered allowed blocks.
 * 
 */
// add_filter( 'allowed_block_types_all', 'fse_control_block_types', 12, 2 );
// function fse_control_block_types( $allowed_blocks, $block_editor_context ) {

//     if( $block_editor_context->post->post_type !== 'post' && $block_editor_context->post->post_type !== 'page' ) {
//         return $allowed_blocks;
//     }

//     $allowed_blocks = array_filter(
//         WP_Block_Type_Registry::get_instance()->get_all_registered(),
//         function( $block) {
//             return $block->category !== 'theme' && $block->category !== 'widgets';
//         }
//     );

//     return array_keys( $allowed_blocks );

// }





// Disable Openverse in the block editor
add_filter('block_editor_settings_all', 'pb_disable_openverse', 10, 2);
function pb_disable_openverse( $editor_settings, $block_editor_context ) {

    $editor_settings['enableOpenverseMediaCategory'] = false;

    return $editor_settings;

}