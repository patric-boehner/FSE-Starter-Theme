<?php
/**
 * Block template for: acf/block-area
 *
 * Dynamically pulls in a "content_area" post based on the selected content area location.
 */

// Get the location from ACF field
$location_term_id = get_field('block_area_id');
$term = $location_term_id ? get_term($location_term_id, 'block_area_location') : null;
$term_slug = $term ? $term->slug : '';

// Dynamic block ID
$block_id = 'wp-block-area-' . $location_term_id;
if ( isset( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Classes
$class_name = 'wp-block-area';
if ( $term_slug ) {
    $class_name .= ' wp-block-area-' . $term_slug;
}
if ( !empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// Check if we should show admin placeholder
$show_admin_placeholder = is_admin() && empty($location_term_id);

// Get rendered content if not showing placeholder
$rendered_content = null;
if (!$show_admin_placeholder) {
    $rendered_content = get_rendered_content_area($location_term_id);
}

// Output template part
include CORE_DIR . 'inc/pluggable/content-areas/block/template.php';