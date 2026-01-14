<?php
/**
 * WordPress Comments
 *
 * @package fse-starter
 **/

 
// Remove comment Feed
add_filter( 'feed_links_show_comments_feed', '__return_false' );


// Remove comments from admin bar
// add_action( 'wp_before_admin_bar_render', 'fse_remove_wp_before_admin_bar_render' );
// function fse_remove_wp_before_admin_bar_render() {

// 	global $wp_admin_bar;
// 	$wp_admin_bar->remove_menu( 'comments' );

// }

// Remove comment menus
// add_action( 'admin_menu', 'fse_remove_admin_comment_menu' );
// function fse_remove_admin_comment_menu() {

// 	remove_menu_page( 'edit-comments.php' );
// 	remove_submenu_page( 'options-general.php', 'options-discussion.php' );

// }


// Remove page comments
// add_action( 'init', 'fse_disable_post_type_comments' );
// function fse_disable_post_type_comments() {
    
//  remove_post_type_support( 'post', 'comments' );
// 	remove_post_type_support( 'page', 'comments' );
// 	remove_post_type_support( 'attachment', 'comments' );

// }
