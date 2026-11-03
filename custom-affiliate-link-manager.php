<?php
/**
 * Plugin Name: Custom Affiliate Link Manager
 * Description: Manage and cloak affiliate links with advanced features, without external APIs.
 * Version: 1.1.1
 * Author: Mr Arslan CEO
 * Author URI: https://www.youtube.com/@themrdigitalceo
 * Text Domain: custom-affiliate-link-manager
 * Domain Path: /languages
 * License: GPL2
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Plugin Constants
 */
define( 'CALM_VERSION', '1.1.1' );
define( 'CALM_PATH', plugin_dir_path( __FILE__ ) );
define( 'CALM_URL', plugin_dir_url( __FILE__ ) );
define( 'CALM_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoload Classes
 */
spl_autoload_register( function ( $class ) {
	$prefix = 'CALM\\';
	$base_dir = CALM_PATH . 'includes/';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, $len );
	$file = $base_dir . 'class-' . strtolower( str_replace( '_', '-', $relative_class ) ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

/**
 * Initialize the plugin
 */
function calm_init() {
	\CALM\Core::get_instance();
}
add_action( 'plugins_loaded', 'calm_init' );

/**
 * Activation Hook
 */
register_activation_hook( __FILE__, array( '\CALM\Core', 'activate' ) );

/**
 * Deactivation Hook
 */
register_deactivation_hook( __FILE__, array( '\CALM\Core', 'deactivate' ) );
