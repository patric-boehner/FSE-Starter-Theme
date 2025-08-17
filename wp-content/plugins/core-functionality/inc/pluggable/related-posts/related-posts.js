const VARIATION_NAME = 'related-posts';

wp.blocks.registerBlockVariation( 'core/query', {
    name: VARIATION_NAME,
    title: 'Related Posts',
    description: 'Query posts related by the current post’s main category.',
    attributes: {
        namespace: VARIATION_NAME,
        query: {
            order: 'desc',
            orderBy: 'date',
            postType: 'posts',
            perPage: 3,
            offset: 0,
            inherit: false,
            filterRelated: true,
        },
    },
    isActive: [ 'namespace' ],
    scope: [ 'inserter' ],
    allowedControls: [ ],
    innerBlocks: [
        [
            'core/post-template',
            { layout: { type: 'grid', columnCount: 3 } },
            [
                [
                    'core/group',
                    { tagName: 'article', layout: { type: 'constrained' } },
                    [
                        [ 'core/post-featured-image', { aspectRatio: '3/2', sizeSlug: 'large' } ],
                        [ 'core/post-title', { level: 3, isLink: true } ]
                    ]
                ]
            ]
        ]
    ]
});