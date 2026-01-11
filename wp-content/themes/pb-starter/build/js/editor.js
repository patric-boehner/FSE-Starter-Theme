(() => {
  // src/js/editor.js
  wp.domReady(() => {
    const { hiddenStyles, registerStyles, hiddenBlocks, unregisteredVariations } = myEditorOptions;
    if (unregisteredVariations) {
      Object.keys(unregisteredVariations).forEach((blockName) => {
        unregisteredVariations[blockName].forEach((variationName) => {
          wp.blocks.unregisterBlockVariation(blockName, variationName);
        });
      });
    }
    if (hiddenBlocks && hiddenBlocks.length > 0) {
      hiddenBlocks.forEach((blockName) => {
        const block = wp.blocks.getBlockType(blockName);
        if (block) {
          wp.blocks.unregisterBlockType(blockName);
        }
      });
    }
    if (hiddenStyles) {
      Object.keys(hiddenStyles).forEach((blockName) => {
        hiddenStyles[blockName].forEach((styleName) => {
          wp.blocks.unregisterBlockStyle(blockName, styleName);
        });
      });
    }
    if (registerStyles) {
      Object.keys(registerStyles).forEach((blockName) => {
        registerStyles[blockName].forEach((style) => {
          wp.blocks.registerBlockStyle(blockName, style);
        });
      });
    }
  });
})();
//# sourceMappingURL=editor.js.map
