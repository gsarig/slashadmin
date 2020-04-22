<?php
/**
 * JetPack settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enable Jetpack Development Mode
if ( class_exists( 'Jetpack' ) && slash_admin( 'jetpack_development_mode' ) ) {
	add_filter( 'jetpack_development_mode', '__return_true' );
}

// Remove Jetpack Sharing (to add it manually later)
if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) && slash_admin('jetpack_move_share') ) {
	function slash_jetpack_remove_share() {
		remove_filter( 'the_content', 'sharing_display', 19 );
		remove_filter( 'the_excerpt', 'sharing_display', 19 );
		if ( class_exists( 'Jetpack_Likes' ) ) {
			remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
		}
	}

	add_action( 'loop_start', 'slash_jetpack_remove_share' );
}
