<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// Limit the number of revisions
if ( slash_admin( 'limit_revisions' ) !== 'default' ) {

	function slashadmin_limit_revisions( $num, $post ) {
		if ( slash_admin( 'limit_revisions' ) == 'one' ) {
			$num = 1;
		} elseif ( slash_admin( 'limit_revisions' ) == 'two' ) {
			$num = 2;
		} elseif ( slash_admin( 'limit_revisions' ) == 'three' ) {
			$num = 3;
		} elseif ( slash_admin( 'limit_revisions' ) == 'four' ) {
			$num = 4;
		} elseif ( slash_admin( 'limit_revisions' ) == 'five' ) {
			$num = 5;
		} elseif ( slash_admin( 'limit_revisions' ) == 'ten' ) {
			$num = 10;
		} elseif ( slash_admin( 'limit_revisions' ) == 'twenty' ) {
			$num = 20;
		} elseif ( slash_admin( 'limit_revisions' ) == 'fifty' ) {
			$num = 50;
		}

		return $num;
	}

	add_filter( 'wp_revisions_to_keep', 'slashadmin_limit_revisions', 10, 2 );
}
