<?php
// Hide ACF for non-techies if a Techie user exists.
use SlashAdmin\Settings;

function slash_hide_acf() {
	$output = 'true';
	if ( ! Settings::isTechie() ) {
		$output = 'false';
	}
	add_filter( 'acf/settings/show_admin', '__return_' . $output );
}

add_action( 'init', 'slash_hide_acf' );