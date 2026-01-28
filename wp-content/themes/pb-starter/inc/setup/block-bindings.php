<?php
/**
 * Register custom block bindings sources.
 *
 * @package pb-starter
 */

add_action( 'init', function() {
    register_block_bindings_source(
        'theme/copyright',
        array(
            'label'              => __( 'Copyright Year', 'Label for the copyright placeholder in the editor', 'pb-starter' ),
            'get_value_callback' => 'fse_get_copyright',
        )
    );
});
