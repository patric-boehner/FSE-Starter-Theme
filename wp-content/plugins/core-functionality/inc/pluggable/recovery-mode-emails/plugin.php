<?php
/**
 * Add extra email addresses to recovery mode email.
 *
 * @param string $email The default recovery email.
 * @param string $url The recovery URL.
 * @return string Modified list of emails.
 *
 * Setup Instructions:
 *
 * 1. Open your wp-config.php file.
 * 2. Add the following lines near the bottom, above the "That's all, stop editing!" comment:
 *
 * define( 'RECOVERY_EMAILS', 'dev@example.com, support@example.com' );
 * define( 'RECOVERY_EMAILS_ONLY', true ); // Optional: set to true to ONLY send to RECOVERY_EMAILS
 *
 * - You can include one or more email addresses in RECOVERY_EMAILS, separated by commas.
 * - Set RECOVERY_EMAILS_ONLY to true if you want to send ONLY to the addresses in RECOVERY_EMAILS.
 * - Set RECOVERY_EMAILS_ONLY to false (or don't define it) to send to both original and additional emails.
 *
 * Notes:
 * - If RECOVERY_EMAILS is not defined, the plugin does nothing.
 * - If RECOVERY_EMAILS_ONLY is true, only the emails in RECOVERY_EMAILS will receive the notification.
 * - If RECOVERY_EMAILS_ONLY is false/undefined, both original and additional emails will receive it.
 * - Invalid email addresses are ignored.
 */

// Block Access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Bring in related files
require_once( CORE_DIR . 'inc/pluggable/recovery-mode-emails/site-health.php' );


add_filter( 'recovery_mode_email', 'cf_add_recovery_email_recipients', 100, 2 );
function cf_add_recovery_email_recipients( $email, $url ) {

    if ( ! defined( 'RECOVERY_EMAILS' ) ) {
        error_log( '[RECOVERY EMAILS] RECOVERY_EMAILS is not defined, default email is being used.' );
        return $email; // If the constant is not defined, return the original email.
    }

    // Check if we should only send to the constant emails
    $only_custom_emails = defined( 'RECOVERY_EMAILS_ONLY' ) && RECOVERY_EMAILS_ONLY === true;

    if ( $only_custom_emails ) {
        
        // Only send to emails defined in RECOVERY_EMAILS constant
        $custom_emails = array_map( 'sanitize_email', array_filter( array_map( 'trim', explode( ',', RECOVERY_EMAILS ) ) ) );
        return implode( ',', $custom_emails );

    } else {

	    // Split both the default and custom emails into arrays
        $existing  = array_map( 'sanitize_email', array_filter( array_map( 'trim', explode( ',', $email ) ) ) );
        $additional = array_map( 'sanitize_email', array_filter( array_map( 'trim', explode( ',', RECOVERY_EMAILS ) ) ) );
    
        // Merge and deduplicate
        $all = array_unique( array_merge( $existing, $additional ) );

    }

    // Merge and deduplicate
    $all = array_unique( array_merge( $existing, $additional ) );

    // Return as comma-separated list
    return implode( ',', $all );

}