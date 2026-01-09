<?php
/**
 * Template tags used through the theme
 *
 * @package pb-starter
 * 
 * This file contains **only** pure functions that relate to templating.
 *
 * Rules:
 * - Functions in this file **must be pure** (i.e., they must not cause side effects).
 * - No hooks, filters, or global state modifications should be added here.
 * - If a function has side effects (e.g., enqueuing scripts, modifying post data, adding filters),
 *   it should be encapsulated within a class in the `src/` directory.
 *
 * A pure function:
 * - Given the same input, it always returns the same output.
 * - Does not modify external state (no global variables, no database writes, etc.).
 * - Does not rely on WordPress hooks or filters.
 */


// Generate the copyright information.
function fse_get_copyright() {

    $copyright_info = '&copy;' .  esc_attr( gmdate( 'Y' ) );
    $site_name = get_bloginfo( 'name' );
    $terms = __( 'All rights reserved', 'pb-starter' );

    return $copyright_info . ' - ' . $site_name . ' | ' . $terms;

}
