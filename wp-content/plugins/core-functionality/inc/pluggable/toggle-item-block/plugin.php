<?php
/**
 * Register the Toggle Item Block
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


add_action( 'init', 'cf_register_toggle_item_block', 5 );
function cf_register_toggle_item_block() {

	// Check availability of block editor
    if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// adjust location(s) as needed for block.json
  	register_block_type( CORE_DIR . 'inc/pluggable/toggle-item-block/block' );

}
