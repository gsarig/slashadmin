<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/* = Allow html in text fields
----------------------------------------------- */
function slashadmin_wp_kses( $input ) {
	$allowed_tags = wp_kses_allowed_html( 'post' );
	$allowed_tags['iframe'] = array(
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	);
	$text         = wp_kses( slash_admin( $input ), $allowed_tags );

	return $text;
}

/* = Change howdy message
----------------------------------------------- */
add_filter( 'gettext', 'slashadmin_white_label_texts', 20, 3 );

function slashadmin_white_label_texts( $translated, $text, $domain ) {
	$howdy   = 'Howdy';
	$message = sanitize_text_field( slash_admin( 'howdy' ) );

	if ( ! is_admin() || 'default' != $domain ) {
		return $translated;
	}

	if ( false !== strpos( $translated, $howdy ) && ! empty( $message ) ) {
		return str_replace( $howdy, $message, $translated );
	}

	return $translated;
}

/* = Change the footer text
----------------------------------------------- */
add_action( 'admin_init', 'slash_admin_change_footer_text' );

function slash_admin_change_footer_text() {
	if ( slash_admin( 'footer_txt' ) ) {
		add_filter( 'admin_footer_text', 'slashadmin_white_label_footer' );
	}
}

function slashadmin_white_label_footer() {
	$text = slashadmin_wp_kses( 'footer_txt' );
	echo $text;
}

/* = Change header logo
----------------------------------------------- */
add_action( 'admin_enqueue_scripts', 'slashadmin_custom_admin_logo' );
add_action( 'wp_enqueue_scripts', 'slashadmin_custom_admin_logo' );

function slashadmin_custom_admin_logo() {
	$image  = slash_admin( 'admin_logo' );
	$output = '<style>
			#wp-admin-bar-wp-logo {
				display: none;
			}
			#wp-admin-bar-root-default:before {
				content: "";
				display: block;
				float: left;
				background-image: url(' . $image . ');
				width: 32px;
				height: 32px;
				background-size: auto 32px;
			}

			@media all and (max-width: 1000px) and (min-width: 782px) {
				#wp-admin-bar-root-default:before {
					width: 46px;
					height: 46px;
					background-size: auto 46px;
				}
			}
		</style>';
	echo ( ! empty( $image ) && is_admin_bar_showing() ) ? $output : '';
}

/* = Replace Welcome Panel with your own
----------------------------------------------- */
add_action( 'admin_init', 'slashadmin_remove_default_welcome_panel' );
add_action( 'welcome_panel', 'slashadmin_welcome_panel' );

function slashadmin_remove_default_welcome_panel() {
	if ( slash_admin( 'dashboard_welcome' ) ) {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}
}

function slashadmin_welcome_panel() {
	$output = slashadmin_wp_kses( 'dashboard_welcome' );
	echo ! empty( $output ) ? $output : '';
}

/* = Add a widget in WordPress Dashboard
----------------------------------------------- */
add_action( 'wp_dashboard_setup', 'slashadmin_add_dashboard_widgets' );

function slashadmin_dashboard_widget_content() {
	$content = slashadmin_wp_kses( 'dashboard_widget_content' );
	echo $content;
}

function slashadmin_add_dashboard_widgets() {
	$site_title = get_bloginfo( 'name' );
	$get_title  = slash_admin( 'dashboard_widget_title' );
	$title      = ! empty( $get_title ) ? sanitize_text_field( $get_title ) : $site_title;
	if ( slash_admin( 'dashboard_widget_content' ) ) {
		wp_add_dashboard_widget( 'slashadmin_dashboard_widget', $title, 'slashadmin_dashboard_widget_content' );
	}
}

/* = Custom CSS
----------------------------------------------- */
add_action( 'admin_head', 'slashadmin_custom_admin_css' );

function slashadmin_custom_admin_css() {
	$output = sanitize_text_field( slash_admin( 'admin_css' ) );
	if ( ! empty( $output ) ) {
		echo '<style>' . $output . '</style>';
	}
}

