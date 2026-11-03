<?php
/**
 * Settings Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Plugin Settings', 'custom-affiliate-link-manager' ); ?></h1>
	
	<form method="post">
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Default Redirect Type', 'custom-affiliate-link-manager' ); ?></th>
				<td>
					<select name="calm_default_redirect_type">
						<option value="301" <?php selected( $settings['default_redirect_type'], '301' ); ?>>301 (Permanent)</option>
						<option value="302" <?php selected( $settings['default_redirect_type'], '302' ); ?>>302 (Found)</option>
						<option value="307" <?php selected( $settings['default_redirect_type'], '307' ); ?>>307 (Temporary)</option>
					</select>
					<p class="description"><?php esc_html_e( 'Default redirect type for new links.', 'custom-affiliate-link-manager' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable Click Tracking', 'custom-affiliate-link-manager' ); ?></th>
				<td>
					<input type="checkbox" name="calm_enable_click_tracking" value="1" <?php checked( $settings['enable_click_tracking'], 1 ); ?>>
					<p class="description"><?php esc_html_e( 'Track clicks and maintain click history for all links.', 'custom-affiliate-link-manager' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable Geo-Targeting', 'custom-affiliate-link-manager' ); ?></th>
				<td>
					<input type="checkbox" name="calm_enable_geo_targeting" value="1" <?php checked( $settings['enable_geo_targeting'], 1 ); ?>>
					<p class="description"><?php esc_html_e( 'Allow different target URLs based on visitor\'s country.', 'custom-affiliate-link-manager' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable A/B Testing', 'custom-affiliate-link-manager' ); ?></th>
				<td>
					<input type="checkbox" name="calm_enable_ab_testing" value="1" <?php checked( $settings['enable_ab_testing'], 1 ); ?>>
					<p class="description"><?php esc_html_e( 'Allow multiple target URLs with traffic distribution.', 'custom-affiliate-link-manager' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Custom 404 Message', 'custom-affiliate-link-manager' ); ?></th>
				<td>
					<textarea name="calm_custom_404_message" rows="3" cols="50"><?php echo esc_textarea( $settings['custom_404_message'] ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Message to show when a link is not found or inactive.', 'custom-affiliate-link-manager' ); ?></p>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" name="calm_save_settings" class="button button-primary" value="<?php esc_attr_e( 'Save Settings', 'custom-affiliate-link-manager' ); ?>">
		</p>
	</form>
</div>
