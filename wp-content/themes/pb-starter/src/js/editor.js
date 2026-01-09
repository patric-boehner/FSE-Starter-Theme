/**
 * Block Editor Customizations
 * 
 * Handles:
 * - Hiding blocks
 * - Unregistering block styles
 * - Registering custom block styles
 * - Unregistering block variations (e.g., stretchy text)
 */

wp.domReady(() => {
    
    // Get configuration from PHP
    const { hiddenStyles, registerStyles, hiddenBlocks, unregisteredVariations } = myEditorOptions;

    // Unregister block variations (e.g., Stretchy Text in WordPress 6.9+)
    if (unregisteredVariations) {
        Object.keys(unregisteredVariations).forEach(blockName => {
            unregisteredVariations[blockName].forEach(variationName => {
                wp.blocks.unregisterBlockVariation(blockName, variationName);
            });
        });
    }

    // Hide blocks
    if (hiddenBlocks && hiddenBlocks.length > 0) {
        hiddenBlocks.forEach(blockName => {
            const block = wp.blocks.getBlockType(blockName);
            if (block) {
                wp.blocks.unregisterBlockType(blockName);
            }
        });
    }

    // Unregister block styles
    if (hiddenStyles) {
        Object.keys(hiddenStyles).forEach(blockName => {
            hiddenStyles[blockName].forEach(styleName => {
                wp.blocks.unregisterBlockStyle(blockName, styleName);
            });
        });
    }

    // Register custom block styles
    if (registerStyles) {
        Object.keys(registerStyles).forEach(blockName => {
            registerStyles[blockName].forEach(style => {
                wp.blocks.registerBlockStyle(blockName, style);
            });
        });
    }

});