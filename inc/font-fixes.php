<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Remove Open Sans that WP adds from frontend
if ( slash_admin( 'default_opensans_remove' ) !== 'default' ) {
	if ( ! function_exists( 'remove_wp_open_sans' ) ) :

		function remove_wp_open_sans() {
			wp_deregister_style( 'open-sans' );
			wp_register_style( 'open-sans', false );
		}

		add_action( 'wp_enqueue_scripts', 'remove_wp_open_sans' );

		if ( slash_admin( 'default_opensans_remove' ) == 'remove_backend' ) {
			add_action( 'admin_enqueue_scripts', 'remove_wp_open_sans' );
		}
	endif;
}

// Enqueue your own fonts
if ( slash_admin( 'custom_fonts' ) !== '' ) {
	add_action( 'wp_enqueue_scripts', 'slash_enqueue_fonts' );
	function slash_enqueue_fonts() {
		wp_enqueue_style( 'slash-admin-fonts', '//fonts.googleapis.com/css?family=' . slash_admin( 'custom_fonts' ) );
	}
}

