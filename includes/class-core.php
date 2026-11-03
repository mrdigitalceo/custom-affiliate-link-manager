<?php
/**
 * Core Plugin Class
 *
 * @package CALM
 */

namespace CALM;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core Class
 */
class Core {

	/**
	 * Instance of this class.
	 *
	 * @var Core
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return Core
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
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load dependencies
	 */
	private function load_dependencies() {
		// Classes are autoloaded, but we can initialize them here if needed.
		Admin::get_instance();
		Frontend::get_instance();
		Hooks::get_instance();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Load textdomain for i18n
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'custom-affiliate-link-manager', false, dirname( CALM_BASENAME ) . '/languages' );
	}

	/**
	 * Register Custom Post Type
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Affiliate Links', 'post type general name', 'custom-affiliate-link-manager' ),
			'singular_name'      => _x( 'Affiliate Link', 'post type singular name', 'custom-affiliate-link-manager' ),
			'menu_name'          => _x( 'Affiliate Links', 'admin menu', 'custom-affiliate-link-manager' ),
			'name_admin_bar'     => _x( 'Affiliate Link', 'add new on admin bar', 'custom-affiliate-link-manager' ),
			'add_new'            => _x( 'Add New', 'affiliate link', 'custom-affiliate-link-manager' ),
			'add_new_item'       => __( 'Add New Affiliate Link', 'custom-affiliate-link-manager' ),
			'new_item'           => __( 'New Affiliate Link', 'custom-affiliate-link-manager' ),
			'edit_item'          => __( 'Edit Affiliate Link', 'custom-affiliate-link-manager' ),
			'view_item'          => __( 'View Affiliate Link', 'custom-affiliate-link-manager' ),
			'all_items'          => __( 'All Affiliate Links', 'custom-affiliate-link-manager' ),
			'search_items'       => __( 'Search Affiliate Links', 'custom-affiliate-link-manager' ),
			'parent_item_colon'  => __( 'Parent Affiliate Links:', 'custom-affiliate-link-manager' ),
			'not_found'          => __( 'No affiliate links found.', 'custom-affiliate-link-manager' ),
			'not_found_in_trash' => __( 'No affiliate links found in Trash.', 'custom-affiliate-link-manager' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'go' ), // Default slug prefix
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-admin-links',
			'supports'           => array( 'title', 'slug' ),
			'show_in_nav_menus'  => false,
			'show_in_admin_bar'  => true,
			'exclude_from_search' => true,
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
		);

		register_post_type( 'calm_affiliate_link', $args );
	}

	/**
	 * Register Taxonomy
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Link Categories', 'taxonomy general name', 'custom-affiliate-link-manager' ),
			'singular_name'     => _x( 'Link Category', 'taxonomy singular name', 'custom-affiliate-link-manager' ),
			'search_items'      => __( 'Search Link Categories', 'custom-affiliate-link-manager' ),
			'all_items'         => __( 'All Link Categories', 'custom-affiliate-link-manager' ),
			'parent_item'       => __( 'Parent Link Category', 'custom-affiliate-link-manager' ),
			'parent_item_colon' => __( 'Parent Link Category:', 'custom-affiliate-link-manager' ),
			'edit_item'         => __( 'Edit Link Category', 'custom-affiliate-link-manager' ),
			'update_item'       => __( 'Update Link Category', 'custom-affiliate-link-manager' ),
			'add_new_item'      => __( 'Add New Link Category', 'custom-affiliate-link-manager' ),
			'new_item_name'     => __( 'New Link Category Name', 'custom-affiliate-link-manager' ),
			'menu_name'         => __( 'Link Categories', 'custom-affiliate-link-manager' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'link-category' ),
		);

		register_taxonomy( 'calm_link_category', array( 'calm_affiliate_link' ), $args );
	}

	/**
	 * Activation callback
	 */
	public static function activate() {
		// Flush rewrite rules.
		self::get_instance()->register_post_type();
		flush_rewrite_rules();
	}

	/**
	 * Deactivation callback
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
