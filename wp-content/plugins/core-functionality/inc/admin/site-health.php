<?php
/**
 * Site Health Integration for Core Functionality Plugin
 *
 * Adds custom Site Health checks and info sections to monitor
 * the status of plugin features like cron email testing and
 * recovery email configuration.
 *
 * @package    CoreFunctionality
 * @since      2.1.0
 * @copyright  Copyright (c) 2025, Patrick Boehner
 * @license    GPL-2.0+
 */

// Block Access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Site Health tests and info sections
 */
add_filter( 'site_status_tests', 'cf_add_site_health_tests' );
add_filter( 'debug_information', 'cf_add_site_health_info' );

/**
 * Register custom Site Health tests
 *
 * @param array $tests Array of Site Health tests
 * @return array Modified tests array
 */
function cf_add_site_health_tests( $tests ) {
    
    // Add cron email test
    $tests['direct']['cf_cron_email_test'] = array(
        'label' => __( 'Cron Email Test Status', 'core-functionality' ),
        'test'  => 'cf_site_health_cron_email_test',
    );

    return $tests;
}

/**
 * Site Health test for cron email functionality
 *
 * @return array Test result
 */
function cf_site_health_cron_email_test() {
    
    $result = array(
        'label'       => __( 'Cron Email Test', 'core-functionality' ),
        'status'      => 'good',
        'badge'       => array(
            'label' => __( 'Core Functionality', 'core-functionality' ),
            'color' => 'blue',
        ),
        'description' => '',
        'actions'     => '',
        'test'        => 'cf_cron_email_test',
    );

    // Check if CRON_EMAIL is defined
    if ( ! defined( 'CRON_EMAIL' ) ) {
        $result['status'] = 'recommended';
        $result['label'] = __( 'Cron Email Test Not Configured', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is not configured. To enable daily email monitoring, add <code>define( \'CRON_EMAIL\', \'your-test@email.com\' );</code> to your wp-config.php file.', 'core-functionality' )
        );
        return $result;
    }

    // Check if we're in a non-live environment
    if ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        $result['status'] = 'good';
        $result['label'] = __( 'Cron Email Test Disabled (Development)', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is properly configured but disabled because this is not a live environment. This is expected behavior.', 'core-functionality' )
        );
        return $result;
    }

    // Check if cron is scheduled
    $next_scheduled = wp_next_scheduled( 'cf_cron_email_test' );
    if ( ! $next_scheduled ) {
        $result['status'] = 'critical';
        $result['label'] = __( 'Cron Email Test Not Scheduled', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is configured but not scheduled to run. This may indicate a problem with WordPress cron functionality.', 'core-functionality' )
        );
        
        // Add action to schedule the cron
        $schedule_url = wp_nonce_url(
            admin_url( 'admin-post.php?action=cf_schedule_cron_email_test' ),
            'cf_schedule_cron_email_test',
            '_cf_nonce'
        );
        
        $result['actions'] = sprintf(
            '<p><a href="%s" class="button button-primary">%s</a></p>',
            esc_url( $schedule_url ),
            __( 'Schedule Cron Email Test', 'core-functionality' )
        );
        
        return $result;
    }

    // Check for recent cron execution (look for errors in the last 25 hours)
    $log_file = WP_CONTENT_DIR . '/debug.log';
    $has_recent_error = false;
    
    if ( file_exists( $log_file ) && is_readable( $log_file ) ) {
        $log_content = file_get_contents( $log_file );
        $lines = explode( "\n", $log_content );
        
        // Look for recent email test failures
        $one_day_ago = time() - ( 25 * HOUR_IN_SECONDS ); // 25 hours to account for timing
        
        foreach ( array_reverse( $lines ) as $line ) {
            if ( strpos( $line, 'Test email sending failed' ) !== false ) {
                // Try to extract timestamp from log line
                if ( preg_match( '/\[(\d{2}-\w{3}-\d{4} \d{2}:\d{2}:\d{2} UTC)\]/', $line, $matches ) ) {
                    $log_time = strtotime( $matches[1] );
                    if ( $log_time && $log_time > $one_day_ago ) {
                        $has_recent_error = true;
                        break;
                    }
                }
            }
        }
    }

    if ( $has_recent_error ) {
        $result['status'] = 'critical';
        $result['label'] = __( 'Cron Email Test Failed Recently', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is scheduled and running, but recent attempts to send test emails have failed. This may indicate an issue with your email configuration or transactional email provider.', 'core-functionality' )
        );
    } else {
        $result['label'] = __( 'Cron Email Test Active', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is properly configured and scheduled. Daily email monitoring is active.', 'core-functionality' )
        );
    }

    return $result;
}

/**
 * Add custom info sections to Site Health Info tab
 *
 * @param array $debug_info Array of debug information
 * @return array Modified debug info array
 */
function cf_add_site_health_info( $debug_info ) {
    
    // Core Functionality Plugin section
    $debug_info['cf-plugin'] = array(
        'label'  => __( 'Core Functionality Plugin', 'core-functionality' ),
        'fields' => array(),
    );

    // Cron Email Test Information
    $debug_info['cf-plugin']['fields']['cron_email_configured'] = array(
        'label' => __( 'Cron Email Test Configured', 'core-functionality' ),
        'value' => defined( 'CRON_EMAIL' ) ? __( 'Yes', 'core-functionality' ) : __( 'No', 'core-functionality' ),
    );

    if ( defined( 'CRON_EMAIL' ) ) {
        $debug_info['cf-plugin']['fields']['cron_email_address'] = array(
            'label' => 'CRON_EMAIL',
            'value' => CRON_EMAIL,
        );

        $debug_info['cf-plugin']['fields']['cron_email_environment'] = array(
            'label' => __( 'Environment Check', 'core-functionality' ),
            'value' => ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) 
                ? __( 'Development/Staging (Test Disabled)', 'core-functionality' )
                : __( 'Live (Test Active)', 'core-functionality' ),
        );

        $next_scheduled = wp_next_scheduled( 'cf_cron_email_test' );
        $debug_info['cf-plugin']['fields']['cron_email_scheduled'] = array(
            'label' => __( 'Next Scheduled Run', 'core-functionality' ),
            'value' => $next_scheduled 
                ? wp_date( 'Y-m-d H:i:s T', $next_scheduled )
                : __( 'Not Scheduled', 'core-functionality' ),
        );
    }

    // Recovery Email Information
    $debug_info['cf-plugin']['fields']['recovery_emails_configured'] = array(
        'label' => __( 'Recovery Emails Configured', 'core-functionality' ),
        'value' => defined( 'RECOVERY_EMAILS' ) ? __( 'Yes', 'core-functionality' ) : __( 'No', 'core-functionality' ),
    );

    if ( defined( 'RECOVERY_EMAILS' ) ) {
        $recovery_emails = array_map( 'trim', explode( ',', RECOVERY_EMAILS ) );
        $debug_info['cf-plugin']['fields']['recovery_emails_list'] = array(
            'label' => 'RECOVERY_EMAILS',
            'value' => implode( ', ', $recovery_emails ),
        );

        $only_custom = defined( 'RECOVERY_EMAILS_ONLY' ) && RECOVERY_EMAILS_ONLY === true;
        $debug_info['cf-plugin']['fields']['recovery_emails_admin_included'] = array(
            'label' => 'RECOVERY_EMAILS_ONLY',
            'value' => $only_custom ? __( 'True', 'core-functionality' ) : __( 'False', 'core-functionality' ),
        );


    }

    // Add plugin version and general info
    $debug_info['cf-plugin']['fields']['plugin_version'] = array(
        'label' => __( 'Plugin Version', 'core-functionality' ),
        'value' => '2.1.0', // Update this when you increment your plugin version
    );

    return $debug_info;
}

/**
 * Helper function to get a human-readable status summary
 * Useful for dashboard widgets or other admin displays
 *
 * @return array Array of feature statuses
 */
function cf_get_plugin_status_summary() {
    
    $status = array();

    // Cron Email Test Status
    if ( ! defined( 'CRON_EMAIL' ) ) {
        $status['cron_email'] = array(
            'status' => 'not_configured',
            'message' => __( 'Not configured', 'core-functionality' ),
            'color' => 'gray',
        );
    } elseif ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() ) {
        $status['cron_email'] = array(
            'status' => 'disabled_dev',
            'message' => __( 'Disabled (development)', 'core-functionality' ),
            'color' => 'blue',
        );
    } elseif ( ! wp_next_scheduled( 'cf_cron_email_test' ) ) {
        $status['cron_email'] = array(
            'status' => 'not_scheduled',
            'message' => __( 'Not scheduled', 'core-functionality' ),
            'color' => 'red',
        );
    } else {
        $status['cron_email'] = array(
            'status' => 'active',
            'message' => __( 'Active', 'core-functionality' ),
            'color' => 'green',
        );
    }

    // Recovery Email Status
    if ( ! defined( 'RECOVERY_EMAILS' ) ) {
        $status['recovery_emails'] = array(
            'status' => 'not_configured',
            'message' => __( 'Using defaults', 'core-functionality' ),
            'color' => 'gray',
        );
    } else {
        $only_custom = defined( 'RECOVERY_EMAILS_ONLY' ) && RECOVERY_EMAILS_ONLY === true;
        $email_count = count( array_filter( array_map( 'trim', explode( ',', RECOVERY_EMAILS ) ) ) );
        
        $status['recovery_emails'] = array(
            'status' => 'configured',
            'message' => sprintf(
                /* translators: %d is the number of custom email addresses */
                _n( '%d custom email', '%d custom emails', $email_count, 'core-functionality' ),
                $email_count
            ) . ( $only_custom ? __( ' (admin excluded)', 'core-functionality' ) : __( ' (+ admin)', 'core-functionality' ) ),
            'color' => 'green',
        );
    }

    return $status;
}