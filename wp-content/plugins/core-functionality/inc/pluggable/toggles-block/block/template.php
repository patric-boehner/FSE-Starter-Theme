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

<?php if ( ! $is_preview ) { ?>
    <div
        <?php
        echo wp_kses_data(
            get_block_wrapper_attributes(
                array(
                    'id'    => 'toggle-container-' . $anchor,
                    'class' => 'toggle-container'
                )
            )
        );
        ?>
    >
<?php } ?>
    
    <InnerBlocks 
        allowedBlocks="<?php echo esc_attr( wp_json_encode( array( 'cf/toggle' ) ) );?>" 
        template="<?php echo esc_attr( wp_json_encode( array(array( 'cf/toggle' )) ) );?>" 
    />

<?php if ( ! $is_preview ) { ?>
</div>
<?php } ?>