<?php
/**
 * Frontend Class
 *
 * @package CALM
 */

namespace CALM;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Class
 */
class Frontend {

	/**
	 * Instance of this class.
	 *
	 * @var Frontend
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @return Frontend
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
		add_action( 'init', array( $this, 'handle_redirection' ) );
		add_shortcode( 'affiliate_link', array( $this, 'affiliate_link_shortcode' ) );
	}

	/**
	 * Handle redirection logic
	 */
	public function handle_redirection() {
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		$requested_path = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
		if ( empty( $requested_path ) ) {
			return;
		}

		$post = get_page_by_path( $requested_path, OBJECT, 'calm_affiliate_link' );

		// Permalinks include the post type's rewrite slug (e.g. "go/my-link"),
		// but the stored post path is just "my-link" — retry without the prefix.
		if ( ! $post ) {
			$calm_post_type = get_post_type_object( 'calm_affiliate_link' );
			$calm_prefix    = ( $calm_post_type && ! empty( $calm_post_type->rewrite['slug'] ) ) ? trim( $calm_post_type->rewrite['slug'], '/' ) : 'go';
			if ( 0 === strpos( $requested_path, $calm_prefix . '/' ) ) {
				$post = get_page_by_path( substr( $requested_path, strlen( $calm_prefix ) + 1 ), OBJECT, 'calm_affiliate_link' );
			}
		}

		if ( ! $post ) {
			return;
		}

		// Check if link is active.
		$link_status = get_post_meta( $post->ID, 'calm_link_status', true );
		if ( 'inactive' === $link_status ) {
			$custom_404_message = get_option( 'calm_custom_404_message', __( 'The requested link was not found.', 'custom-affiliate-link-manager' ) );
			wp_die( esc_html( $custom_404_message ), __( 'Link Inactive', 'custom-affiliate-link-manager' ), array( 'response' => 404 ) );
		}

		$target_url       = get_post_meta( $post->ID, 'calm_target_url', true );
		$redirect_type    = get_post_meta( $post->ID, 'calm_redirect_type', true );
		$start_date       = get_post_meta( $post->ID, 'calm_start_date', true );
		$expiration_date  = get_post_meta( $post->ID, 'calm_expiration_date', true );
		$custom_params    = get_post_meta( $post->ID, 'calm_custom_params', true );
		$geo_targets      = get_post_meta( $post->ID, 'calm_geo_targets', true );
		$ab_testing       = get_post_meta( $post->ID, 'calm_ab_testing', true );
		$redirect_message = get_post_meta( $post->ID, 'calm_redirect_message', true );

		// Check start date.
		if ( $start_date && strtotime( $start_date ) > time() ) {
			wp_die( __( 'This affiliate link is not yet active.', 'custom-affiliate-link-manager' ), __( 'Link Not Active', 'custom-affiliate-link-manager' ), array( 'response' => 403 ) );
		}

		// Check expiration.
		if ( $expiration_date && strtotime( $expiration_date ) < time() ) {
			wp_die( __( 'This affiliate link has expired.', 'custom-affiliate-link-manager' ), __( 'Link Expired', 'custom-affiliate-link-manager' ), array( 'response' => 410 ) );
		}

		// Geo-Targeting.
		if ( get_option( 'calm_enable_geo_targeting', 1 ) ) {
			$visitor_country = $this->get_visitor_country();
			if ( $geo_targets && is_array( $geo_targets ) ) {
				foreach ( $geo_targets as $geo ) {
					if ( $geo['country'] === $visitor_country ) {
						$target_url = $geo['url'];
						break;
					}
				}
			}
		}

		// A/B Testing.
		if ( get_option( 'calm_enable_ab_testing', 1 ) ) {
			if ( $ab_testing && is_array( $ab_testing ) ) {
				$rand       = mt_rand( 1, 100 );
				$cumulative = 0;
				foreach ( $ab_testing as $ab ) {
					$cumulative += $ab['percentage'];
					if ( $rand <= $cumulative ) {
						$target_url = $ab['url'];
						break;
					}
				}
			}
		}

		if ( $target_url ) {
			// Append custom parameters.
			if ( $custom_params ) {
				$target_url = add_query_arg( wp_parse_args( $custom_params ), $target_url );
			}

			// Increment click count and record click history.
			if ( get_option( 'calm_enable_click_tracking', 1 ) ) {
				$click_count   = (int) get_post_meta( $post->ID, 'calm_click_count', true );
				$click_history = get_post_meta( $post->ID, 'calm_click_history', true );
				if ( ! is_array( $click_history ) ) {
					$click_history = array();
				}
				$click_history[] = time();
				update_post_meta( $post->ID, 'calm_click_count', $click_count + 1 );
				update_post_meta( $post->ID, 'calm_click_history', $click_history );
			}

			// Show redirect message if set.
			if ( $redirect_message ) {
				include CALM_PATH . 'templates/frontend/redirect-message.php';
				exit;
			}

			// Use default redirect type if not set.
			if ( empty( $redirect_type ) ) {
				$redirect_type = get_option( 'calm_default_redirect_type', '301' );
			}

			wp_redirect( $target_url, $redirect_type );
			exit;
		}
	}

	/**
	 * Get visitor country (Simplified)
	 */
	private function get_visitor_country() {
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( '127.0.0.1' === $ip || '::1' === $ip ) {
			return 'US';
		}
		// In a real scenario, use a proper geo-IP library.
		return 'US';
	}

	/**
	 * Affiliate link shortcode
	 */
	public function affiliate_link_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id'     => '',
			'slug'   => '',
			'text'   => __( 'Click here', 'custom-affiliate-link-manager' ),
			'class'  => '',
			'target' => '_blank',
			'rel'    => 'nofollow',
		), $atts );

		$link_url = '';
		if ( ! empty( $atts['id'] ) ) {
			$post = get_post( $atts['id'] );
			if ( $post && 'calm_affiliate_link' === $post->post_type ) {
				$link_url = home_url( '/' . $post->post_name );
			}
		} elseif ( ! empty( $atts['slug'] ) ) {
			$post = get_page_by_path( $atts['slug'], OBJECT, 'calm_affiliate_link' );
			if ( $post ) {
				$link_url = home_url( '/' . $post->post_name );
			}
		}

		if ( empty( $link_url ) ) {
			return '<span style="color: red;">' . esc_html__( '[Invalid affiliate link]', 'custom-affiliate-link-manager' ) . '</span>';
		}

		return sprintf(
			'<a href="%s" class="%s" target="%s" rel="%s">%s</a>',
			esc_url( $link_url ),
			esc_attr( $atts['class'] ),
			esc_attr( $atts['target'] ),
			esc_attr( $atts['rel'] ),
			esc_html( $atts['text'] )
		);
	}
}
