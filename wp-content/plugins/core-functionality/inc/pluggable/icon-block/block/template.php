<?php
/**
* Block Template
*
*/

//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;

?>

<?php if( is_admin() && empty( $data['icon_name'] ) ): ?>
    <div class="components-placeholder">
        <div class="components-placeholder__label">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true" focusable="false"><path d="m21.5 9.1-6.6-6.6-4.2 5.6c-1.2-.1-2.4.1-3.6.7-.1 0-.1.1-.2.1-.5.3-.9.6-1.2.9l3.7 3.7-5.7 5.7v1.1h1.1l5.7-5.7 3.7 3.7c.4-.4.7-.8.9-1.2.1-.1.1-.2.2-.3.6-1.1.8-2.4.6-3.6l5.6-4.1zm-7.3 3.5.1.9c.1.9 0 1.8-.4 2.6l-6-6c.8-.4 1.7-.5 2.6-.4l.9.1L15 4.9 19.1 9l-4.9 3.6z"></path></svg>
            <?php echo esc_html__( 'Icon', 'core-functionality' ); ?>
        </div>
        <div class="components-placeholder__instructions"><?php echo esc_html__( 'Choose an icon from the library.', 'core-functionality' ); ?></div>
    </div>
<?php endif; ?>

<?php if( !empty( $data['icon_name'] ) ): ?>
    <div<?php if( !empty($block_id) ): ?> id="<?php echo esc_attr( $block_id ); ?>"<?php endif; ?> class="<?php echo esc_attr( $block_classes_outter ); ?>">
        <div class="<?php echo esc_attr( $block_classes_inner ); ?>">
            <?php echo $icon_svg; ?>
        </div>
    </div>
<?php endif; ?>
