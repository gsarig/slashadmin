<?php
/**
 * Show Admin notices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function slash_get_ip_address() {
	$output = '';
	foreach (
		array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		) as $key
	) {
		if ( array_key_exists( $key, $_SERVER ) === true ) {
			foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
				$ip = trim( $ip ); // just to be safe

				if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
					$output = $ip;
				}
			}
		}
	}

	return $output;

}

function slash_admin_notices() {

	global $pagenow;

	if ( $pagenow == 'tools.php' && current_user_can( 'install_plugins' ) ) :

		$class            = "error notice is-dismissible";
		$icon_admin_tools = '<span class="dashicons dashicons-admin-tools"></span> ';
		$icon_hidden      = '<span class="dashicons dashicons-hidden"></span> ';

		$server_addr = slash_get_ip_address();

		if ( $server_addr ) {
			// Notice #1: If site is on air and debug mode is on
			if ( WP_DEBUG && $server_addr !== '127.0.0.1' ) {
				$message = $icon_admin_tools . __( 'Debug mode is enabled. This is a good habit if you are currently developing this site, but don\'t forget to disable it when you go on air (<a href="http://codex.wordpress.org/Function_Reference/wp_debug_mode" target="_blank">more info</a>).', 'slash-admin' );

				echo "<div class=\"$class\"> <p>$message</p></div>";
			}

			// Notice #2: If site is on localhost and debug mode is off
			if ( ! WP_DEBUG && $server_addr === '127.0.0.1' ) {
				$message = $icon_admin_tools . __( 'Debug mode is disabled. It seems that this site is under development so, you might consider turning it on. (<a href="http://codex.wordpress.org/Function_Reference/wp_debug_mode" target="_blank">more info</a>).', 'slash-admin' );
				echo "<div class=\"$class\"> <p>$message</p></div>";
			}

			// Notice #3: If site is on air and hidden from Search Engines
			if ( ! get_option( 'blog_public' ) && $server_addr !== '127.0.0.1' ) {
				$message = $icon_hidden . __( 'Your Website is on air and you have chosen to hide it from Search Engines. This is a huge SEO issue. Unless you know what you are doing, you should go to <strong>Settings &rarr; Reading &rarr; Search engine visibility</strong> and <strong>uncheck</strong> <i>"Discourage search engines from indexing this site"</i>.', 'slash-admin' );
				echo "<div class=\"$class\"> <p>$message</p></div>";
			}
		}


		// Notice #4: If Jetpack Development mode is enabled
		if ( class_exists( 'Jetpack' ) && slash_admin( 'jetpack_development_mode' ) ) {
			$message = $icon_admin_tools . __( 'Jetpack is on development mode. Don\'t forget to properly connect to it when you go live.', 'slash-admin' );
			echo "<div class=\"$class\"> <p>$message</p></div>";
		}
	endif;

}

add_action( 'admin_notices', 'slash_admin_notices' );