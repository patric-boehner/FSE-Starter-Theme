<?php

/**
 * ============================================================================
 * SITE HEALTH INTEGRATION - RECOVERY EMAIL
 * ============================================================================
 * 
 * This section registers Site Health info fields for the recovery email
 * functionality. It will only register if the core Site Health system
 * is loaded and the recovery email functions exist.
 * 
 * Provides:
 * - Info tab details about custom recovery email configuration
 * - Shows which emails will receive recovery notifications
 * - Displays whether site admin is included or excluded
 * 
 * Requirements:
 * - cf_register_site_health_info() function must exist
 * - cf_add_recovery_email_recipients() function must exist
 * 
 * @since 2.1.0
 */

//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;


add_action( 'init', 'cf_register_recovery_email_site_health_info' );
function cf_register_recovery_email_site_health_info() {

    // Only register if the recovery email filter exists (module is included)
    if ( ! function_exists( 'cf_add_recovery_email_recipients' ) ) {
        return;
    }

    // Register info fields for recovery emails
    $recovery_info_fields = array();

    $recovery_info_fields['recovery_emails_configured'] = array(
        'label' => __( 'Custom Recovery Emails Configured', 'core-functionality' ),
        'value' => defined( 'RECOVERY_EMAILS' ) ? __( 'Yes', 'core-functionality' ) : __( 'No', 'core-functionality' ),
    );

    if ( defined( 'RECOVERY_EMAILS' ) ) {
        $recovery_emails = array_map( 'trim', explode( ',', RECOVERY_EMAILS ) );
        $recovery_info_fields['recovery_emails_list'] = array(
            'label' => 'RECOVERY_EMAILS',
            'value' => implode( ', ', $recovery_emails ),
        );

        $only_custom = defined( 'RECOVERY_EMAILS_ONLY' ) && RECOVERY_EMAILS_ONLY === true;
        $recovery_info_fields['recovery_emails_admin_included'] = array(
            'label' => 'RECOVERY_EMAILS_ONLY',
            'value' => $only_custom ? __( 'true', 'core-functionality' ) : __( 'false', 'core-functionality' ),
        );
    }

    cf_register_site_health_info( $recovery_info_fields );
}