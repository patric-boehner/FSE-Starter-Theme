<?php
/**
* Render Icon Block
* 
* @link https://www.billerickson.net/building-acf-blocks-with-block-json/
* @link https://www.modernwpdev.co/acf-blocks/registering-blocks/#render-template
*/


// If this file is called directly, abort.
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// Dynamic block ID
$block_id = '';
if ( isset( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Initialize classes array
$classes_outter = ['wp-block-icon'];
$classes_inner = ['icon-container'];

// Add alignment classes
// if ( ! empty( $block['align'] ) ) {
//     $classes_outter[] = 'items-justified-' . $block['align'];
// }

// Check to see if a custom class has been added
if ( ! empty( $block['className'] ) ) {
    $classes_outter = array_merge( $classes_outter, explode( ' ', $block['className'] ) );
}

// Join the classes together into one variable
$block_classes_outter = esc_attr( join( ' ', $classes_outter ) );

// Class names for text colors
if ( ! empty( $block['textColor'] ) ) {
    $classes_inner[] = 'has-icon-color';
    $classes_inner[] = 'has-' . $block['textColor'] . '-color';
}

// Class names for background colors
if ( ! empty( $block['backgroundColor'] ) ) {
    $classes_inner[] = 'has-icon-background-color';
    $classes_inner[] = 'has-' . $block['backgroundColor'] . '-background-color';
}

// Data is what we're going to expose to our render template
$icon_select = get_field( 'icon_select' );
$icon_size = get_field( 'icon_size' );
$icon_label = get_field( 'icon_label' );

// Set default icon size if none selected
if ( empty( $icon_size ) ) {
    $icon_size = 'medium';
}


// Setup icon data
$icon_group = '';
$icon_name = '';

// Handle the icon selection
if ( !empty( $icon_select ) && is_string( $icon_select ) ) {
    // Check if it's the new format (directory/icon) or old format (just icon)
    if ( strpos( $icon_select, '/' ) !== false ) {
        // New format: "utility/search" or "decorative/star"
        $icon_parts = explode('/', $icon_select);
        $icon_group = $icon_parts[0];
        $icon_name = $icon_parts[1];
    } else {
        // Old format: just "search" - default to decorative for backward compatibility
        $icon_group = 'decorative';
        $icon_name = $icon_select;
    }
}

$data = array(
    'icon_select' => $icon_select, // Keep the original value for debugging
    'icon_name' => $icon_name, // Individual icon name
    'icon_group' => $icon_group, // Individual group name
    'icon_size' => $icon_size,
    'icon_lable' => $icon_label,
);

// Icon Size Class
if( ! empty( $data['icon_size'] ) ) {
    $classes_inner[] = 'icon-size-' . $data['icon_size'];
}

// Join the classes together into one variable
$block_classes_inner = esc_attr( join( ' ', $classes_inner ) );

// Setup SVG
$icon_svg = '';

// Icons
if ( function_exists( 'cf_icon' ) && !empty( $data['icon_name'] ) && !empty( $data['icon_group'] ) ) {
    $icon_svg = cf_icon( array(
        'icon' => $data['icon_name'],
        'group' => $data['icon_group'],
        'size' => false,
        'class' => 'icon',
        'label' => $data['icon_lable'],
    ) );
}

// Output template part
include CORE_DIR . 'inc/pluggable/icon-block/block/template.php';