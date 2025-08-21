<?php
/**
 * Site Health Integration for Core Functionality Plugin
 *
 * Modular system for adding Site Health checks and info sections.
 * Each feature can register its own health checks independently.
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

// Global arrays to store registered tests and info fields
global $cf_site_health_tests, $cf_site_health_info_fields;
$cf_site_health_tests = array();
$cf_site_health_info_fields = array();

/**
 * Register a Site Health test
 *
 * @param string $test_id Unique test identifier
 * @param string $label Test label
 * @param string $callback Function name to call for the test
 */
function cf_register_site_health_test( $test_id, $label, $callback ) {

    global $cf_site_health_tests;
    
    $cf_site_health_tests[ $test_id ] = array(
        'label' => $label,
        'callback' => $callback,
    );

}

/**
 * Register Site Health info fields
 *
 * @param array $fields Array of field configurations
 */
function cf_register_site_health_info( $fields ) {

    global $cf_site_health_info_fields;
    
    $cf_site_health_info_fields = array_merge( $cf_site_health_info_fields, $fields );

}

/**
 * Add registered tests to Site Health
 */
function cf_add_site_health_tests( $tests ) {

    global $cf_site_health_tests;
    
    foreach ( $cf_site_health_tests as $test_id => $config ) {
        $tests['direct'][ $test_id ] = array(
            'label' => $config['label'],
            'test'  => $config['callback'],
        );
    }
    
    return $tests;

}

/**
 * Add registered info to Site Health Info tab
 */
function cf_add_site_health_info( $debug_info ) {

    global $cf_site_health_info_fields;
    
    if ( empty( $cf_site_health_info_fields ) ) {
        return $debug_info;
    }
    
    // Core Functionality Plugin section
    $debug_info['cf-plugin'] = array(
        'label'  => __( 'Core Functionality Plugin', 'core-functionality' ),
        'fields' => array(),
    );
    
    // Add plugin version if available
    if ( defined( 'CORE_VERSION' ) ) {
        $debug_info['cf-plugin']['fields']['plugin_version'] = array(
            'label' => __( 'Plugin Version', 'core-functionality' ),
            'value' => CORE_VERSION,
        );
    }
    
    // Add all registered fields
    foreach ( $cf_site_health_info_fields as $field_id => $field_config ) {
        $debug_info['cf-plugin']['fields'][ $field_id ] = $field_config;
    }
    
    return $debug_info;
    
}

// Hook into WordPress Site Health
add_filter( 'site_status_tests', 'cf_add_site_health_tests', 10 );
add_filter( 'debug_information', 'cf_add_site_health_info', 10 );
