<?php
/**
 * Filter the Excerpt Block
 *
 * @package fse-starter
 **/


// Track if the current post title is linked (simple boolean flag)
add_filter( 'render_block', 'fse_track_linked_post_title_simple_flag', 9, 2 );
function fse_track_linked_post_title_simple_flag( $block_content, $block ) {

    // Reset flag at the start of each post template block
    if ( $block['blockName'] === 'core/post-template' ) {
        $GLOBALS['_excerpt_title_is_linked'] = false;
    }

    // Set flag if post-title block has isLink true
    if ( $block['blockName'] === 'core/post-title' && ! empty( $block['attrs']['isLink'] ) ) {
        $GLOBALS['_excerpt_title_is_linked'] = true;
    }

    return $block_content;
    
}


/**
 * Should we add screen reader text to the excerpt Read More link?
 * returns true if the post title is not linked.
 */
function should_add_excerpt_screen_reader_text() {
    return empty( $GLOBALS['_excerpt_title_is_linked'] );
}

/**
 * Add aria-hidden="true" and tabindex="-1" to the first link in the Post Excerpt block
 * if the post title is already linked.
 *
 * This ensures that screen readers ignore the "Read More" link
 * when the post title is already linked.
 *
 * @param string $block_content The content of the block.
 * @param array  $block The block attributes.
 * @return string Modified block content with aria-hidden attribute.
 */
add_filter( 'render_block', 'fse_add_aria_hidden_to_excerpt_link', 10, 2 );
function fse_add_aria_hidden_to_excerpt_link( $block_content, $block ) {

    if ( $block['blockName'] !== 'core/post-excerpt' ) {
        return $block_content;
    }

    // If the post title is already linked, adding screen reader text
    if ( should_add_excerpt_screen_reader_text() ) {
        return $block_content;
    }

    $processor = new WP_HTML_Tag_Processor( $block_content );

    // Look for the first <a> inside the excerpt
    if ( $processor->next_tag( 'a' ) ) {
        $processor->set_attribute( 'aria-hidden', 'true' );
        $processor->set_attribute( 'tabindex', '-1' );
        return $processor->get_updated_html();
    }

    return $block_content;

}

/**
 * Add screen reader text to the "Read More" link in the Post Excerpt block
 * if the post title is not already linked.
 *
 * This ensures that the "Read More" link is accessible for screen readers.
 *
 * @param string $block_content The content of the block.
 * @param array  $block The block attributes.
 * @return string Modified block content with accessible "Read More" link.
 */
add_filter( 'render_block', 'fse_make_excerpt_read_more_accessible', 10, 2 );
function fse_make_excerpt_read_more_accessible( $block_content, $block ) {

    if ( $block['blockName'] !== 'core/post-excerpt' ) {
        return $block_content;
    }

    // Use the current post in the loop
    $post_id = get_the_ID();

    if( ! $post_id ) {
        return $block_content; // Return early if no post ID is found
    }

    // If the post title is already linked, skip adding screen reader text
    if ( ! should_add_excerpt_screen_reader_text() ) {
        return $block_content;
    }

    // Get the post title and permalink
    $post_title = get_the_title( $post_id );
    $read_more_link = get_permalink( $post_id );

    // Replace default "Read more" link with accessible version
    $screen_reader_text = sprintf(
        '<span class="screen-reader-text">: %s</span>',
        esc_html( $post_title )
    );

    // Insert just before the closing </a>
    $block_content = str_replace(
        '</a>',
        $screen_reader_text . '</a>',
        $block_content
    );

    return $block_content;

}
