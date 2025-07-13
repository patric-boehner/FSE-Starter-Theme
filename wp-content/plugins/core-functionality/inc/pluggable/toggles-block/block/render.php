<?php
/**
* Block Data
*
* @package    CoreFunctionality
* @since      2.0.0
* @copyright  Copyright (c) 2019, Patrick Boehner
* @license    GPL-2.0+
*
* @param array  $block The block settings and attributes.
* @param string $content The block inner HTML (empty).
* @param bool   $is_preview True during backend preview render.
* @param int    $post_id The post ID the block is rendering content against.
*                     This is either the post ID currently being displayed inside a query loop,
*                     or the post ID of the post hosting this block.
* @param array $context The context provided to the block by the post or it's parent block.
* 
* Reference for accessible accordion: https://www.hassellinclusion.com/blog/accessible-accordion-pattern/
* Reference for parent/child ACF blocks: https://www.advancedcustomfields.com/resources/acf-blocks-using-innerblocks-and-parent-child-relationships/
* Reference for parent/child ACF values: https://www.advancedcustomfields.com/resources/using-context-with-acf-blocks/
*
* @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/ 
* @link https://www.modernwpdev.co/acf-blocks/
*/

// If this file is called directly, abort.
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// Support custom "anchor" values.
if ( ! empty( $block['anchor'] ) ) {
    $anchor = esc_attr( $block['anchor'] );
} else {
    $anchor = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 6);
} 

// Output template part
include CORE_DIR . 'inc/pluggable/toggles-block/block/template.php';
