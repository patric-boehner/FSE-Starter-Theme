<?php
/**
 * WordPress Editor Customizations
 *
 * @package fse-starter
 **/


// Disable the block directory in the editor
remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');


// Disable Openverse in the block editor
add_filter('block_editor_settings_all', 'pb_disable_openverse', 10, 2);
function pb_disable_openverse( $editor_settings, $block_editor_context ) {

    $editor_settings['enableOpenverseMediaCategory'] = false;

    return $editor_settings;

}