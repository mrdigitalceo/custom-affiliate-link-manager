<?php
/**
 * Analytics Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Analytics & Reports', 'custom-affiliate-link-manager' ); ?></h1>
	
	<div class="calm-analytics-grid">
		<div class="calm-stat-card">
			<h3><?php esc_html_e( 'Total Links', 'custom-affiliate-link-manager' ); ?></h3>
			<div class="calm-stat-number"><?php echo esc_html( $total_links ); ?></div>
		</div>
		<div class="calm-stat-card">
			<h3><?php esc_html_e( 'Active Links', 'custom-affiliate-link-manager' ); ?></h3>
			<div class="calm-stat-number"><?php echo esc_html( $active_links ); ?></div>
		</div>
		<div class="calm-stat-card">
			<h3><?php esc_html_e( 'Total Clicks', 'custom-affiliate-link-manager' ); ?></h3>
			<div class="calm-stat-number"><?php echo esc_html( $total_clicks ); ?></div>
		</div>
		<div class="calm-stat-card">
			<h3><?php esc_html_e( 'Average Clicks', 'custom-affiliate-link-manager' ); ?></h3>
			<div class="calm-stat-number"><?php echo esc_html( $avg_clicks ); ?></div>
		</div>
	</div>
	
	<div class="calm-analytics-section">
		<h2><?php esc_html_e( 'Top Performing Links', 'custom-affiliate-link-manager' ); ?></h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Link Title', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Clicks', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Pretty URL', 'custom-affiliate-link-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $top_performers ) ) : ?>
					<?php foreach ( $top_performers as $performer ) : ?>
					<tr>
						<td><?php echo esc_html( $performer['title'] ); ?></td>
						<td><strong><?php echo esc_html( $performer['clicks'] ); ?></strong></td>
						<td><a href="<?php echo esc_url( $performer['url'] ); ?>" target="_blank"><?php echo esc_html( $performer['url'] ); ?></a></td>
					</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="3"><?php esc_html_e( 'No links found.', 'custom-affiliate-link-manager' ); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
