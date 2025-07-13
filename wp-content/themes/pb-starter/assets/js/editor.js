wp.domReady(() => {
	// Unregister block styles
	if (window.myEditorOptions?.hiddenStyles) {
		Object.entries(myEditorOptions.hiddenStyles).forEach(([blockName, styles]) => {
			styles.forEach((style) => {
				wp.blocks.unregisterBlockStyle(blockName, style);
			});
		});
	}

	// Register custom block styles
	if (window.myEditorOptions?.registerStyles) {
		Object.entries(myEditorOptions.registerStyles).forEach(([blockName, styles]) => {
			styles.forEach((style) => {
				wp.blocks.registerBlockStyle(blockName, style);
			});
		});
	}
});