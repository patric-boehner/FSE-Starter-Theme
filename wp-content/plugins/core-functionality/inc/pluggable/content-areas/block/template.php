<?php if( is_admin() && empty( $term_id ) ): ?>
    <div class="components-placeholder">
        <div class="components-placeholder__label">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M21.3 10.8l-5.6-5.6c-.7-.7-1.8-.7-2.5 0l-5.6 5.6c-.7.7-.7 1.8 0 2.5l5.6 5.6c.3.3.8.5 1.2.5s.9-.2 1.2-.5l5.6-5.6c.8-.7.8-1.9.1-2.5zm-17.6 1L10 5.5l-1-1-6.3 6.3c-.7.7-.7 1.8 0 2.5L9 19.5l1.1-1.1-6.3-6.3c-.2 0-.2-.2-.1-.3z"></path></svg>
            <?php echo esc_html__( 'Content Area', 'core-functionality' ); ?>
        </div>
        <div class="components-placeholder__instructions"><?php echo esc_html__( 'Select a content area to display content in this post or template.', 'core-functionality' ); ?></div>
    </div>
<?php endif; ?>

<?php if( $query->have_posts() ): ?>

    <?php while( $query->have_posts() ): ?>
        <?php $query->the_post(); ?>
        <div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($class_name); ?>">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>

<?php endif; ?>