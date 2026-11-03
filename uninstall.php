<?php
/**
 * Uninstall Plugin
 *
 * @package CALM
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Clean up plugin data
 */
function calm_uninstall() {
	// Delete options.
	delete_option( 'calm_default_redirect_type' );
	delete_option( 'calm_enable_click_tracking' );
	delete_option( 'calm_enable_geo_targeting' );
	delete_option( 'calm_enable_ab_testing' );
	delete_option( 'calm_custom_404_message' );
	delete_option( 'calm_health_check_results' );

	// Delete all posts of type calm_affiliate_link.
	$links = get_posts( array(
		'post_type'   => 'calm_affiliate_link',
		'numberposts' => -1,
		'post_status' => 'any',
	) );

	if ( ! empty( $links ) ) {
		foreach ( $links as $link ) {
			wp_delete_post( $link->ID, true );
		}
	}

	// Delete taxonomies.
	$terms = get_terms( array(
		'taxonomy'   => 'calm_link_category',
		'hide_empty' => false,
	) );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			wp_delete_term( $term->term_id, 'calm_link_category' );
		}
	}
}

calm_uninstall();
