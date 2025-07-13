<?php

/**
 * Customize ACF functions
 */


//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// Set custom ACF JSON save/load location
add_filter('acf/settings/save_json', 'cf_selective_acf_save');
add_filter('acf/settings/load_json', 'cf_acf_json_load_point');

function cf_selective_acf_save($path) {
    if (isset($_POST['acf_field_group'])) {
        $field_group = $_POST['acf_field_group'];
        
        // Define your plugin's field groups by title
        $plugin_field_groups = [
            'Block Area Block Settings',
            'Icon Block Settings',
            'Toggles Block Settings',
            'Toggle Item Block Settings'
        ];
        
        // Check if current field group belongs to your plugin
        if (in_array($field_group['title'], $plugin_field_groups)) {
            return CORE_DIR . 'inc/acf-json';
        }
    }
    
    return $path;
}

function cf_acf_json_load_point($paths) {
    $paths[] = CORE_DIR . 'inc/acf-json';
    return $paths;
}

// Post archive theme settings page
// if( function_exists('acf_add_options_page') ) {
 
// 	acf_add_options_sub_page(array(
// 		'page_title' 	=> 'Posts Archive Settings',
// 		'menu_title'	=> 'Archive Settings',
// 		'parent_slug'	=> 'edit.php',
// 	));
	
// }


// Block Catoegires
// add_filter( 'block_categories_all', 'cf_plugin_block_categories_all', 10, 2 );
// function  cf_plugin_block_categories_all( $categories, $post ) {
// //  if ( $post->post_type !== array( 'post', 'block_area', 'page', 'presenters' )  ) {
// //      return $categories;
// //  }
//     return array_merge(
//         $categories,
//         array(
//             array(
//                 'slug' => 'content',
//                 'title' => __( 'Content', 'core-functionality' ),
//             ),
//             array(
//             'slug' => 'donations',
//             'title' => __( 'Donations', 'core-functionality' ),
//         ),
//         )
//     );
    
// }