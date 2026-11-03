<?php
/**
 * Health Check Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Link Health Check', 'custom-affiliate-link-manager' ); ?></h1>
	<p><?php esc_html_e( 'Check if your affiliate links are working properly and their target URLs are accessible.', 'custom-affiliate-link-manager' ); ?></p>
	
	<form method="post">
		<button type="submit" name="calm_check_links" class="button button-primary"><?php esc_html_e( 'Run Health Check', 'custom-affiliate-link-manager' ); ?></button>
	</form>
	
	<?php if ( ! empty( $health_results ) ) : ?>
	<div class="calm-health-results">
		<h2><?php esc_html_e( 'Health Check Results', 'custom-affiliate-link-manager' ); ?></h2>
		<p><small><?php esc_html_e( 'Last checked:', 'custom-affiliate-link-manager' ); ?> <?php echo esc_html( date( 'Y-m-d H:i:s', $health_results['timestamp'] ) ); ?></small></p>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Link Title', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Status', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Response Code', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Target URL', 'custom-affiliate-link-manager' ); ?></th>
					<th><?php esc_html_e( 'Action', 'custom-affiliate-link-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $health_results['results'] as $result ) : ?>
				<tr>
					<td><?php echo esc_html( $result['title'] ); ?></td>
					<td>
						<span class="calm-status-<?php echo esc_attr( $result['status'] ); ?>">
							<?php echo esc_html( ucfirst( $result['status'] ) ); ?>
						</span>
					</td>
					<td><?php echo esc_html( $result['response_code'] ); ?></td>
					<td><a href="<?php echo esc_url( $result['target_url'] ); ?>" target="_blank"><?php echo esc_html( $result['target_url'] ); ?></a></td>
					<td><a href="<?php echo esc_url( admin_url( 'post.php?post=' . $result['post_id'] . '&action=edit' ) ); ?>"><?php esc_html_e( 'Edit', 'custom-affiliate-link-manager' ); ?></a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
</div>

<style>
	.calm-health-results {
		margin-top: 20px;
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 8px;
		padding: 20px;
		box-shadow: 0 2px 4px rgba(0,0,0,0.05);
	}
	.calm-status-healthy { color: #46b450; font-weight: bold; }
	.calm-status-warning { color: #ffb900; font-weight: bold; }
	.calm-status-error { color: #dc3232; font-weight: bold; }
</style>
