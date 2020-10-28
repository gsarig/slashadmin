<?php

namespace SlashAdmin;

class InternetExplorer {

	/**
	 * Add Old IE Warning
	 */
	public function __construct() {
		if ( self::option() ) {
			add_action( 'wp_footer', array( $this, 'showWarning' ) );
		}
	}

	private static function option() {
		$option = Settings::option( 'oldie_warning' );

		return 'disabled' !== $option ? $option : false;
	}

	public static function showWarning() {
		$data  = self::data();
		$class = htmlspecialchars( $data['class'] );
		$src   = htmlspecialchars( $data['img_src'] );
		$title = htmlspecialchars( $data['icon_title'] );
		$text  = htmlspecialchars( $data['warning'] );
		$close = htmlspecialchars( $data['close_title'] );
		echo '<script>
				(function() {
					"use strict";
					window.addEventListener("load", function() {
						const isIE = /*@cc_on!@*/!!document.documentMode;
						if(!isIE) {
							return;
						}
						const sheet = document.createElement("style");
						const decodeHTML = function (html) {
							let txt = document.createElement("textarea");
							txt.innerHTML = html;
							return txt.value;
						};
						const text = decodeHTML("' . $text . '");
						const className = "' . $class . '";
						sheet.innerHTML = "#oldie{background:#fdf2ab;width:100%;padding:1em 2em;position:fixed;min-height:40px;z-index:9999;display:block;top:0;left:0}#oldie p{display:block;width:90%;margin:0 auto}#oldie img{float:left;padding-right:.5em}#oldie a{color:#e25600}#oldie a:hover{color:#ff8941}span.oldie-close{display:block;position:absolute;right:2em;top:1em;font-weight:700;cursor:pointer}span.oldie-close:hover{color:#e25600}#oldie.aggressive{height:100%;display:flex;align-items:center;justify-content:center}#oldie.hidden{display:none}";
						document.head.appendChild(sheet);		
						const warning = document.createElement("div");
						const closeBtn = "aggressive" !== className ? "<span id=\'slashAdminOldIeClose\' class=\'oldie-close\' title=\'' . $close . '\'>x</span>" : "";
						warning.setAttribute("id","oldie");
						warning.setAttribute("class", className );
						warning.innerHTML = "<p><img src=\'' . $src . '\' alt=\'' . $title . '\' title=\'' . $title . '\' />" + text + "</p>" + closeBtn;
						document.getElementsByTagName("body")[0].appendChild(warning);
						const close = document.getElementById("slashAdminOldIeClose");
						close.onclick = function() {
							document.getElementById("oldie").classList.add("hidden");
						}
					});
				})();
		</script>';
	}

	public static function isIE() {
		return (
			preg_match( '~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'] )
			|| preg_match( '~Trident/7.0(.*)?; rv:11.0~', $_SERVER['HTTP_USER_AGENT'] )
		);
	}


	/**
	 * texts can be overwritten hooking into the slash_ie action
	 * @return array
	 */
	public static function texts() {
		return [
			'icon_title'  => __( 'Warning: Your browser is outdated', 'slash-admin' ),
			'warning'     => __( 'Your browser is <strong>out of date</strong>. It has known <strong>security flaws</strong> and may not display all features of this and other websites. Please update to a modern browser like <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank">Microsoft Edge</a>, <a href="http://www.mozilla.com/firefox/" target="_blank">Mozilla Firefox</a>, <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> or <a href="http://www.opera.com/browser/" target="_blank">Opera</a>.',
				'slash-admin' ),
			'close_title' => __( 'Close this warning', 'slash-admin' ),

		];
	}

	public static function data() {
		$class = self::option();
		$data  = apply_filters( 'slash_ie', self::texts() );

		return array_merge(
			[
				'class'   => $class,
				'img_src' => plugins_url( 'img/warning.jpg', dirname( __FILE__ ) ),
			],
			$data
		);
	}
}