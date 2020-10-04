<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enqueue your own fonts
add_action( 'wp_enqueue_scripts', 'slash_enqueue_fonts' );
function slash_enqueue_fonts() {
	$local_enabled = slash_admin( 'local_fonts_enabled' );
	$fonts         = slash_admin( 'custom_fonts' );
	if ( ! $local_enabled ) {
		if ( $fonts ) {
			if ( strpos( $fonts, 'css2' ) ) {
				$src = $fonts;
			} else {
				$src = '//fonts.googleapis.com/css?family=' . $fonts;
			}
			wp_enqueue_style( 'slash-admin-fonts', $src );
		}
	} else { // Local fonts
		$local_fonts = slash_admin( 'local_fonts' );
		if ( $local_fonts ) {
			$fonts_arr = explode( "\n", $local_fonts );
			if ( $fonts_arr ) {
				include_once dirname( __DIR__ ) . '/vendor/wptt-webfont-loader.php';
				$local_woff = slash_admin( 'local_fonts_woff' );
				$woff       = $local_woff ? 'woff' : 'woff2';
				foreach ( $fonts_arr as $index => $font ) {
					wp_enqueue_style( 'slash-admin-fonts_' . $index, wptt_get_webfont_url( $font, $woff ) );
				}
			}
		}
	}
}