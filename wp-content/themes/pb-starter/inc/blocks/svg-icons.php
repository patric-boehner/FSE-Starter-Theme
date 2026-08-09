<?php
/**
 * Block SVG Icon Replacements
 *
 * Replaces default WordPress block SVGs with custom icons.
 * Uses cf_icon() from core-functionality plugin for icon loading.
 *
 * Icons are stored in: /build/svg/blocks/
 *
 * @package pb-starter
 */


/**
 * Define block SVG replacements
 *
 * Central configuration for which SVGs to replace in which blocks.
 * Icons are loaded from /build/svg/blocks/ via cf_icon().
 *
 * Format: 'block_name' => array of replacements
 * Each replacement: array( 'class' => 'target-class', 'icon' => 'icon-name' )
 *
 * @return array Block replacement configuration.
 */
function fse_get_block_svg_replacements() {
	return array(
		'core/navigation' => array(
			array(
				'class' => 'wp-block-navigation__responsive-container-open',
				'icon'  => 'menu',
			),
			array(
				'class' => 'wp-block-navigation__responsive-container-close',
				'icon'  => 'close',
			),
			// array(
			// 	'class' => 'wp-block-navigation-submenu__toggle',
			// 	'icon'  => 'chevron-down',
			// ),
		),
		'core/query-pagination-previous' => array(
			array(
				'class' => 'wp-block-query-pagination-previous',
				'icon'  => 'arrow-left',
			),
		),
		'core/query-pagination-next' => array(
			array(
				'class' => 'wp-block-query-pagination-next',
				'icon'  => 'arrow-right',
			),
		),
		'core/post-navigation-link' => array(
			array(
				'class' => 'post-navigation-link-previous',
				'icon'  => 'arrow-left',
			),
			array(
				'class' => 'post-navigation-link-next',
				'icon'  => 'arrow-right',
			),
		),
		// 'core/search' => array(
		// 	array(
		// 		'class' => 'wp-block-search__button',
		// 		'icon'  => 'search',
		// 	),
		// ),
	);
}


/**
 * Replace SVG in block content
 *
 * Finds SVG elements within a CSS selector context and replaces them.
 *
 * @param string $content    Block HTML content.
 * @param string $class      CSS class to target (the parent element containing the SVG).
 * @param string $icon_name  Icon name (filename without .svg extension).
 * @return string Modified content.
 */
function fse_replace_block_svg( $content, $class, $icon_name ) {

	// Use cf_icon() from core-functionality plugin if available
	if ( function_exists( 'cf_icon' ) ) {
		$icon = cf_icon( array(
			'icon'  => $icon_name,
			'group' => 'utility',
			'force' => true,
		) );
	} else {
		// Fallback: load directly from theme
		$icon_path = get_theme_file_path( '/build/svg/blocks/' . $icon_name . '.svg' );
		if ( ! file_exists( $icon_path ) ) {
			return $content;
		}
		$icon = file_get_contents( $icon_path );
	}

	if ( empty( $icon ) ) {
		return $content;
	}

	// Pattern: Find elements with the target class, then replace their SVG
	$pattern = '/(<[^>]*class="[^"]*' . preg_quote( $class, '/' ) . '[^"]*"[^>]*>)(\s*)(<svg[^>]*>.*?<\/svg>)/s';

	return preg_replace( $pattern, '$1$2' . $icon, $content );
}


/**
 * Replace block SVGs via render_block filter
 *
 * Single filter that handles all SVG replacements based on configuration.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content.
 */
add_filter( 'render_block', 'fse_replace_block_svgs', 10, 2 );
function fse_replace_block_svgs( $block_content, $block ) {
	$replacements = fse_get_block_svg_replacements();

	// Check if this block has any SVG replacements configured
	if ( ! isset( $replacements[ $block['blockName'] ] ) ) {
		return $block_content;
	}

	// Apply each replacement for this block
	foreach ( $replacements[ $block['blockName'] ] as $replacement ) {
		$block_content = fse_replace_block_svg(
			$block_content,
			$replacement['class'],
			$replacement['icon']
		);
	}

	return $block_content;
}
