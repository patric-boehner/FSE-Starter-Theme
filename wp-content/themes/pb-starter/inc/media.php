<?php
/**
 * WordPress Media Customizations
 *
 * @package fse-starter
 **/

// Set medium size to theme.json content width
add_filter('pre_option_medium_size_w', 'fse_set_medium_image_size' );
add_filter('pre_option_medium_size_h', 'fse_set_medium_image_size' );
function fse_set_medium_image_size() {
     return 800; 
}

// Set large size to theme.json wide with
add_filter('pre_option_large_size_w', 'fse_set_large_image_size' );
add_filter('pre_option_large_size_h', 'fse_set_large_image_size' );
function fse_set_large_image_size() {
     return 1200;
}