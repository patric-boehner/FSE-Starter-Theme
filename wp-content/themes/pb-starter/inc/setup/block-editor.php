<?php
/**
 * Customize the block editor options.
 *
 * @package pb-starter
 **/


/**
 * Get hidden blocks
 */
function get_hidden_blocks() {

    $post_type = get_post_type();
    $screen = get_current_screen();
    $context = $screen ? $screen->id : '';
    
    // Blocks to hide everywhere
    $universal_hidden = [
        // Common blocks
        'core/verse',
        'core/pullquote',
        'core/preformatted',
        'core/code',
        'core/classic',
        'core/table',
        'core/spacer',
        'core/media-text',

        // Meida blocks
        'core/audio',
        'core/video',
        'core/file',

        // Desgin blocks
        'core/more',
        'core/nextpage',

        // Widgets blocks
        'core/archives',
        'core/calendar',
        'core/page-list',
        'core/categories',
        'core/latest-comments',
        'core/latest-posts',
        'core/rss',
        // 'core/search',
        'core/tag-cloud',
        'core/shortcode',
        'core/html',

        // Plugins
        // 'outermost/social-sharing',
    ];


    // FSE blocks - only hide if NOT in site editor
    $fse_blocks = [
        'core/site-logo',
        'core/site-title',
        'core/site-tagline',
        'core/navigation',
        'core/navigation-link',
        'core/navigation-submenu',
        'core/query',
        'core/query-pagination',
        'core/query-pagination-next',
        'core/query-pagination-numbers',
        'core/query-pagination-previous',
        'core/query-no-results',
        'core/query-title',
        'core/post-title',
        // 'core/post-content',
        'core/post-date',
        'core/post-excerpt',
        'core/post-featured-image',
        'core/post-terms',
        'core/post-author',
        'core/post-author-biography',
        'core/post-author-name',
        'core/read-more',
        'core/comments',
        'core/post-comments',
        'core/post-comments-form',
        'core/page-list',
        'core/loginout',
        'core/avatar',
        'core/term-description',
        'core/post-navigation-link',
    ];
    
    $hidden_blocks = $universal_hidden;

    // Only hide FSE blocks if NOT in site editor
    if ( $context !== 'site-editor') {
        $hidden_blocks = array_merge($hidden_blocks, $fse_blocks);
    }
    
    // Post type specific hiding (only applies to non-site-editor contexts)
    if ($context !== 'site-editor') {
        if ($post_type === 'post' || $post_type === 'page') {
            $hidden_blocks = array_merge($hidden_blocks, [
                'outermost/social-sharing',
                'core/search',
            ]);
        }
        
        // if ($post_type === 'page') {
        //     $hidden_blocks = array_merge($hidden_blocks, [
        //         'core/code',
        //         'core/spacer',
        //     ]);
        // }
    }
    
    return $hidden_blocks;

}


/**
 * Enhanced function to get custom block styles (unchanged from your current)
 */
function get_custom_block_styles() {

    $custom_styles = [
        'core/button' => [
            ['name' => 'primary', 'label' => 'Primary', 'isDefault' => true],
            ['name' => 'secondary', 'label' => 'Secondary'],
        ],
        'core/list' => [
            ['name' => 'default', 'label' => 'Default', 'isDefault' => true],
            ['name' => 'no-bullets', 'label' => 'No Bullets'],
            ['name' => 'checkmarks', 'label' => 'Checkmarks'],
            ['name' => 'arrows', 'label' => 'Arrows'],
        ],
        'core/navigation-link' => [
            ['name' => 'default', 'label' => 'Default', 'isDefault' => true],
            ['name' => 'button', 'label' => 'Button'],
        ]
    ];

    return apply_filters('theme_custom_block_styles', $custom_styles);

}


/**
 * Enhanced function to get hidden block styles (unchanged from your current)
 */
function get_hidden_block_styles() {

    $hidden_styles = [
        'core/image' => ['default', 'circle-mask', 'rounded'],
        'core/button' => ['default', 'fill', 'squared', 'outline'],
        'core/quote' => ['default', 'large', 'plain'],
        'core/separator' => ['default', 'wide', 'dots'],
        'core/pullquote' => ['default', 'solid-color'],
        'core/table' => ['default', 'stripes'],
        'core/social-links' => ['default', 'logos-only', 'pill-shape'],
        'outermost/social-sharing' => ['logos-only', 'pill-shape'],
    ];

    return apply_filters('theme_hidden_block_styles', $hidden_styles);

}