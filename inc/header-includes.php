<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function slashadmin_header() {
	if ( slash_admin( 'analytics' ) !== '' ) {
		echo slash_admin( 'analytics' );
	} else {
		echo '';
	}
	if ( slash_admin( 'favicon' ) !== '' ) {
		echo '<link rel="shortcut icon" href="' . slash_admin( 'favicon' ) . '" />';
	} else {
		echo '';
	}
}

add_action( 'wp_head', 'slashadmin_header' );