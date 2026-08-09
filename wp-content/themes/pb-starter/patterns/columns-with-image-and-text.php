<?php
/**
 * Title: Columns with image and text
 * Slug: pb-starter/columns-with-image-and-text
 * Description: A two column layout with text and an image.
 * Categories: about, columns
 * Keywords: columns, text, image
 * Viewport Width: 1700
 * Inserter: true
 */
?>

<!-- wp:group {"metadata":{"name":"Columns with image and text","categories":["about"],"patternName":"pb-starter/columns-with-image-and-text"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

    <!-- wp:columns {"align":"wide","className":"is-style-large-gap is-style-columns-reverse"} -->
    <div class="wp-block-columns alignwide is-style-large-gap is-style-columns-reverse">

        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">

            <!-- wp:heading -->
            <h2 class="wp-block-heading"><?php esc_html_e( 'This is a primary heading', 'pb-starter' ); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph -->
            <p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum. Suspendisse potenti.', 'pb-starter' ); ?></p>
            <!-- /wp:paragraph -->

        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">

            <!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large">
                <img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/square-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for section image.', 'pb-starter' ); ?>" style="aspect-ratio:1;object-fit:cover"/>
            </figure>
            <!-- /wp:image -->

        </div>
        <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->

</div>
<!-- /wp:group -->
