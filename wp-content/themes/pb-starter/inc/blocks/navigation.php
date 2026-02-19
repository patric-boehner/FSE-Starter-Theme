<?php
/**
 * Filter the Navigation Block
 *
 * @package fse-starter
 **/


/**
 * Add text to mobile menu
 * 
 * @link https://fullsiteediting.com/blocks/navigation/
 */
add_filter( 'render_block_core/navigation', 'fse_modify_navigation_block', 10, 2 );
function fse_modify_navigation_block( $block_content, $block) {

    // Check if it's a navigation block and if it's the mobile view.
	if ( strpos( $block_content, 'wp-block-navigation__responsive-container-open' ) !== false ) {

		// Menu Change
		$menu_text = esc_html__( 'Menu', 'pb-starter' );

        // Modify the content to add "menu" to the mobile button text.
        $updated_content = str_replace( '</svg></button>', '</svg><span class="navigation-button-text">' .$menu_text. '</span></button>', $block_content );

        // Return the modified content.
        return $updated_content;

	}
	
    // Return content as normal if not true
    return $block_content;

}