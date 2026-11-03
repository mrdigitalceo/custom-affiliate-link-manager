<?php
/**
 * Dashboard Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap calm-dashboard">
	<h1 class="calm-dashboard-title"><?php esc_html_e( 'Custom Affiliate Link Manager Dashboard', 'custom-affiliate-link-manager' ); ?></h1>
	<div class="calm-dashboard-content">
		<div class="calm-dashboard-card">
			<div class="calm-card-header">
				<h2><?php esc_html_e( 'Plugin Information', 'custom-affiliate-link-manager' ); ?></h2>
			</div>
			<div class="calm-card-body">
				<p><span class="calm-label"><?php esc_html_e( 'Plugin Name:', 'custom-affiliate-link-manager' ); ?></span> Custom Affiliate Link Manager</p>
				<p><span class="calm-label"><?php esc_html_e( 'Description:', 'custom-affiliate-link-manager' ); ?></span> <?php esc_html_e( 'Manage and cloak affiliate links with advanced features, without external APIs.', 'custom-affiliate-link-manager' ); ?></p>
				<p><span class="calm-label"><?php esc_html_e( 'Version:', 'custom-affiliate-link-manager' ); ?></span> <?php echo esc_html( CALM_VERSION ); ?></p>
				<p><span class="calm-label"><?php esc_html_e( 'Author:', 'custom-affiliate-link-manager' ); ?></span> <a href="https://www.youtube.com/@themrdigitalceo" target="_blank">Mr Digital CEO</a></p>
			</div>
		</div>
		<div class="calm-dashboard-card">
			<div class="calm-card-header">
				<h2><?php esc_html_e( 'Admin Information', 'custom-affiliate-link-manager' ); ?></h2>
			</div>
			<div class="calm-card-body">
				<p><span class="calm-label"><?php esc_html_e( 'Name:', 'custom-affiliate-link-manager' ); ?></span> <?php echo esc_html( $current_user->display_name ); ?></p>
				<p><span class="calm-label"><?php esc_html_e( 'Email:', 'custom-affiliate-link-manager' ); ?></span> <?php echo esc_html( $current_user->user_email ); ?></p>
			</div>
		</div>
		<div class="calm-dashboard-card">
			<div class="calm-card-header">
				<h2><?php esc_html_e( 'Quick Stats', 'custom-affiliate-link-manager' ); ?></h2>
			</div>
			<div class="calm-card-body">
				<p><span class="calm-label"><?php esc_html_e( 'Total Links:', 'custom-affiliate-link-manager' ); ?></span> <?php echo esc_html( $total_links ); ?></p>
				<p><span class="calm-label"><?php esc_html_e( 'Total Clicks:', 'custom-affiliate-link-manager' ); ?></span> <?php echo esc_html( $total_clicks ); ?></p>
			</div>
		</div>
	</div>
</div>
