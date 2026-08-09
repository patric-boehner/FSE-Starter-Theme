<?php
/**
 * Dependency manifest for edit.js. Hand-authored - @wordpress/scripts would
 * generate this, but a build step costs more than it saves for one small file.
 *
 * @package CoreFunctionality
 */

return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-element',
		'wp-block-editor',
		'wp-components',
		'wp-server-side-render',
		'wp-data',
		'wp-i18n',
		'wp-api-fetch',
	),
	// From the file itself, so editing edit.js always busts the browser cache.
	'version'      => (string) filemtime( __DIR__ . '/edit.js' ),
);
