<?php
/**
* Block Template
*
* @package    CoreFunctionality
* @since      2.0.0
* @copyright  Copyright (c) 2019, Patrick Boehner
* @license    GPL-2.0+
*/

//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;

?>
<?php if ( ! $is_preview ): ?>
    <div <?php echo wp_kses_data( 
        get_block_wrapper_attributes(
            array(
                'id' => 'toggle-item-' . esc_attr( $anchor ),
                'class' => 'toggle-item' . esc_attr( $data['open_state'] == 'yes' ? ' toggle-open' : '' ),
            )
        )
    ); 
    ?>>
<?php endif; ?>

<?php if ( ! $is_preview ): ?>

    <<?php echo esc_attr( $data['heading_level'] ); ?> class="toggle-item__header">
        <button <?php echo $button_attr; ?>>
            <?php echo $icon_svg; ?>
            <span class="button_text"><?php echo esc_html($data['heading']); ?></span>
        </button>
    </<?php echo esc_attr( $data['heading_level'] ); ?>>

<?php else: ?>
    <h3 class="toggle-item__header">  
        <span class="button_text"><?php echo esc_html($data['heading']); ?></span>   
    </h3>
<?php endif; ?>

    <div <?php echo $content_attr; ?>>
        <InnerBlocks template="<?php echo esc_attr( wp_json_encode( $inner_blocks_template ) ); ?>"/>
    </div>
    
<?php if ( ! $is_preview ): ?>
</div>
<?php endif; ?>