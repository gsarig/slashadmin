<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* = slash_dump()
 * Styles var_dump output
 * for better readability
----------------------------------------------- */
if ( ! function_exists( 'slash_dump' ) ) :
	function slash_dump( $var ) {
		echo '<pre>';
		var_dump( $var );
		echo '</pre>';
	}
endif;

/* = slash_admin_dump()
 * Styles var_dump output
 * for better readability
 * Visible only if you are an admin
----------------------------------------------- */
if ( ! function_exists( 'slash_admin_dump' ) ) :
	function slash_admin_dump( $var ) {
		if ( current_user_can( 'manage_options' ) ) {
			echo '<pre>';
			var_dump( $var );
			echo '</pre>';
		}
	}
endif;