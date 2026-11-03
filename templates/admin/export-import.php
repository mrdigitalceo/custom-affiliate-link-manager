<?php
/**
 * Export/Import Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Export/Import Affiliate Links', 'custom-affiliate-link-manager' ); ?></h1>
	<div class="calm-export-import">
		<div class="calm-section">
			<h2><?php esc_html_e( 'Export Links', 'custom-affiliate-link-manager' ); ?></h2>
			<form method="post">
				<p><?php esc_html_e( 'Download all affiliate links as a CSV file.', 'custom-affiliate-link-manager' ); ?></p>
				<button type="submit" name="calm_export_links" class="button button-primary"><?php esc_html_e( 'Export Links', 'custom-affiliate-link-manager' ); ?></button>
			</form>
		</div>
		<div class="calm-section">
			<h2><?php esc_html_e( 'Import Links', 'custom-affiliate-link-manager' ); ?></h2>
			<form method="post" enctype="multipart/form-data">
				<p><?php esc_html_e( 'Upload a CSV file to import affiliate links. Format: Title, Slug, Target URL, Notes', 'custom-affiliate-link-manager' ); ?></p>
				<input type="file" name="calm_import_file" accept=".csv" required>
				<button type="submit" name="calm_import_links" class="button button-primary"><?php esc_html_e( 'Import Links', 'custom-affiliate-link-manager' ); ?></button>
			</form>
		</div>
	</div>
</div>
<style>
	.calm-export-import {
		display: flex;
		gap: 20px;
		flex-wrap: wrap;
		margin-top: 20px;
	}
	.calm-export-import .calm-section {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 8px;
		padding: 20px;
		flex: 1;
		min-width: 300px;
		box-shadow: 0 2px 4px rgba(0,0,0,0.05);
	}
	.calm-export-import h2 {
		margin-top: 0;
		border-bottom: 1px solid #eee;
		padding-bottom: 10px;
	}
</style>
