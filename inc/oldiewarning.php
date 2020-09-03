<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( slash_admin( 'oldie_warning' ) == 1 ) {

	function old_ie_warning() {

		preg_match( '/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches );

		if ( count( $matches ) > 1 ) {
			//Then we're using IE
			$version = $matches[1];


			wp_enqueue_script( 'oldie-warning',
				plugins_url( 'js/oldiewarning.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				false );
			$params = array( // Pass Theme Options to the script
				'text' => '<div id="oldie">
									<p>
									<img src="' . plugins_url( 'img/warning.jpg',
						dirname( __FILE__ ) ) . '" alt="' . __( 'Warning: Your browser is outdated',
						'slash-admin' ) . '" title="' . __( 'Warning: Your browser is outdated',
						'slash-admin' ) . '" />' .
				          __( 'Your browser is <strong>out of date</strong>. It has known <strong>security flaws</strong> and may not display all features of this and other websites. Please update to a modern browser like <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank">Microsoft Edge</a>, <a href="http://www.mozilla.com/firefox/" target="_blank">Mozilla Firefox</a>, <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> or <a href="http://www.opera.com/browser/" target="_blank">Opera</a>.',
					          'slash-admin' ) .
				          '</p>
									<span class="oldie-close" title="' . __( 'Close this warning', 'slash-admin' ) . '">x</span>
								   </div>',
			);
			wp_localize_script( 'oldie-warning', 'oldIE', $params );

			wp_enqueue_style( 'oldie-warning-style',
				plugins_url( 'css/oldiewarning.css', dirname( __FILE__ ) ) ); // Warning Styles
		}

	}

	add_action( 'wp_enqueue_scripts', 'old_ie_warning' );

}
