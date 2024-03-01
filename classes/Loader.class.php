<?php

namespace SlashAdmin;

class Loader {
	public function __construct() {
		if ( ! Settings::option( 'loader_enabled' ) ) {
			return;
		}

		add_action( 'wp_head', array( $this, 'styles' ), 100 );
		if (
			Settings::option( 'loading_location' ) &&
			( ! Settings::option( 'loading_manual' ) && ! Settings::option( 'loading_position' ) ) &&
			'slash_admin_loader' !== ! Settings::option( 'loading_position' )
		) {
			if ( is_front_page() ) {
				add_action( self::hookName(), array( $this, 'loader' ) );
			}
		} else {
			add_action( self::hookName(), array( $this, 'loader' ) );
		}
	}

	public static function loader() {
		$alt_text = Settings::option( 'loading_text' );
		$output   = '<div id="slash_admin_loader-container" class="loading">' .
		            self::image( $alt_text ) .
		            self::message() .
		            self::spinner( $alt_text ) .
		            '</div>' .
		            self::script();
		echo $output;
	}

	public static function script() {
		return '<script>
					(function() {
						"use strict";
						const loaderContainer = document.getElementById("slash_admin_loader-container");
						window.addEventListener("load", function() {
							loaderContainer.classList.remove("loading");	
							setTimeout(function () {
								loaderContainer.parentNode.removeChild(loaderContainer);
							},600);
						});
						loaderContainer.onclick = function () {
							loaderContainer.classList.remove("loading");
						}						
					})();
				</script>';
	}

	public static function hookName() {
		if ( Settings::option( 'loading_manual' ) && ! Settings::option( 'loading_position' ) ) {
			$hook = 'slash_admin_loader';
		} elseif (
			! Settings::option( 'loading_position' ) ||
			version_compare( get_bloginfo( 'version' ), '5.2' ) < 0
		) {
			$hook = 'wp_footer';
		} else {
			$hook = Settings::option( 'loading_position' );
		}

		return $hook;
	}

	public static function styles() {
		$styles = '#slash_admin_loader-container{position:fixed;z-index:99999;width:100%;height:100%;background:#fff;top:0;overflow:hidden;opacity:0;pointer-events:none;transition:opacity .6s}#slash_admin_loader-container.loading{opacity:1;pointer-events:all}#slash_admin_loader-container .slash_admin_logo-loader{opacity:1;top:50%;left:calc(50% - 48px);transform:translateY(-50%);position:absolute;width:96px;height:96px}#slash_admin_loader-container .slash_admin_logo-loader img{height:auto}.slash_admin_image-rounded img{border-radius:50%}.slash_admin_loader_delay-msg{position:absolute;z-index:99999999;width:100%;text-align:center;bottom:10%}#slash_admin_loader-container .slash_admin_loader{margin:-5em auto 0 auto;font-size:10px;text-indent:-9999em;border-top:.2em solid #ccc;border-right:.2em solid #ccc;border-bottom:.2em solid #ccc;border-left:.2em solid #000;position:absolute;top:50%;left:calc(50% - 5em);transform:translate(-50%, 0);animation:load8 1.1s infinite linear}#slash_admin_loader-container .slash_admin_loader,#slash_admin_loader-container .slash_admin_loader:after{border-radius:50%;width:10em;height:10em}@-webkit-keyframes load8{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}@keyframes load8{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}';
		if ( self::hasCustomColors() ) {
			// Custom styles
			$background    = safecss_filter_attr( Settings::option( 'loading_bg_color' ) );
			$empty_spinner = safecss_filter_attr( Settings::option( 'loading_spinner_empty' ) );
			$full_spinner  = safecss_filter_attr( Settings::option( 'loading_spinner_full' ) );
			$styles        .= "#slash_admin_loader-container {background: {$background};}.slash_admin_loader_delay-msg {color: {$full_spinner};}.slash_admin_loader {border-top-color: {$empty_spinner};border-right-color: {$empty_spinner};border-bottom-color: {$empty_spinner};border-left-color: {$full_spinner};}";
		}

		echo $styles ? '<style>' . $styles . '</style>' : '';
	}

	private static function image( $alt_text ) {
		$site_icon    = has_site_icon() ? get_site_icon_url() : '';
		$slash_img    = Settings::option( 'loading_img' );
		$loader_image = ! empty( $slash_img ) ? $slash_img : $site_icon;

		if ( empty( $loader_image ) || Settings::option( 'loading_enabled' ) === 'empty' ) {
			return '';
		}
		$rounded = Settings::option( 'loading_enabled' ) !== 'empty' ? ' slash_admin_image-rounded' : '';

		return '<div class="slash_admin_logo-loader' . $rounded . '">
					<img alt="' . $alt_text . '" src="' . $loader_image . '" width="96" height="96">
				</div>';
	}

	private static function spinner( $alt_text ) {
		if ( Settings::option( 'loading_enabled' ) === 'gif' ) {
			return '';
		}

		return '<div class="slash_admin_loader">' . $alt_text . '</div>';
	}

	private static function message() {
		return '<div id="slash_admin_keeps_loading" class="slash_admin_loader_delay-msg">
							' . Settings::option( 'loading_hide' ) . '
						</div>';
	}

	private static function hasCustomColors() {
		$has_color = false;
		$options   = [ 'background', 'empty_spinner', 'full_spinner' ];
		foreach ( $options as $option ) {
			if ( Settings::option( $option ) !== self::defaults( $option ) ) {
				$has_color = true;
			}
		}

		return $has_color;
	}

	private static function defaults( $option ) {
		$options = [
			'background'    => '#FFFFFF',
			'empty_spinner' => '#CCCCCC',
			'full_spinner'  => '#000000',
		];

		return isset( $options[ $option ] ) ? $options[ $option ] : '';
	}
}