<?php
/**
 * Meta Box Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="calm-meta-box">
	<div class="calm-tabs">
		<ul>
			<li class="calm-tab active" data-tab="basic"><?php esc_html_e( 'Basic', 'custom-affiliate-link-manager' ); ?></li>
			<li class="calm-tab" data-tab="advanced"><?php esc_html_e( 'Advanced', 'custom-affiliate-link-manager' ); ?></li>
			<li class="calm-tab" data-tab="analytics"><?php esc_html_e( 'Analytics', 'custom-affiliate-link-manager' ); ?></li>
			<li class="calm-tab" data-tab="geo-targeting"><?php esc_html_e( 'Geo-Targeting', 'custom-affiliate-link-manager' ); ?></li>
			<li class="calm-tab" data-tab="ab-testing"><?php esc_html_e( 'A/B Testing', 'custom-affiliate-link-manager' ); ?></li>
		</ul>
	</div>
	<div class="calm-tab-content" id="basic">
		<div class="calm-section">
			<label for="calm_redirect_type"><?php esc_html_e( 'Redirection Type', 'custom-affiliate-link-manager' ); ?></label>
			<select id="calm_redirect_type" name="calm_redirect_type">
				<option value="301" <?php selected( $data['redirect_type'], '301' ); ?>>301 (Permanent)</option>
				<option value="302" <?php selected( $data['redirect_type'], '302' ); ?>>302 (Found)</option>
				<option value="307" <?php selected( $data['redirect_type'], '307' ); ?>>307 (Temporary)</option>
			</select>
			<p class="description"><?php esc_html_e( 'Choose the type of redirect for this link.', 'custom-affiliate-link-manager' ); ?></p>
		</div>
		<div class="calm-section">
			<label for="calm_target_url"><?php esc_html_e( 'Target URL', 'custom-affiliate-link-manager' ); ?></label>
			<input type="url" id="calm_target_url" name="calm_target_url" value="<?php echo esc_attr( $data['target_url'] ); ?>" placeholder="https://example.com/affiliate-link" required>
		</div>
		<div class="calm-section">
			<label for="calm_pretty_link"><?php esc_html_e( 'Pretty Link', 'custom-affiliate-link-manager' ); ?></label>
			<div class="calm-pretty-link">
				<input type="text" id="calm_pretty_link" value="<?php echo esc_attr( $data['pretty_link'] ); ?>" readonly>
				<button type="button" class="button calm-copy-btn" data-copy-target="calm_pretty_link"><?php esc_html_e( 'Copy', 'custom-affiliate-link-manager' ); ?></button>
			</div>
		</div>
	</div>
	<div class="calm-tab-content" id="advanced" style="display:none;">
		<div class="calm-section">
			<label for="calm_start_date"><?php esc_html_e( 'Start Date', 'custom-affiliate-link-manager' ); ?></label>
			<input type="date" id="calm_start_date" name="calm_start_date" value="<?php echo esc_attr( $data['start_date'] ); ?>">
		</div>
		<div class="calm-section">
			<label for="calm_expiration_date"><?php esc_html_e( 'Expiration Date', 'custom-affiliate-link-manager' ); ?></label>
			<input type="date" id="calm_expiration_date" name="calm_expiration_date" value="<?php echo esc_attr( $data['expiration_date'] ); ?>">
		</div>
		<div class="calm-section">
			<label for="calm_notes"><?php esc_html_e( 'Notes', 'custom-affiliate-link-manager' ); ?></label>
			<textarea id="calm_notes" name="calm_notes"><?php echo esc_textarea( $data['notes'] ); ?></textarea>
		</div>
	</div>
	<div class="calm-tab-content" id="analytics" style="display:none;">
		<div class="calm-section">
			<p><strong><?php esc_html_e( 'Total Clicks:', 'custom-affiliate-link-manager' ); ?></strong> <?php echo esc_html( $data['click_count'] ? $data['click_count'] : 0 ); ?></p>
			<h4><?php esc_html_e( 'Recent Click History', 'custom-affiliate-link-manager' ); ?></h4>
			<ul class="calm-click-history">
				<?php
				if ( ! empty( $data['click_history'] ) && is_array( $data['click_history'] ) ) {
					$history = array_reverse( $data['click_history'] );
					$history = array_slice( $history, 0, 10 );
					foreach ( $history as $time ) {
						echo '<li>' . esc_html( date( 'Y-m-d H:i:s', $time ) ) . '</li>';
					}
				} else {
					echo '<li>' . esc_html__( 'No clicks recorded yet.', 'custom-affiliate-link-manager' ) . '</li>';
				}
				?>
			</ul>
		</div>
	</div>
	<div class="calm-tab-content" id="geo-targeting" style="display:none;">
		<div class="calm-section">
			<p><?php esc_html_e( 'Redirect users to different URLs based on their country.', 'custom-affiliate-link-manager' ); ?></p>
			<div id="calm-geo-targets-container">
				<?php
				if ( ! empty( $data['geo_targets'] ) && is_array( $data['geo_targets'] ) ) {
					foreach ( $data['geo_targets'] as $index => $geo ) {
						?>
						<div class="calm-geo-target">
							<select name="calm_geo_targets[<?php echo $index; ?>][country]">
								<option value="US" <?php selected( $geo['country'], 'US' ); ?>>United States</option>
								<option value="GB" <?php selected( $geo['country'], 'GB' ); ?>>United Kingdom</option>
								<option value="CA" <?php selected( $geo['country'], 'CA' ); ?>>Canada</option>
								<option value="AU" <?php selected( $geo['country'], 'AU' ); ?>>Australia</option>
								<option value="IN" <?php selected( $geo['country'], 'IN' ); ?>>India</option>
							</select>
							<input type="url" name="calm_geo_targets[<?php echo $index; ?>][url]" value="<?php echo esc_url( $geo['url'] ); ?>" placeholder="Target URL">
							<button type="button" class="button calm-remove-row"><?php esc_html_e( 'Remove', 'custom-affiliate-link-manager' ); ?></button>
						</div>
						<?php
					}
				}
				?>
			</div>
			<button type="button" id="calm-add-geo" class="button"><?php esc_html_e( 'Add Geo-Targeting Rule', 'custom-affiliate-link-manager' ); ?></button>
		</div>
	</div>
	<div class="calm-tab-content" id="ab-testing" style="display:none;">
		<div class="calm-section">
			<p><?php esc_html_e( 'Distribute traffic between multiple target URLs for A/B testing.', 'custom-affiliate-link-manager' ); ?></p>
			<div id="calm-ab-testing-container">
				<?php
				if ( ! empty( $data['ab_testing'] ) && is_array( $data['ab_testing'] ) ) {
					foreach ( $data['ab_testing'] as $index => $ab ) {
						?>
						<div class="calm-ab-test">
							<input type="url" name="calm_ab_testing[<?php echo $index; ?>][url]" value="<?php echo esc_url( $ab['url'] ); ?>" placeholder="Target URL">
							<input type="number" name="calm_ab_testing[<?php echo $index; ?>][percentage]" value="<?php echo esc_attr( $ab['percentage'] ); ?>" placeholder="%" min="1" max="100">
							<button type="button" class="button calm-remove-row"><?php esc_html_e( 'Remove', 'custom-affiliate-link-manager' ); ?></button>
						</div>
						<?php
					}
				}
				?>
			</div>
			<button type="button" id="calm-add-ab" class="button"><?php esc_html_e( 'Add A/B Test URL', 'custom-affiliate-link-manager' ); ?></button>
		</div>
	</div>
</div>
