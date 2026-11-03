<?php
/**
 * QR Code Meta Box Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="calm-qr-code-box" style="text-align: center;">
	<img src="<?php echo esc_url( $qr_code_url ); ?>" alt="QR Code" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
	<p><small><?php esc_html_e( 'QR Code for:', 'custom-affiliate-link-manager' ); ?><br><?php echo esc_html( $pretty_link ); ?></small></p>
	<button type="button" class="button button-secondary" onclick="window.open('<?php echo esc_url( $qr_code_url ); ?>', '_blank')"><?php esc_html_e( 'Download QR Code', 'custom-affiliate-link-manager' ); ?></button>
</div>
