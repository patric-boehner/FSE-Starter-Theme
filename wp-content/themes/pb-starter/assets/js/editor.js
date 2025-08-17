wp.domReady(() => {
    
    if (!window.myEditorOptions) {
        console.error('myEditorOptions not found!');
        return;
    }
    
    const { hiddenStyles, registerStyles, hiddenBlocks } = window.myEditorOptions;
    
    // Unregister blocks
    if (hiddenBlocks && hiddenBlocks.length > 0) {
        hiddenBlocks.forEach(blockName => {
            wp.blocks.unregisterBlockType(blockName);
            // console.log(`Unregistered: ${blockName}`);
        });
    }
    
    // Unregister block styles
    if (hiddenStyles) {
        Object.entries(hiddenStyles).forEach(([blockName, styles]) => {
            styles.forEach((style) => {
                wp.blocks.unregisterBlockStyle(blockName, style);
            });
        });
    }
    
    // Register custom block styles
    if (registerStyles) {
        setTimeout(() => {
            Object.entries(registerStyles).forEach(([blockName, styles]) => {
                styles.forEach((style) => {
                    wp.blocks.registerBlockStyle(blockName, style);
                });
            });
        }, 100);
    }
    
});