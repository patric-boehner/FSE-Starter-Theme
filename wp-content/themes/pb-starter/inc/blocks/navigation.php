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


/**
 * Change Subtoggle Navigation Icons
 */
add_filter( 'render_block_core/navigation', 'fse_modify_block_core_navigation_subtoggle_svg', 11, 2 );
function fse_modify_block_core_navigation_subtoggle_svg ( $block_content, $block ) {

	$tag_processor = new WP_HTML_Tag_Processor( $block_content );
	
	// Find the Main navigation block based on aria lable
	if ( $tag_processor->next_tag( array( 'tag_name' => 'nav', 'Main Navigation' === 'get_attribute' => 'aria-label' ) ) ) {

		// Set Submenue Toggle SVG
		while( $tag_processor->next_tag( array( 'tag_name' => 'button', 'class_name' => 'wp-block-navigation-submenu__toggle' ) ) ) {

			// Update SVG Attributes
			if ( $tag_processor->next_tag( 'svg' ) ) {
				$tag_processor->set_attribute( 'width', '8' );
				$tag_processor->set_attribute( 'height', '12' );
				$tag_processor->set_attribute( 'viewbox', '0 0 8 12' );
				$tag_processor->remove_attribute( 'fill' ); // Remove fill none attribute
			}

			// Update SVG path & Stroke
			if ( $tag_processor->next_tag( 'path' ) ) {
				$tag_processor->set_attribute( 'd', 'M3.22 8.78c0.293 0.293 0.769 0.293 1.062 0l3-3c0.216-0.216 0.279-0.537 0.162-0.818s-0.389-0.464-0.694-0.464l-6 0.002c-0.302 0-0.577 0.183-0.694 0.464s-0.052 0.602 0.162 0.818l3 3z' );
				$tag_processor->remove_attribute( 'stroke-width' ); // Remove stroke width attribute
			}

		}

		return $tag_processor->get_updated_html();
	
	}

	// Return content as normal if not true
	return $block_content;

}


/**
 * Change Menu Navigation Icons
 */
// add_filter( 'render_block_core/navigation', 'fse_modify_block_core_navigation_svg', 12, 2 );
function fse_modify_block_core_navigation_svg ( $block_content, $block ) {

	$tag_processor = new WP_HTML_Tag_Processor( $block_content );
	
	// Find the Main navigation block based on aria lable
	if ( $tag_processor->next_tag( array( 'tag_name' => 'nav', 'Main Navigation' === 'get_attribute' => 'aria-label' ) ) ) {

		// Set Open button SVG
		if ( $tag_processor->next_tag( array( 'tag_name' => 'button', 'class_name' => 'wp-block-navigation__responsive-container-open' ) ) ) {
			
			// Update SVG Attributes
			if ( $tag_processor->next_tag( 'svg' ) ) {
				$tag_processor->set_attribute( 'width', '21' );
				$tag_processor->set_attribute( 'height', '24' );
				$tag_processor->set_attribute( 'viewbox', '0 0 21 24' );
			}

			// Update SVG path
			if ( $tag_processor->next_tag( 'path' ) ) {
				$tag_processor->set_attribute( 'd', 'M0 4.5c0-0.83 0.67-1.5 1.5-1.5h18c0.83 0 1.5 0.67 1.5 1.5s-0.67 1.5-1.5 1.5h-18c-0.83 0-1.5-0.67-1.5-1.5zM0 12c0-0.83 0.67-1.5 1.5-1.5h18c0.83 0 1.5 0.67 1.5 1.5s-0.67 1.5-1.5 1.5h-18c-0.83 0-1.5-0.67-1.5-1.5zM21 19.5c0 0.83-0.67 1.5-1.5 1.5h-18c-0.83 0-1.5-0.67-1.5-1.5s0.67-1.5 1.5-1.5h18c0.83 0 1.5 0.67 1.5 1.5z' );
			}

		}

		// Set Close button SVG
		if ( $tag_processor->next_tag( array( 'tag_name' => 'button', 'class_name' => 'wp-block-navigation__responsive-container-close' ) ) ) {
			
			// Update SVG Attributes
			if ( $tag_processor->next_tag( 'svg' ) ) {
				$tag_processor->set_attribute( 'width', '18' );
				$tag_processor->set_attribute( 'height', '24' );
				$tag_processor->set_attribute( 'viewbox', '0 0 18 24' );
			}

			// Update SVG path
			if ( $tag_processor->next_tag( 'path' ) ) {
				$tag_processor->set_attribute( 'd', 'M16.059 7.059c0.586-0.586 0.586-1.537 0-2.123s-1.538-0.586-2.123 0l-4.936 4.941-4.941-4.936c-0.586-0.586-1.537-0.586-2.123 0s-0.586 1.537 0 2.123l4.941 4.936-4.936 4.941c-0.586 0.586-0.586 1.538 0 2.123s1.537 0.586 2.123 0l4.936-4.941 4.941 4.936c0.586 0.586 1.537 0.586 2.123 0s0.586-1.538 0-2.123l-4.941-4.936 4.936-4.941z' );
			}

		}

		return $tag_processor->get_updated_html();
	
	}

	// Return content as normal if not true
	return $block_content;

}