<?php
/**
 * GravityForms
 *
 * @package fse-starter
 **/


 // Remove required message
 add_filter( 'gform_required_legend', '__return_empty_string' );


/**
 * Change the button text for Gravity Forms to match the block editor
 */
add_filter( 'gform_submit_button', 'fse_add_custom_css_classes_to_submit', 10, 2 );
function fse_add_custom_css_classes_to_submit( $button, $form ) {

    $tag_processor = new WP_HTML_Tag_Processor( $button );

    if ( $tag_processor->next_tag( array( 'tag_name' => 'input', 'class_name' => 'gform_button button' ) ) ) {
        $tag_processor->add_class( 'wp-block-button__link' );
        $tag_processor->add_class( 'wp-element-button' );

        return $tag_processor->get_updated_html();
    }

    return $button;

}