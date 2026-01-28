<?php
/**
 * ============================================================================
 * SITE HEALTH INTEGRATION - CRON EMAIL TEST
 * ============================================================================
 *
 * This section registers Site Health checks and info fields for the cron email
 * test functionality. It will only register if the core Site Health system
 * is loaded and the cron email functions exist.
 *
 * Provides:
 * - Critical Site Health test if cron is misconfigured
 * - Info tab details about configuration and scheduling
 * - Environment detection and next run time
 *
 * Requirements:
 * - cf_register_site_health_test() function must exist
 * - cf_register_site_health_info() function must exist
 * - cf_run_cron_email_test() function must exist
 *
 * @since 2.1.0
 */


//* Block Access
//**********************
if( !defined( 'ABSPATH' ) ) exit;


add_action( 'init', 'cf_register_cron_email_site_health_info' );
function cf_register_cron_email_site_health_info() {

    // Only register if the functions exist (module is included)
    if ( ! function_exists( 'cf_run_cron_email_test' ) ) {
        return;
    }

    // Register the Site Health test
    cf_register_site_health_test(
        'cf_cron_email_test',
        __( 'Cron Email Test Status', 'core-functionality' ),
        'cf_site_health_cron_email_test'
    );

    // Register info fields
    $cron_info_fields = array();

    $cron_info_fields['cron_email_configured'] = array(
        'label' => __( 'Cron Email Test Configured', 'core-functionality' ),
        'value' => defined( 'CRON_EMAIL' ) ? __( 'Yes', 'core-functionality' ) : __( 'No', 'core-functionality' ),
    );

    if ( defined( 'CRON_EMAIL' ) ) {
        $cron_info_fields['cron_email_address'] = array(
            'label' => 'CRON_EMAIL',
            'value' => CRON_EMAIL,
        );

        $cron_info_fields['cron_email_environment'] = array(
            'label' => __( 'Environment Check', 'core-functionality' ),
            'value' => ( cf_is_local_dev_site() || cf_is_development_staging_site() || cf_is_staging_site() )
                ? __( 'Development/Staging (Test Disabled)', 'core-functionality' )
                : __( 'Live (Test Active)', 'core-functionality' ),
        );

        $next_scheduled = wp_next_scheduled( 'cf_cron_email_test' );
        $cron_info_fields['cron_email_scheduled'] = array(
            'label' => __( 'Next Scheduled Run', 'core-functionality' ),
            'value' => $next_scheduled
                ? wp_date( 'Y-m-d H:i:s T', $next_scheduled )
                : __( 'Not Scheduled', 'core-functionality' ),
        );
    }

    cf_register_site_health_info( $cron_info_fields );
}

/**
 * Site Health test for cron email functionality
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

    // Check if the cron has missed its schedule (WordPress native check)
    $cron_array = _get_cron_array();
    $missed_cron = false;

    // Check if any cf_cron_email_test events are overdue
    foreach ( $cron_array as $timestamp => $cron ) {
        if ( isset( $cron['cf_cron_email_test'] ) && $timestamp < time() ) {
            $missed_cron = true;
            break;
        }
    }

    if ( $missed_cron ) {
        $result['status'] = 'critical';
        $result['label'] = __( 'Cron Email Test Behind Schedule', 'core-functionality' );
        $result['description'] = sprintf(
            '<p>%s</p>',
            __( 'The cron email test is scheduled but appears to be behind schedule. This may indicate that WordPress cron is not running properly on your site.', 'core-functionality' )
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
