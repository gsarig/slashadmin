<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function slash_custom_splash() {
	$splash = slash_admin( 'splash_path' );
	if ( slash_admin( 'splash_enable' ) && ! empty( $splash ) ) {
		if ( ! function_exists( 'slash_is_login_page' ) ) {
			function slash_is_login_page() {
				return in_array(
					$GLOBALS['pagenow'],
					array( 'wp-login.php', 'wp-register.php' ),
					true
				);
			}
		}
		if ( ! is_user_logged_in() && ! slash_is_login_page() ) {
			wp_safe_redirect( get_home_url() . '/' . $splash . '/' );
			exit;
		}
	}
}

add_action( 'init', 'slash_custom_splash' );