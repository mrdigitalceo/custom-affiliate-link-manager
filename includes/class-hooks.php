<?php
/**
 * Hooks Class
 *
 * @package CALM
 */

namespace CALM;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks Class
 */
class Hooks {

	/**
	 * Instance of this class.
	 *
	 * @var Hooks
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return Hooks
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		// Add any custom hooks or filters here for extensibility.
		add_filter( 'calm_redirect_url', array( $this, 'filter_redirect_url' ), 10, 2 );
	}

	/**
	 * Filter redirect URL
	 */
	public function filter_redirect_url( $url, $post_id ) {
		return apply_filters( 'calm_final_redirect_url', $url, $post_id );
	}
}
