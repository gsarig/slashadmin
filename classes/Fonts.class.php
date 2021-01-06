<?php

namespace SlashAdmin;

class Fonts {
	/**
	 * Load Fonts
	 * @var mixed|bool|string
	 */
	private $local_enabled;
	private $fonts;

	public function __construct() {
		$local_enabled       = Settings::option( 'local_fonts_enabled' );
		$fonts               = Settings::option( 'custom_fonts' );
		$this->local_enabled = $local_enabled;
		$this->fonts         = $fonts;
		if ( ! $local_enabled && ! $fonts ) {
			return;
		}
		if ( ! $local_enabled ) {
			$func = 'external';
		} else {
			$func = 'local';
		}
		add_action( 'wp_enqueue_scripts', array( $this, $func ) );
		add_filter( 'style_loader_tag', array( $this, 'preload' ), 10, 2 );
	}

	public function external() {
		$src = '//fonts.googleapis.com/css?family=' . $this->fonts;
		if ( strpos( $this->fonts, 'css2' ) ) {
			$src = $this->fonts;
		}
		wp_enqueue_style( 'slash-admin-fonts', $src );
	}

	public function local() {
		$fonts_arr = $this->urls();
		if ( $fonts_arr ) {
			include_once dirname( __DIR__ ) . '/vendor/wptt-webfont-loader.php';
			foreach ( $fonts_arr as $index => $font ) {
				$src = wptt_get_webfont_url( $font, $this->woff() );
				wp_enqueue_style( $this->handle( $index ), $src );
			}
		}
	}


	public function preload( $html, $handle ) {
		$fonts_arr = $this->urls();
		if ( ! $fonts_arr || ! $this->local_enabled ) {
			return $html;
		}
		$woff = $this->woff();
		foreach ( $fonts_arr as $index => $font ) {
			$preload = strpos( $font, '&preload=true' );
			if ( $preload && $handle === $this->handle( $index ) ) {
				$html = str_replace(
					"rel='stylesheet'",
					"rel='preload' as='font' type='font/$woff' crossorigin='anonymous'",
					$html
				);
			}
		}

		return $html;
	}

	private function woff(): string {
		$local_woff = Settings::option( 'local_fonts_woff' );

		return $local_woff ? 'woff' : 'woff2';
	}

	private function urls() {
		$local_fonts = Settings::option( 'local_fonts' );
		if ( ! $local_fonts ) {
			return [];
		}

		return explode( "\n", $local_fonts );
	}

	private function handle( $index ): string {
		return 'slash-admin-fonts_' . $index;
	}
}