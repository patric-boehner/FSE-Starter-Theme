<?php
/**
 * Feature: Content Areas
 *
 * Reusable, conditional content the client edits in the normal post editor,
 * without ever opening the Site Editor. Essentially the widget system rebuilt
 * on blocks.
 *
 * Two words, kept distinct:
 *   Slot          a named PLACE. A plain string: 'sidebar-cta', 'site-banner'.
 *   Content Area  a POST the client edits, which targets one slot.
 *
 * Files:
 *   slots.php     the slot registry (registration is optional - see the file)
 *   meta.php      _cf_slot (where) and _cf_post_types (a condition)
 *   context.php   what page are we on, answered once
 *   query.php     one query for every slot, cached
 *   resolver.php  which content area wins
 *   render.php    do_blocks() and the wrapper
 *   hooks.php     slots that render at a hook, with no block
 *   block.php     the cf/content-slot block
 *   admin/        authoring fields, list table, cache control
 *
 * Why a CPT and not template parts or synced patterns: core binds template
 * parts by slug at parse time and the Site Editor reads them over REST whatever
 * show_ui says; synced patterns are inserted by numeric ID and edited in the
 * Site Editor too. Neither can meet the one hard requirement - the client never
 * goes there.
 *
 * @package     Core Functionality
 * @subpackage  Pluggable Features
 * @author      Patrick Boehner
 * @copyright   Copyright (c) 2012-2026, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 */

/*
 * TODO: conditions are split across two panels. Taxonomy conditions live in the
 * core Categories metabox, post type conditions in the ACF panel, which also
 * holds the slot - mixing WHERE with WHEN. Fix by building the group in PHP with
 * acf_add_local_field_group(), one taxonomy field per cf_get_condition_taxonomies()
 * entry, save_terms/load_terms ON so real term relationships are still written
 * (that is what keeps the single-query priming working). Then remove_meta_box
 * 'categorydiv' and split into Placement / Display Conditions.
 *
 * TODO: this block could be PHP-only and drop edit.js entirely. WP 7.0, flag is
 * supports.autoRegister plus a render_callback; attributes of type string/number/
 * integer/boolean get controls automatically and `enum` becomes a dropdown. Not
 * taken because `enum` is a CLOSED list, so slots would stop being typeable -
 * the same wall the ACF select hit. Also loses the SVG icon and the post_id
 * preview context. Revisit together with making registration required; a fixed
 * slot list is exactly what `enum` wants.
 */


//* Block Access
//**********************
if ( ! defined( 'ABSPATH' ) ) exit;


// Ordered as a request reads: what exists, where it goes, what page we are on,
// what is available, which one wins, how it prints.
require_once CORE_DIR . 'inc/pluggable/content-areas/post-type.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/meta.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/slots.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/context.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/query.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/resolver.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/render.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/hooks.php';
require_once CORE_DIR . 'inc/pluggable/content-areas/block.php';

// Front end too - the admin bar node appears there.
require_once CORE_DIR . 'inc/pluggable/content-areas/admin/cache.php';

if ( is_admin() ) {
	require_once CORE_DIR . 'inc/pluggable/content-areas/admin/fields.php';
	require_once CORE_DIR . 'inc/pluggable/content-areas/admin/columns.php';
}


/**
 * Ask themes to declare their slots.
 *
 * Priority 1 so slots exist before the block registers at 5 and the theme's
 * block style loader runs at 10.
 */
add_action( 'init', 'cf_register_content_slots_action', 1 );
function cf_register_content_slots_action() {

	do_action( 'cf_register_content_slots' );

}
