<?php
/**
 * Title: Columns with cards
 * Slug: pb-starter/columns-with-cards
 * Description: A three column layout with card styles.
 * Categories: pb-starter/features, columns
 * Keywords: columns, features, cards
 * Viewport Width: 1700
 * Inserter: true
*/
?>
<!-- wp:group {"metadata":{"name":"Columns with cards","categories":["pb-starter/features"],"patternName":"pb-starter/columns-with-cards"},"align":"full","layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull">
    <!-- wp:columns {"verticalAlignment":"center","align":"wide","className":"is-style-large-gap"} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-center is-style-large-gap">

        <!-- wp:column {"verticalAlignment":"center","className":"is-style-card"} -->
        <div class="wp-block-column is-vertically-aligned-center is-style-card">
            <!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-1.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for card image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
            <!-- /wp:image -->

            <!-- wp:group {"metadata":{"name":"Entry"},"backgroundColor":"tertiary","layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group has-tertiary-background-color has-background">
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","className":"is-style-card"} -->
        <div class="wp-block-column is-vertically-aligned-center is-style-card">
            <!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-2.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for card image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
            <!-- /wp:image -->

            <!-- wp:group {"metadata":{"name":"Entry"},"backgroundColor":"tertiary","layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group has-tertiary-background-color has-background">
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","className":"is-style-card"} -->
        <div class="wp-block-column is-vertically-aligned-center is-style-card">
            <!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-3.webp' ) ); ?>" alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for card image.', 'pb-starter' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
            <!-- /wp:image -->

            <!-- wp:group {"metadata":{"name":"Entry"},"backgroundColor":"tertiary","layout":{"type":"constrained","justifyContent":"center"}} -->
            <div class="wp-block-group has-tertiary-background-color has-background">
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading"><?php esc_html_e( 'This is a heading', 'pb-starter' ); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e( 'Curabitur viverra congue urna. Aliquam viverra consectetur diam sed suscipit. Sed imperdiet cursus bibendum.', 'pb-starter' ); ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

        </div>
        <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
