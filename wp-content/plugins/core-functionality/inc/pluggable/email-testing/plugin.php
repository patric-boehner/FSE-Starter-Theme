<?php
/**
* Cron Email Testing
*
* Setup a custom cron action that runs daily and sends an email
* using the wp_mail function to an address defined via CORON_EMAIL.
* The email goes to a ping testing service that ntofies the developer
* when the email fails to be delviered. This is to test and monitor the
* websites email sending ability or connection to a transactional email
* provider.
*
* The cron wont run if the email isn't defined or if the site isn't live.
* The cron will be removed when the plugin is deleated but not deactivated.
*
* Add define( 'CRON_EMAIL', 'test@example.com' ); to your wp-config.php
*
* @package    CoreFunctionality
* @since      2.0.0
* @copyright  Copyright (c) 2022, Patrick Boehner
* @license    GPL-2.0+
*/

//* Block Acess
//**********************
if( !defined( 'ABSPATH' ) ) exit;


// Cron action, ping URL
add_action( 'cf_cron_email_test', 'cf_run_cron_email_test' );
function cf_run_cron_email_test() {

    // Don't run if the test email isn't defined or if the site is not live
    if ( ! defined( 'CRON_EMAIL' ) || cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        error_log( 'Test email is not defined or the site is not a live enviroment' );
        return;
    }

	$to = CRON_EMAIL;
    $subject = wp_strip_all_tags( 'Email Test' );
    $body = wp_strip_all_tags( 'The email sent' );
    $headers = array('Content-Type: text/plain; charset=UTF-8');
	
	
	// Send email to cron monitor
	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		// The email sent sucessful, do nothing
    } else {
		error_log( 'Test email sending failed' );
	}

}


function cf_add_cron_event_email_test() {

    // Don't run if not set up or if the site is not live
    if ( ! defined( 'CRON_EMAIL' ) || cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        error_log( 'Test email is not defined or the site is not a live enviroment' );
        return;
    }

    // Check if the task is not already scheduled
    if ( ! wp_next_scheduled( 'cf_cron_email_test' ) ) {

        // Setup cron timestamp for midnight based on site GMT offset
        $gmt = get_option( 'gmt_offset' );
        $time = strtotime('midnight') + ((24 - $gmt) * HOUR_IN_SECONDS);
        if ( $time < time() ) {
            $time += (24 * HOUR_IN_SECONDS);
        }

        if ( wp_schedule_event( $time, 'daily', 'cf_cron_email_test' ) ) {
            // The email was successfully schedualed, do nothing
        } else {
            error_log( 'Test email scheduling failed' );
        }

    } else {
        error_log( 'Test email already scheduled' );
    }

}