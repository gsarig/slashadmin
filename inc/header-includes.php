<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function slashadmin_header() {
	if ( slash_admin( 'analytics' ) !== '' ) {
		echo slash_cookie_check( slash_admin( 'analytics' ) );
	}
	if ( ! has_site_icon() && slash_admin( 'favicon' ) !== '' ) {
		echo '<link rel="shortcut icon" href="' . slash_admin( 'favicon' ) . '" />';
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
	$cookie_law = slash_admin( 'cookielaw_enable' );
	preg_match_all( '/<<cookie=(.*?)>>(.*?)<<\/cookie>>/s', $scripts, $match );
	$output = '';
	if ( isset( $match[1] ) && ! empty( $match[1] ) ) {
		foreach ( $match[1] as $num => $cookie ) {
			if ( false !== $cookie_law ) {
				if ( isset( $_COOKIE[ $cookie ] ) || $cookie === '0' ) {
					$output .= $match[2][ $num ];
				}
			} else {
				$output .= $match[2][ $num ];
			}
		}
	} else {
		$output = $scripts;
	}

	return $output;
}