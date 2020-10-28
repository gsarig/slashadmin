<?php

namespace SlashAdmin;

class Fonts {
	/**
	 * Load Fonts
	 * @var mixed|bool|string
	 */
	private $fonts;

	public function __construct() {
		$local_enabled = Settings::option( 'local_fonts_enabled' );
		$fonts         = Settings::option( 'custom_fonts' );
		$this->fonts   = $fonts;
		if ( ! $local_enabled && ! $fonts ) {
			return;
		}
		if ( ! $local_enabled ) {
			$func = 'external';
		} else {
			$func = 'local';
		}
		add_action( 'wp_enqueue_scripts', array( $this, $func ) );
	}

	public function external() {
		$src = '//fonts.googleapis.com/css?family=' . $this->fonts;
		if ( strpos( $this->fonts, 'css2' ) ) {
			$src = $this->fonts;
		}
		wp_enqueue_style( 'slash-admin-fonts', $src );
	}

	public function local() {
		$local_fonts = Settings::option( 'local_fonts' );
		if ( $local_fonts ) {
			$fonts_arr = explode( "\n", $local_fonts );
			if ( $fonts_arr ) {
				include_once dirname( __DIR__ ) . '/vendor/wptt-webfont-loader.php';
				$local_woff = Settings::option( 'local_fonts_woff' );
				$woff       = $local_woff ? 'woff' : 'woff2';
				foreach ( $fonts_arr as $index => $font ) {
					$name = 'slash-admin-fonts_' . $index;
					$src  = wptt_get_webfont_url( $font, $woff );
					wp_enqueue_style( $name, $src );
				}
			}
		}
	}
}