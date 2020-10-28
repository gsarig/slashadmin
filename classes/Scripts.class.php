<?php

namespace SlashAdmin;

class Scripts {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'header' ) );
		add_action( 'wp_body_open', array( $this, 'body' ) );
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	public function header() {
		$scripts = Settings::option( 'analytics' );
		if ( $scripts !== '' ) {
			echo $this->hasCookie( $scripts );
		}
		if ( ! has_site_icon() && Settings::option( 'favicon' ) !== '' ) {
			echo '<link rel="shortcut icon" href="' . Settings::option( 'favicon' ) . '" />';
		}
	}

	public function body() {
		$scripts = Settings::option( 'scripts_body' );
		if ( $scripts !== '' ) {
			echo $this->hasCookie( $scripts );
		}
	}

	public function footer() {
		$scripts = Settings::option( 'scripts_footer' );
		if ( $scripts !== '' ) {
			echo $this->hasCookie( $scripts );
		}
	}


	/**
	 * Load scripts only if a specific cookie is set. Example:
	 * <<cookie=eucookie>>YOUR SCRIPT HERE<</cookie>>
	 *
	 * @param $scripts
	 *
	 * @return string
	 */
	public function hasCookie( $scripts ) {
		$cookie_law = Settings::option( 'cookielaw_enable' );
		preg_match_all( '/<<cookie=(.*?)>>(.*?)<<\/cookie>>/s', $scripts, $match );
		$output = '';
		if ( isset( $match[1] ) && ! empty( $match[1] ) ) {
			foreach ( $match[1] as $num => $cookie ) {
				if ( false !== $cookie_law ) {
					if ( isset( $_COOKIE[ $cookie ] ) || $cookie === '0' ) {
						$output .= $match[2][ $num ];
					}
				} else {
					$output .= $match[2][ $num ];
				}
			}
		} else {
			$output = $scripts;
		}

		return $output;
	}
}