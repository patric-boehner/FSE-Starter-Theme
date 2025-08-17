<?php
/**
 * Wordpress Archive
 *
 * @package fse-starter
 **/


add_filter( 'get_the_archive_title', 'fes_taxonomy_archive_title' );
function fes_taxonomy_archive_title( $title ) {

    if ( is_category() || is_tag() || is_tax() ) {

        $term = get_queried_object();

        $custom_title = get_term_meta( $term->term_id, 'tax_archive_heading', true );

        if ( ! empty( $custom_title ) ) {
            return $custom_title;
        }

        return $term->name; // fallback if meta not set

    }

    return $title;

}