<?php
/**
 * Status Meta Box Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="calm-status-box">
	<label for="calm_link_status"><?php esc_html_e( 'Status:', 'custom-affiliate-link-manager' ); ?></label>
	<select id="calm_link_status" name="calm_link_status">
		<option value="active" <?php selected( $link_status, 'active' ); ?>><?php esc_html_e( 'Active', 'custom-affiliate-link-manager' ); ?></option>
		<option value="inactive" <?php selected( $link_status, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'custom-affiliate-link-manager' ); ?></option>
	</select>
	<p class="description"><?php esc_html_e( 'Inactive links will show a 404 error when accessed.', 'custom-affiliate-link-manager' ); ?></p>
</div>
