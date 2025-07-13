<?php
/**
* Block Data
*
* @package    CoreFunctionality
* @since      2.0.0
* @copyright  Copyright (c) 2019, Patrick Boehner
* @license    GPL-2.0+
*
* @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/ 
* @link https://www.billerickson.net/building-acf-blocks-with-block-json/
* @link https://www.modernwpdev.co/acf-blocks/
*/

// If this file is called directly, abort.
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// $data is what we're going to expose to our render template
$data = array(
	'heading' => get_field( 'heading' ),
    'open_state' => get_field( 'default_open' ),
    'heading_level' => $context['acf/fields']['heading_level'] ? $context['acf/fields']['heading_level'] : 'h3',
    'closed_state'   => ( ! $is_preview ) ? 'toggle-hidden' : '',
);

// Support custom "anchor" values.
if ( ! empty( $block['anchor'] ) ) {
    $anchor = esc_attr( $block['anchor'] );
} else {
    $anchor = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 6);
}

// Button attribute
$button_attr = sprintf(
    'id="toggle__button-%s" data-id="%s" class="toggle__button" aria-controls="toggle__content-%s" aria-expanded="%s"',
    esc_attr( $anchor ),
    esc_attr( $anchor ),
    esc_attr( $anchor ),
    esc_attr( $data['open_state'] == 'yes' ? 'true' : 'false' )
);

$content_attr = sprintf(
    'id="toggle__content-%s"  class="toggle-item__content %s" aria-labelledby="toggle__button-%s"',
    esc_attr( $anchor ),
    esc_attr( $data['open_state'] == 'yes' ? '' : $data['closed_state'] ),
    esc_attr( $anchor )
);

$inner_blocks_template = array(
	array(
        'core/paragraph',
        array(),
        array()
    ),
);

// Setup SVG
$icon_svg = '';

// Icons
if ( function_exists( 'cf_icon' ) ) {
    $icon_svg = cf_icon( array(
        'icon' => 'chevron-right',
        'group' => 'utility',
        'class' => 'button_icon',
        'size' => false,
    ) );
}


// Output template part
include CORE_DIR . 'inc/pluggable/toggle-item-block/block/template.php';
