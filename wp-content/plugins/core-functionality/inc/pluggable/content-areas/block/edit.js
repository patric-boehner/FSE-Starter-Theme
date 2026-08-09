/**
 * Content Slot block - editor
 *
 * Plain wp.element calls, no JSX, no build step.
 *
 * The slot field is a quick-pick of known slugs plus free text: any slug works
 * whether or not it was registered, so the select is a convenience, not a gate.
 */
( function ( blocks, element, blockEditor, components, ServerSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var Fragment = element.Fragment;
	var useState = element.useState;
	var __ = i18n.__;

	var CUSTOM = '__custom__';
	var known = window.cfContentSlots || [];

	/**
	 * Here rather than in block.json because that only accepts a Dashicon slug -
	 * an inline SVG there renders nothing. block.json keeps a dashicon fallback.
	 */
	var icon = el(
		'svg',
		{
			xmlns: 'http://www.w3.org/2000/svg',
			viewBox: '0 0 24 24',
			width: 24,
			height: 24,
			'aria-hidden': true,
			focusable: 'false',
		},
		el( 'path', {
			d:
				'M21.3 10.8l-5.6-5.6c-.7-.7-1.8-.7-2.5 0l-5.6 5.6c-.7.7-.7 1.8 0 2.5l5.6 5.6c.3.3.8.5 1.2.5s.9-.2 1.2-.5l5.6-5.6c.8-.7.8-1.9.1-2.5zm-17.6 1L10 5.5l-1-1-6.3 6.3c-.7.7-.7 1.8 0 2.5L9 19.5l1.1-1.1-6.3-6.3c-.2 0-.2-.2-.1-.3z',
		} )
	);

	function isKnownSlot( slug ) {
		return known.some( function ( option ) {
			return option.value === slug;
		} );
	}

	function SlotControls( props ) {
		var slot = props.attributes.slot || '';

		// An unrecognised value means a custom slug, or one renamed in code.
		// Show it rather than silently resetting.
		var startCustom = slot !== '' && ! isKnownSlot( slot );
		var custom = useState( startCustom );
		var isCustom = custom[ 0 ];
		var setCustom = custom[ 1 ];

		var options = [ { value: '', label: __( '— Select a slot —', 'core-functionality' ) } ]
			.concat( known )
			.concat( [ { value: CUSTOM, label: __( 'Custom…', 'core-functionality' ) } ] );

		function onSelect( value ) {
			if ( value === CUSTOM ) {
				setCustom( true );
				return;
			}
			setCustom( false );
			props.setAttributes( { slot: value } );
		}

		return el(
			components.PanelBody,
			{ title: __( 'Content Slot', 'core-functionality' ) },
			el( components.SelectControl, {
				label: __( 'Slot', 'core-functionality' ),
				value: isCustom ? CUSTOM : slot,
				options: options,
				onChange: onSelect,
				__nextHasNoMarginBottom: true,
			} ),
			isCustom &&
				el( components.TextControl, {
					label: __( 'Slot name', 'core-functionality' ),
					value: slot,
					help: __(
						'Any name works. It does not need to be registered first.',
						'core-functionality'
					),
					onChange: function ( value ) {
						props.setAttributes( { slot: value } );
					},
					__nextHasNoMarginBottom: true,
				} )
		);
	}

	blocks.registerBlockType( 'cf/content-slot', {
		icon: icon,

		edit: function ( props ) {
			var blockProps = blockEditor.useBlockProps();

			// Send the post being edited so the preview resolves like a real page.
			// Numbers only: in the Site Editor getCurrentPostId() returns a
			// template string like "pb-starter//header", and the block-renderer
			// route types post_id as an integer, so sending that fails with
			// "Invalid parameter(s): post_id". A template has no post context
			// anyway, so omitting it is correct.
			var editor = wp.data.select( 'core/editor' );
			var currentId = editor ? editor.getCurrentPostId() : 0;
			var postId = typeof currentId === 'number' && currentId > 0 ? currentId : 0;

			return el(
				Fragment,
				null,
				el( blockEditor.InspectorControls, null, el( SlotControls, props ) ),
				el(
					'div',
					blockProps,
					el( ServerSideRender, {
						block: 'cf/content-slot',
						attributes: props.attributes,
						urlQueryArgs: postId ? { post_id: postId } : {},
					} )
				)
			);
		},

		save: function () {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.i18n
);
