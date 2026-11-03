<?php
/**
 * Admin Class
 *
 * @package CALM
 */

namespace CALM;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 */
class Admin {

	/**
	 * Instance of this class.
	 *
	 * @var Admin
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return Admin
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
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_calm_affiliate_link', array( $this, 'save_meta_box_data' ) );
		add_filter( 'manage_calm_affiliate_link_posts_columns', array( $this, 'manage_columns' ) );
		add_action( 'manage_calm_affiliate_link_posts_custom_column', array( $this, 'custom_column_content' ), 10, 2 );
		add_filter( 'bulk_actions-edit-calm_affiliate_link', array( $this, 'add_bulk_actions' ) );
		add_filter( 'handle_bulk_actions-edit-calm_affiliate_link', array( $this, 'handle_bulk_actions' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'wp_ajax_calm_preview_link', array( $this, 'handle_preview_request' ) );
	}

	/**
	 * Add menu pages
	 */
	public function add_menu_pages() {
		add_submenu_page(
			'edit.php?post_type=calm_affiliate_link',
			__( 'Dashboard', 'custom-affiliate-link-manager' ),
			__( 'Dashboard', 'custom-affiliate-link-manager' ),
			'manage_options',
			'calm-dashboard',
			array( $this, 'dashboard_page_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=calm_affiliate_link',
			__( 'Analytics & Reports', 'custom-affiliate-link-manager' ),
			__( 'Analytics', 'custom-affiliate-link-manager' ),
			'manage_options',
			'calm-analytics',
			array( $this, 'analytics_page_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=calm_affiliate_link',
			__( 'Export/Import Links', 'custom-affiliate-link-manager' ),
			__( 'Export/Import', 'custom-affiliate-link-manager' ),
			'manage_options',
			'calm-export-import',
			array( $this, 'export_import_page_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=calm_affiliate_link',
			__( 'Link Health Check', 'custom-affiliate-link-manager' ),
			__( 'Health Check', 'custom-affiliate-link-manager' ),
			'manage_options',
			'calm-health-check',
			array( $this, 'health_check_page_callback' )
		);

		add_submenu_page(
			'edit.php?post_type=calm_affiliate_link',
			__( 'Settings', 'custom-affiliate-link-manager' ),
			__( 'Settings', 'custom-affiliate-link-manager' ),
			'manage_options',
			'calm-settings',
			array( $this, 'settings_page_callback' )
		);
	}

	/**
	 * Enqueue assets
	 */
	public function enqueue_assets( $hook ) {
		wp_enqueue_style( 'calm-admin-css', CALM_URL . 'assets/css/admin.css', array(), CALM_VERSION );
		wp_enqueue_script( 'calm-admin-js', CALM_URL . 'assets/js/admin.js', array( 'jquery' ), CALM_VERSION, true );

		wp_localize_script( 'calm-admin-js', 'calm_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'calm_admin_nonce' ),
		) );
	}

	/**
	 * Add meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'calm_meta_box',
			__( 'Affiliate Link Settings', 'custom-affiliate-link-manager' ),
			array( $this, 'meta_box_callback' ),
			'calm_affiliate_link',
			'normal',
			'high'
		);

		add_meta_box(
			'calm_link_status_meta_box',
			__( 'Link Status', 'custom-affiliate-link-manager' ),
			array( $this, 'link_status_meta_box_callback' ),
			'calm_affiliate_link',
			'side',
			'high'
		);

		add_meta_box(
			'calm_qr_code_meta_box',
			__( 'QR Code', 'custom-affiliate-link-manager' ),
			array( $this, 'qr_code_meta_box_callback' ),
			'calm_affiliate_link',
			'side',
			'default'
		);
	}

	/**
	 * Meta box callback
	 */
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'calm_save_meta_box', 'calm_meta_box_nonce' );
		$data = array(
			'target_url'       => get_post_meta( $post->ID, 'calm_target_url', true ),
			'redirect_type'    => get_post_meta( $post->ID, 'calm_redirect_type', true ),
			'notes'            => get_post_meta( $post->ID, 'calm_notes', true ),
			'click_count'      => get_post_meta( $post->ID, 'calm_click_count', true ),
			'start_date'       => get_post_meta( $post->ID, 'calm_start_date', true ),
			'expiration_date'  => get_post_meta( $post->ID, 'calm_expiration_date', true ),
			'custom_params'    => get_post_meta( $post->ID, 'calm_custom_params', true ),
			'click_history'    => get_post_meta( $post->ID, 'calm_click_history', true ),
			'geo_targets'      => get_post_meta( $post->ID, 'calm_geo_targets', true ),
			'ab_testing'       => get_post_meta( $post->ID, 'calm_ab_testing', true ),
			'redirect_message' => get_post_meta( $post->ID, 'calm_redirect_message', true ),
			'pretty_link'      => home_url( '/' . $post->post_name ),
		);

		include CALM_PATH . 'templates/admin/meta-box.php';
	}

	/**
	 * Link status meta box callback
	 */
	public function link_status_meta_box_callback( $post ) {
		$link_status = get_post_meta( $post->ID, 'calm_link_status', true );
		if ( empty( $link_status ) ) {
			$link_status = 'active';
		}
		include CALM_PATH . 'templates/admin/status-meta-box.php';
	}

	/**
	 * QR code meta box callback
	 */
	public function qr_code_meta_box_callback( $post ) {
		$pretty_link = home_url( '/' . $post->post_name );
		$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode( $pretty_link );
		include CALM_PATH . 'templates/admin/qr-meta-box.php';
	}

	/**
	 * Save meta box data
	 */
	public function save_meta_box_data( $post_id ) {
		if ( ! isset( $_POST['calm_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['calm_meta_box_nonce'], 'calm_save_meta_box' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'calm_target_url'       => 'esc_url_raw',
			'calm_redirect_type'    => 'sanitize_text_field',
			'calm_notes'            => 'sanitize_textarea_field',
			'calm_start_date'       => 'sanitize_text_field',
			'calm_expiration_date'  => 'sanitize_text_field',
			'calm_custom_params'    => 'sanitize_textarea_field',
			'calm_redirect_message' => 'sanitize_textarea_field',
			'calm_link_status'      => 'sanitize_text_field',
		);

		foreach ( $fields as $field => $sanitize_func ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, $sanitize_func( $_POST[ $field ] ) );
			}
		}

		if ( isset( $_POST['calm_geo_targets'] ) && is_array( $_POST['calm_geo_targets'] ) ) {
			$geo_targets = array();
			foreach ( $_POST['calm_geo_targets'] as $geo ) {
				if ( ! empty( $geo['country'] ) && ! empty( $geo['url'] ) ) {
					$geo_targets[] = array(
						'country' => sanitize_text_field( $geo['country'] ),
						'url'     => esc_url_raw( $geo['url'] ),
					);
				}
			}
			update_post_meta( $post_id, 'calm_geo_targets', $geo_targets );
		}

		if ( isset( $_POST['calm_ab_testing'] ) && is_array( $_POST['calm_ab_testing'] ) ) {
			$ab_testing       = array();
			$total_percentage = 0;
			foreach ( $_POST['calm_ab_testing'] as $ab ) {
				if ( ! empty( $ab['url'] ) && isset( $ab['percentage'] ) ) {
					$percentage        = (int) $ab['percentage'];
					$total_percentage += $percentage;
					$ab_testing[]      = array(
						'url'        => esc_url_raw( $ab['url'] ),
						'percentage' => $percentage,
					);
				}
			}
			if ( $total_percentage <= 100 ) {
				update_post_meta( $post_id, 'calm_ab_testing', $ab_testing );
			}
		}

		if ( ! metadata_exists( 'post', $post_id, 'calm_click_count' ) ) {
			update_post_meta( $post_id, 'calm_click_count', 0 );
		}
		if ( ! metadata_exists( 'post', $post_id, 'calm_click_history' ) ) {
			update_post_meta( $post_id, 'calm_click_history', array() );
		}
	}

	/**
	 * Manage columns
	 */
	public function manage_columns( $columns ) {
		$columns['pretty_link'] = __( 'Pretty Link', 'custom-affiliate-link-manager' );
		$columns['clicks']      = __( 'Clicks', 'custom-affiliate-link-manager' );
		$columns['link_status'] = __( 'Status', 'custom-affiliate-link-manager' );
		return $columns;
	}

	/**
	 * Custom column content
	 */
	public function custom_column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'pretty_link':
				$slug        = get_post_field( 'post_name', $post_id );
				$pretty_link = home_url( '/' . $slug );
				echo '<a href="' . esc_url( $pretty_link ) . '" target="_blank">' . esc_html( $pretty_link ) . '</a>';
				break;
			case 'clicks':
				$click_count = get_post_meta( $post_id, 'calm_click_count', true );
				echo esc_html( $click_count ? $click_count : 0 );
				break;
			case 'link_status':
				$status       = get_post_meta( $post_id, 'calm_link_status', true );
				$status       = empty( $status ) ? 'active' : $status;
				$status_class = $status === 'active' ? 'status-active' : 'status-inactive';
				echo '<span class="' . esc_attr( $status_class ) . '">' . esc_html( ucfirst( $status ) ) . '</span>';
				break;
		}
	}

	/**
	 * Add bulk actions
	 */
	public function add_bulk_actions( $bulk_actions ) {
		$bulk_actions['calm_bulk_activate']     = __( 'Activate Links', 'custom-affiliate-link-manager' );
		$bulk_actions['calm_bulk_deactivate']   = __( 'Deactivate Links', 'custom-affiliate-link-manager' );
		$bulk_actions['calm_bulk_reset_clicks'] = __( 'Reset Click Counts', 'custom-affiliate-link-manager' );
		return $bulk_actions;
	}

	/**
	 * Handle bulk actions
	 */
	public function handle_bulk_actions( $redirect_to, $doaction, $post_ids ) {
		if ( $doaction === 'calm_bulk_activate' ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, 'calm_link_status', 'active' );
			}
			$redirect_to = add_query_arg( 'bulk_activated', count( $post_ids ), $redirect_to );
		} elseif ( $doaction === 'calm_bulk_deactivate' ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, 'calm_link_status', 'inactive' );
			}
			$redirect_to = add_query_arg( 'bulk_deactivated', count( $post_ids ), $redirect_to );
		} elseif ( $doaction === 'calm_bulk_reset_clicks' ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, 'calm_click_count', 0 );
				update_post_meta( $post_id, 'calm_click_history', array() );
			}
			$redirect_to = add_query_arg( 'bulk_reset', count( $post_ids ), $redirect_to );
		}
		return $redirect_to;
	}

	/**
	 * Admin notices
	 */
	public function admin_notices() {
		if ( ! empty( $_REQUEST['bulk_activated'] ) ) {
			$count = intval( $_REQUEST['bulk_activated'] );
			printf( '<div class="updated notice is-dismissible"><p>' . esc_html( _n( '%s link activated.', '%s links activated.', $count, 'custom-affiliate-link-manager' ) ) . '</p></div>', $count );
		}
		if ( ! empty( $_REQUEST['bulk_deactivated'] ) ) {
			$count = intval( $_REQUEST['bulk_deactivated'] );
			printf( '<div class="updated notice is-dismissible"><p>' . esc_html( _n( '%s link deactivated.', '%s links deactivated.', $count, 'custom-affiliate-link-manager' ) ) . '</p></div>', $count );
		}
		if ( ! empty( $_REQUEST['bulk_reset'] ) ) {
			$count = intval( $_REQUEST['bulk_reset'] );
			printf( '<div class="updated notice is-dismissible"><p>' . esc_html( _n( '%s link click count reset.', '%s links click counts reset.', $count, 'custom-affiliate-link-manager' ) ) . '</p></div>', $count );
		}
		if ( isset( $_GET['imported'] ) && $_GET['imported'] > 0 ) {
			$count = intval( $_GET['imported'] );
			printf( '<div class="notice notice-success is-dismissible"><p>' . esc_html( _n( '%d link imported successfully.', '%d links imported successfully.', $count, 'custom-affiliate-link-manager' ) ) . '</p></div>', $count );
		}
	}

	/**
	 * Dashboard page callback
	 */
	public function dashboard_page_callback() {
		$current_user = wp_get_current_user();
		$total_links  = wp_count_posts( 'calm_affiliate_link' )->publish;
		$total_clicks = 0;
		$links        = get_posts( array( 'post_type' => 'calm_affiliate_link', 'posts_per_page' => -1 ) );
		foreach ( $links as $link ) {
			$total_clicks += (int) get_post_meta( $link->ID, 'calm_click_count', true );
		}
		include CALM_PATH . 'templates/admin/dashboard.php';
	}

	/**
	 * Analytics page callback
	 */
	public function analytics_page_callback() {
		$links = get_posts( array( 'post_type' => 'calm_affiliate_link', 'posts_per_page' => -1 ) );
		$total_links    = count( $links );
		$total_clicks   = 0;
		$active_links   = 0;
		$top_performers = array();

		foreach ( $links as $link ) {
			$clicks = (int) get_post_meta( $link->ID, 'calm_click_count', true );
			$status = get_post_meta( $link->ID, 'calm_link_status', true );
			$total_clicks += $clicks;
			if ( $status !== 'inactive' ) {
				$active_links++;
			}
			$top_performers[] = array(
				'title'  => $link->post_title,
				'clicks' => $clicks,
				'url'    => home_url( '/' . $link->post_name ),
			);
		}

		usort( $top_performers, function( $a, $b ) {
			return $b['clicks'] - $a['clicks'];
		} );

		$top_performers = array_slice( $top_performers, 0, 10 );
		$avg_clicks     = $total_links > 0 ? round( $total_clicks / $total_links, 2 ) : 0;

		include CALM_PATH . 'templates/admin/analytics.php';
	}

	/**
	 * Export/Import page callback
	 */
	public function export_import_page_callback() {
		if ( isset( $_POST['calm_export_links'] ) ) {
			$this->export_links();
		}
		if ( isset( $_POST['calm_import_links'] ) && isset( $_FILES['calm_import_file'] ) ) {
			$this->import_links();
		}
		include CALM_PATH . 'templates/admin/export-import.php';
	}

	/**
	 * Health check page callback
	 */
	public function health_check_page_callback() {
		if ( isset( $_POST['calm_check_links'] ) ) {
			$this->perform_health_check();
		}
		$health_results = get_option( 'calm_health_check_results', array() );
		include CALM_PATH . 'templates/admin/health-check.php';
	}

	/**
	 * Settings page callback
	 */
	public function settings_page_callback() {
		if ( isset( $_POST['calm_save_settings'] ) ) {
			update_option( 'calm_default_redirect_type', sanitize_text_field( $_POST['calm_default_redirect_type'] ) );
			update_option( 'calm_enable_click_tracking', isset( $_POST['calm_enable_click_tracking'] ) ? 1 : 0 );
			update_option( 'calm_enable_geo_targeting', isset( $_POST['calm_enable_geo_targeting'] ) ? 1 : 0 );
			update_option( 'calm_enable_ab_testing', isset( $_POST['calm_enable_ab_testing'] ) ? 1 : 0 );
			update_option( 'calm_custom_404_message', sanitize_textarea_field( $_POST['calm_custom_404_message'] ) );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully!', 'custom-affiliate-link-manager' ) . '</p></div>';
		}

		$settings = array(
			'default_redirect_type' => get_option( 'calm_default_redirect_type', '301' ),
			'enable_click_tracking' => get_option( 'calm_enable_click_tracking', 1 ),
			'enable_geo_targeting'  => get_option( 'calm_enable_geo_targeting', 1 ),
			'enable_ab_testing'     => get_option( 'calm_enable_ab_testing', 1 ),
			'custom_404_message'    => get_option( 'calm_custom_404_message', __( 'The requested link was not found.', 'custom-affiliate-link-manager' ) ),
		);

		include CALM_PATH . 'templates/admin/settings.php';
	}

	/**
	 * Export links
	 */
	private function export_links() {
		$links = get_posts( array( 'post_type' => 'calm_affiliate_link', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
		$filename = 'affiliate-links-' . date( 'Y-m-d' ) . '.csv';
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Title', 'Slug', 'Target URL', 'Notes', 'Clicks', 'Status' ) );
		foreach ( $links as $link ) {
			fputcsv( $output, array(
				$link->post_title,
				$link->post_name,
				get_post_meta( $link->ID, 'calm_target_url', true ),
				get_post_meta( $link->ID, 'calm_notes', true ),
				get_post_meta( $link->ID, 'calm_click_count', true ),
				get_post_meta( $link->ID, 'calm_link_status', true ),
			) );
		}
		fclose( $output );
		exit;
	}

	/**
	 * Import links
	 */
	private function import_links() {
		if ( ! isset( $_FILES['calm_import_file'] ) || $_FILES['calm_import_file']['error'] !== UPLOAD_ERR_OK ) {
			return;
		}
		$file = $_FILES['calm_import_file']['tmp_name'];
		$handle = fopen( $file, 'r' );
		if ( $handle === false ) {
			return;
		}
		$imported = 0;
		fgetcsv( $handle ); // Skip header
		while ( ( $data = fgetcsv( $handle ) ) !== false ) {
			if ( count( $data ) >= 3 ) {
				$post_id = wp_insert_post( array(
					'post_title'  => sanitize_text_field( $data[0] ),
					'post_name'   => sanitize_title( $data[1] ),
					'post_type'   => 'calm_affiliate_link',
					'post_status' => 'publish',
				) );
				if ( $post_id ) {
					update_post_meta( $post_id, 'calm_target_url', esc_url_raw( $data[2] ) );
					update_post_meta( $post_id, 'calm_notes', isset( $data[3] ) ? sanitize_textarea_field( $data[3] ) : '' );
					update_post_meta( $post_id, 'calm_link_status', isset( $data[5] ) ? sanitize_text_field( $data[5] ) : 'active' );
					update_post_meta( $post_id, 'calm_click_count', 0 );
					update_post_meta( $post_id, 'calm_click_history', array() );
					$imported++;
				}
			}
		}
		fclose( $handle );
		wp_redirect( admin_url( 'edit.php?post_type=calm_affiliate_link&page=calm-export-import&imported=' . $imported ) );
		exit;
	}

	/**
	 * Perform health check
	 */
	private function perform_health_check() {
		$links = get_posts( array( 'post_type' => 'calm_affiliate_link', 'posts_per_page' => -1 ) );
		$results = array();
		foreach ( $links as $link ) {
			$target_url = get_post_meta( $link->ID, 'calm_target_url', true );
			if ( empty( $target_url ) ) {
				$results[] = array( 'post_id' => $link->ID, 'title' => $link->post_title, 'target_url' => '', 'status' => 'error', 'response_code' => 'No URL' );
				continue;
			}
			$response = wp_remote_head( $target_url, array( 'timeout' => 10 ) );
			if ( is_wp_error( $response ) ) {
				$results[] = array( 'post_id' => $link->ID, 'title' => $link->post_title, 'target_url' => $target_url, 'status' => 'error', 'response_code' => 'Connection Error' );
			} else {
				$code = wp_remote_retrieve_response_code( $response );
				$status = $code >= 400 ? 'error' : ( $code >= 300 ? 'warning' : 'healthy' );
				$results[] = array( 'post_id' => $link->ID, 'title' => $link->post_title, 'target_url' => $target_url, 'status' => $status, 'response_code' => $code );
			}
		}
		update_option( 'calm_health_check_results', array( 'timestamp' => time(), 'results' => $results ) );
	}

	/**
	 * Handle preview request
	 */
	public function handle_preview_request() {
		check_ajax_referer( 'calm_admin_nonce', 'nonce' );
		$post_id = intval( $_POST['post_id'] );
		$post = get_post( $post_id );
		if ( ! $post || $post->post_type !== 'calm_affiliate_link' ) {
			wp_send_json_error( 'Invalid link' );
		}
		wp_send_json_success( array(
			'pretty_link' => home_url( '/' . $post->post_name ),
			'target_url'  => get_post_meta( $post_id, 'calm_target_url', true ),
			'title'       => $post->post_title,
		) );
	}
}
