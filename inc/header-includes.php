<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function slashadmin_header() {
	if ( slash_admin( 'analytics' ) !== '' ) {
		echo slash_cookie_check( slash_admin( 'analytics' ) );
	}
}

add_action( 'wp_head', 'slashadmin_header' );

/**
 * Load scripts only if a specific cookie is set. Example:
 * <<cookie=eucookie>>YOUR SCRIPT HERE<</cookie>>
 *
 * @param $scripts
 *
 * @return string
 */
function slash_cookie_check( $scripts ) {
	preg_match_all( '/<<cookie=(.*?)>>(.*?)<<\/cookie>>/s', $scripts, $match );
	$output = '';
	if ( isset( $match[1] ) ) {
		foreach ( $match[1] as $num => $cookie ) {

			if ( isset( $_COOKIE[ $cookie ] ) || $cookie === '0' ) {
				$output .= $match[2][ $num ];
			}
		}
	} else {
		$output = $scripts;
	}

	return $output;
}