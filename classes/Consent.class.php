<?php

namespace SlashAdmin;

class Consent {
	public function __construct() {
		$cookie_law = Settings::option( 'cookielaw_enable' );
		if ( isset( $_COOKIE['eucookie'] ) || ! isset( $cookie_law ) || ! $cookie_law ) {
			return;
		}
		add_action( 'wp_head', array( $this, 'styles' ), 100 );
		add_action( 'wp_footer', array( $this, 'cookiebox' ) );

		$page_id = self::consentPage();
		if ( Settings::option( 'cookielaw_pagedata' ) && ! empty( $page_id ) && 'none' !== $page_id ) {
			add_post_meta( $page_id, 'slash_accept', 'Accept', true );
		}
	}

	public static function cookiebox() {
		$style  = Settings::option( 'cookielaw_style' );
		$pos    = Settings::option( 'cookielaw_position' );
		$class  = ( 'dark' !== $style ) ? $style : 'dark';
		$pos_cl = ( 'bottom' !== $pos ) ? $pos : 'bottom';

		$consent_page = self::consentPage();
		$get_excerpt  = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $consent_page ) );
		$excerpt      = wp_strip_all_tags( $get_excerpt, true );
		$accept_meta  = get_post_meta( $consent_page, 'slash_accept', true );
		$accept       = ( isset( $accept_meta ) && ! empty( $accept_meta ) ) ? $accept_meta : Settings::option( 'cookielaw_accept' );
		if ( Settings::option( 'cookielaw_pagedata' ) && ! empty( $consent_page ) && 'none' !== $consent_page ) {
			$text = ( ! empty( $excerpt ) ) ? $excerpt : get_the_title( $consent_page );
			$more = ( ! empty( $excerpt ) ) ? get_the_title( $consent_page ) : Settings::option( 'cookielaw_readmore' );
			$link = ' <a id="slashadmin_cookiemore" href="' . get_the_permalink( $consent_page ) . '">' . $more . '</a>';
		} elseif ( ! empty( $consent_page ) && 'none' !== $consent_page ) {
			$text = Settings::option( 'cookielaw_message' );
			$more = Settings::option( 'cookielaw_readmore' );
			$link = ' <a id="slashadmin_cookiemore" href="' . get_the_permalink( $consent_page ) . '">' . $more . '</a>';
		} else {
			$text = Settings::option( 'cookielaw_message' );
			$link = '';
		}
		echo '<div id="slashadmin_eucookielaw" class="cl_' . $pos_cl . ' cl_' . $class . ' open">
					<p>' . $text . $link . '<span id="slashadmin_removecookie">' . $accept . '</span></p>
				</div>' .
		     self::script();
	}

	public static function script() {
		return '<script>
					"use strict";
					(function () {
					    function SetCookie( c_name, value, expiredays ) {
					        let exdate = new Date();
					        exdate.setDate( exdate.getDate() + expiredays );
					        document.cookie = c_name + "=" + encodeURI(value) + ";path=/" + ((expiredays === null) ? "" : ";sameSite=Strict;expires="+exdate.toUTCString());
					    }
					    window.addEventListener("load", function() {
							const cookieBox = document.getElementById("slashadmin_eucookielaw");
							const removeCookie = document.getElementById("slashadmin_removecookie");
					        if( document.cookie.indexOf("eucookie") === -1 ) {
					            cookieBox.classList.add("cl_visible");
					        }
					        removeCookie.onclick = function() {
					            SetCookie( "eucookie", "eucookie", 365*100 );
					            cookieBox.classList.remove("open");
					            setTimeout(function () {
									cookieBox.parentNode.removeChild(cookieBox);
								},600);
					        }
						});
					})();
				</script>';
	}

	public static function styles() {
		$styles = '#slashadmin_eucookielaw{display:block;position:fixed;width:100%;text-align:center;padding:1.5em 1em 0 1em;font-size:90%;z-index:999999;opacity:0;transition:all .6s ease-in-out}#slashadmin_eucookielaw.open{opacity:1}#slashadmin_eucookielaw p{margin-bottom:1.5em;line-height:2em}#slashadmin_removecookie{padding:.5em 1.5em;margin-left:1em;border-radius:5px;cursor:pointer;font-weight:700}#slashadmin_eucookielaw.cl_bottom{bottom:0;margin-bottom:-999px}#slashadmin_eucookielaw.cl_bottom.cl_visible{margin-bottom:0}#slashadmin_eucookielaw.cl_bottom.cl_light{border-top:1px solid #ccc}#slashadmin_eucookielaw.cl_top{top:0;margin-top:-999px}#slashadmin_eucookielaw.cl_top.cl_visible{margin-top:0}#slashadmin_eucookielaw.cl_top.cl_light{border-bottom:1px solid #ccc}#slashadmin_eucookielaw.cl_right{bottom:0;margin-bottom:-999px;right:1em;max-width:320px;width:80%;border-radius:5px 5px 0 0}#slashadmin_eucookielaw.cl_right.cl_visible{margin-bottom:0}#slashadmin_eucookielaw.cl_right #slashadmin_removecookie{display:block;margin-top:1em}#slashadmin_eucookielaw.cl_right.cl_light{border:1px solid #ccc}#slashadmin_eucookielaw.cl_dark{background:rgba(0,0,0,.85);color:#fff;box-shadow:1px 1px 6px #000}#slashadmin_eucookielaw.cl_dark #slashadmin_cookiemore{color:#fff}#slashadmin_eucookielaw.cl_dark #slashadmin_removecookie{background:#ffcb00;color:#000}#slashadmin_eucookielaw.cl_light{background:rgba(255,255,255,.92);color:#000;box-shadow:1px 1px 6px #ccc}#slashadmin_eucookielaw.cl_light #slashadmin_cookiemore{color:#000}#slashadmin_eucookielaw.cl_light #slashadmin_removecookie{background:#444;color:#fff}';
		echo '<style>' . $styles . '</style>';
	}

	public static function consentPage() {
		$page_id = Settings::option( 'cookielaw_url' );

		return ( function_exists( 'icl_object_id' ) ) ? icl_object_id( $page_id, 'page', true ) : $page_id;

	}
}