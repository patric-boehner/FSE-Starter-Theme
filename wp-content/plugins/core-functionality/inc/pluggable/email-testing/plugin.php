<?php

/**
 * Feature: Cron Email Testing
 *
 * @package     Core Functionality
 * @subpackage  Pluggable Features
 * @author      Patrick Boehner
 * @link        https://patrickboehner.com
 * @copyright   Copyright (c) 2012-2025, Patrick Boehner
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 *
 * Description: A custom cron job for testing email delivery.
 * 
 *  * The email goes to a ping testing service that notifies the developer
 * when delivery fails. This helps monitor the site's email sending ability
 * or connection to a transactional email provider.
 *
 * The cron won't run if the email isn't defined or if the site isn't live.
 * The cron will be removed when the plugin is deleted (but not deactivated).
 *
 * Add define( 'CRON_EMAIL', 'test@example.com' ); to your wp-config.php.
 */



//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// Bring in related files
require_once( CORE_DIR . 'inc/pluggable/email-testing/site-health.php' );



// Cron action, ping URL
add_action( 'cf_cron_email_test', 'cf_run_cron_email_test' );
function cf_run_cron_email_test() {

    // Don't run if the test email isn't defined or if the site is not live
    if ( ! defined( 'CRON_EMAIL' ) ) {
        error_log( '[CRON EMAIL TEST] CRON_EMAIL is not defined, skipping email test.' );
        return;
    }
    if ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        error_log( '[CRON EMAIL TEST] Not a live environment, skipping email test.' );
        return;
    }

	$to = CRON_EMAIL;
    $subject = wp_strip_all_tags( 'Email Test' );
    $body = sanitize_text_field( 'The email sent' );
    $headers = array('Content-Type: text/plain; charset=UTF-8');
	
	
	// Send email to cron monitor
	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		// The email sent successfully, do nothing
    } else {
		error_log( 'Test email sending failed' );
	}

}


function cf_add_cron_event_email_test() {

    // Don't run if not set up or if the site is not live
    if ( ! defined( 'CRON_EMAIL' ) ) {
        error_log( '[CRON EMAIL TEST] CRON_EMAIL is not defined, skipping email test.' );
        return;
    }
    if ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        error_log( '[CRON EMAIL TEST] Not a live environment, skipping email test.' );
        return;
    }

    // Check if the task is not already scheduled
    if ( ! wp_next_scheduled( 'cf_cron_email_test' ) ) {

        // Setup cron timestamp for midnight based on site GMT offset
        $gmt = get_option( 'gmt_offset' );
        $time = strtotime('midnight') + ((24 - $gmt) * HOUR_IN_SECONDS);
        if ( $time < time() ) {
            $time += DAY_IN_SECONDS;
        }

        if ( ! wp_schedule_event( $time, 'daily', 'cf_cron_email_test' ) ) {
            error_log( '[CRON EMAIL TEST] Failed to schedule cron event.' );
        }

    }

}


// Add admin notice if the cron is not scheduled
add_action( 'admin_notices', 'cf_cron_email_test_admin_notice' );
function cf_cron_email_test_admin_notice() {
    // Check conditions
    if (
        ! current_user_can( 'manage_options' ) ||
        ! defined( 'CRON_EMAIL' ) ||
        cf_is_local_dev_site() ||
        cf_is_development_staging_site() ||
        cf_is_staging_site() ||
        wp_next_scheduled( 'cf_cron_email_test' )
    ) {
        return;
    }

    // Prepare URL for triggering schedule
    $url = wp_nonce_url(
        admin_url( 'admin-post.php?action=cf_schedule_cron_email_test' ),
        'cf_schedule_cron_email_test',
        '_cf_nonce'
    );

    ?>
    <div class="notice notice-warning is-dismissible">
        <p>
            <strong><?php esc_html_e( 'Email Cron Test Not Scheduled:', 'core-functionality' ); ?></strong>
            <?php esc_html_e( 'The test email is enabled (CRON_EMAIL is defined), but the daily email check is not scheduled.', 'core-functionality' ); ?>
            <a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Click here to schedule it now', 'core-functionality' ); ?></a>.
        </p>
    </div>
    <?php
}


add_action( 'admin_post_cf_schedule_cron_email_test', 'cf_handle_schedule_cron_email_test' );
function cf_handle_schedule_cron_email_test() {
    if (
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_cf_nonce'] ) ||
        ! wp_verify_nonce( $_GET['_cf_nonce'], 'cf_schedule_cron_email_test' )
    ) {
        wp_die( 'Unauthorized or invalid request' );
    }

    // Run the scheduling function
    cf_add_cron_event_email_test();

    // Redirect back with success message (optional)
    wp_safe_redirect( admin_url( 'tools.php?cf_cron_scheduled=1' ) );
    exit;
}


add_action( 'admin_notices', 'cf_cron_email_test_success_notice' );
function cf_cron_email_test_success_notice() {
    if ( isset( $_GET['cf_cron_scheduled'] ) ) {
        $message = __( 'Email Cron Test was successfully scheduled.', 'core-functionality' );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
    }
}
