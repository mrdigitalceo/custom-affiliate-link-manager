<?php
/**
 * Redirect Message Template
 *
 * @package CALM
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="2;url=<?php echo esc_url( $target_url ); ?>">
	<title><?php esc_html_e( 'Redirecting...', 'custom-affiliate-link-manager' ); ?></title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			margin: 0;
			background-color: #f0f2f5;
			color: #1c1e21;
		}
		.redirect-container {
			background: white;
			padding: 40px;
			border-radius: 12px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.1);
			text-align: center;
			max-width: 400px;
			width: 90%;
		}
		.loader {
			border: 4px solid #f3f3f3;
			border-top: 4px solid #0073aa;
			border-radius: 50%;
			width: 40px;
			height: 40px;
			animation: spin 1s linear infinite;
			margin: 0 auto 20px;
		}
		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
		h1 { font-size: 20px; margin-bottom: 10px; }
		p { color: #606770; font-size: 16px; }
	</style>
</head>
<body>
	<div class="redirect-container">
		<div class="loader"></div>
		<h1><?php esc_html_e( 'Redirecting...', 'custom-affiliate-link-manager' ); ?></h1>
		<p><?php echo esc_html( $redirect_message ); ?></p>
		<p><small><?php printf( esc_html__( 'If you are not redirected within 2 seconds, %sclick here%s.', 'custom-affiliate-link-manager' ), '<a href="' . esc_url( $target_url ) . '">', '</a>' ); ?></small></p>
	</div>
</body>
</html>
