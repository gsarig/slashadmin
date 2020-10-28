<?php

namespace SlashAdmin;

class ACF {
	public function __construct() {
		add_action( 'init', array( $this, 'hide' ) );
	}

	/**
	 *  Hide ACF for non-techies if a Techie user exists.
	 */
	public function hide() {
		$output = 'true';
		if ( ! Settings::isTechie() ) {
			$output = 'false';
		}
		add_filter( 'acf/settings/show_admin', '__return_' . $output );
	}
}