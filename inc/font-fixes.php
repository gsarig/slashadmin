<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enqueue your own fonts
if ( slash_admin( 'custom_fonts' ) !== '' ) {
	add_action( 'wp_enqueue_scripts', 'slash_enqueue_fonts' );
	function slash_enqueue_fonts() {
		$fonts = slash_admin( 'custom_fonts' );
		if ( strpos( $fonts, 'css2' ) ) {
			$src = $fonts;
		} else {
			$src = '//fonts.googleapis.com/css?family=' . $fonts;
		}
		wp_enqueue_style( 'slash-admin-fonts', $src );
	}
}

