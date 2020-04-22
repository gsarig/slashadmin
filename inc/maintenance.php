<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// Maintenance mode prevents non-Admins from accessing the WordPress backend. Admins can always login as usual.

if ( slash_admin( 'maintenance_mode' ) ) {

	function slash_maintenance() { // Maintenance mode
		if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) { // Run this only if the current user is logged in and is NOT an Administrator
			$logout_url = add_query_arg( [
				'maintenance' => '1'
			], wp_login_url() ); // Set the maintenance mode url
			wp_logout(); // Sign out user
			wp_redirect( $logout_url, 302 ); // Redirect user to the maintenance mode url
			exit;
		}
	}

	add_action( 'admin_head', 'slash_maintenance' ); // Run it on the admin_head, to retain the frontend unaffected.


	function slash_maintenance_msg() { // Custom login message

		$param           = isset( $_GET['maintenance'] ) ? $_GET['maintenance'] : '';
		$maintenance_msg = slash_admin( 'maintenance_mode_msg' );
		if ( isset( $param ) && $param === '1' ) {
			$message = '<p id="login_error" class="message"><b>' . $maintenance_msg . '</b></p>';

			return $message;
		}
	}

	add_filter( 'login_message', 'slash_maintenance_msg' );
}

