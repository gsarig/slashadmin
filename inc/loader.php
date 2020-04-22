<?php

/**
 * Loading animation
 * You can call it manually with
 * <?php do_action('slash_admin_loader'); ?>
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/* = Enqueeue scripts and styles
----------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'slashadmin_loader_scripts' );

function slashadmin_loader_scripts() {
	if ( slash_admin( 'loading_enabled' ) !== false && slash_admin( 'loading_enabled' ) !== 'disabled' ) {
		wp_enqueue_style( 'slashadmin-loader-styles', plugins_url( 'css/loader.css', dirname( __FILE__ ) ), '', SLASH_ADMIN_VERSION );

		// Custom styles
		$background    = slash_admin( 'loading_bg_color' );
		$empty_spinner = slash_admin( 'loading_spinner_empty' );
		$full_spinner  = slash_admin( 'loading_spinner_full' );
		$custom_css    = "
		#slash_admin_loader-container {
			background: {$background};
		}
		.slash_admin_loader_delay-msg {
			color: {$full_spinner};
		}
		.slash_admin_loader {
			border-top-color: {$empty_spinner};
			border-right-color: {$empty_spinner};
			border-bottom-color: {$empty_spinner};
			border-left-color: {$full_spinner};
		}";
		if ( $background !== '#FFFFFF' || $empty_spinner !== '#CCCCCC' || $full_spinner !== '#000000' ) {
			wp_add_inline_style( 'slashadmin-loader-styles', $custom_css );
		}
	}
}

/* = The HTML output
----------------------------------------------- */
function slash_admin_hook_loader() {

	if ( slash_admin( 'loading_enabled' ) !== false && slash_admin( 'loading_enabled' ) !== 'disabled' ) {
		$site_icon    = has_site_icon() ? get_site_icon_url() : '';
		$slash_img    = slash_admin( 'loading_img' );
		$loader_image = ! empty( $slash_img ) ? $slash_img : $site_icon;
		$alt_text     = slash_admin( 'loading_text' );
		$rounded      = ! empty( $loader_image ) && slash_admin( 'loading_enabled' ) !== 'empty' ? ' slash_admin_image-rounded' : '';
		$delay_msg    = '<div id="slash_admin_keeps_loading" class="slash_admin_loader_delay-msg">' . slash_admin( 'loading_hide' ) . '</div>';
		$logo_img     = ! empty( $loader_image ) && slash_admin( 'loading_enabled' ) !== 'empty' ?
			'<div class="slash_admin_logo-loader' . $rounded . '">
				<img alt="' . $alt_text . '" src="' . $loader_image . '" width="96" height="96">
			</div>' : '';

		$spinner = slash_admin( 'loading_enabled' ) !== 'gif' ? '<div class="slash_admin_loader">' . $alt_text . '</div>' : '';
		$script  = '<script type="text/javascript">
						jQuery(document).ready(function ($) {
						    var loaderContainer = $("#slash_admin_loader-container");
						    $(window).on("load", function () {
						        loaderContainer.fadeOut();
						    });
						    loaderContainer.on("click", function () {
						        loaderContainer.fadeOut();
						    });
						    $("#slash_admin_keeps_loading").delay(3000).fadeIn();
						});
					</script>';

		$output = '<div id="slash_admin_loader-container">' . $logo_img . $delay_msg . $spinner . '</div>' . $script;

		echo $output;
	}
}

/* = Action for manual loading
----------------------------------------------- */
if ( slash_admin( 'loading_manual' ) !== false && slash_admin( 'loading_enabled' ) !== false && slash_admin( 'loading_enabled' ) !== 'disabled' ) {
	add_action( 'slash_admin_loader', 'slash_admin_hook_loader' );
}

/* = Conditional hook
----------------------------------------------- */
function slash_admin_loader_footer_hook() {
	if ( slash_admin( 'loading_location' ) !== false ) {
		if ( is_home() || is_front_page() ) {
			add_action( 'wp_footer', 'slash_admin_hook_loader' );
		}
	} else {
		add_action( 'wp_footer', 'slash_admin_hook_loader' );
	}
}

/* = Get the hook
----------------------------------------------- */
if ( slash_admin( 'loading_manual' ) === false && slash_admin( 'loading_enabled' ) !== false && slash_admin( 'loading_enabled' ) !== 'disabled' ) {
	add_action( 'wp_head', 'slash_admin_loader_footer_hook' );
}