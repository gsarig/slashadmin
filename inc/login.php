<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// enqueue styles for the login page
add_action( 'login_enqueue_scripts', 'slashadmin_login_enqueues' );

function slashadmin_login_enqueues() {
	if ( slash_admin( 'logo' ) !== '' || slash_admin( 'login_css' ) !== '' ) {
		echo '<style type="text/css">' . slashadmin_login_page() . slashadmin_login_css() . '</style>';
	}
}

/* = Upload login logo
----------------------------------------------- */
function slashadmin_login_page() {
	$image   = slash_admin( 'logo' );
	$headers = ! empty( $image ) && ini_get( 'allow_url_fopen' ) ? @get_headers( $image ) : '';
	if ( ! empty( $image ) && $headers && ( strpos( $headers[0], '404' ) === false ) && ( strpos( $headers[0],
				'403' ) === false ) && ini_get( 'allow_url_fopen' ) ) {
		$img_id = attachment_url_to_postid( $image );
		if ( $img_id ) { // First we check if the image has been uploaded on WordPress
			$img_meta = wp_get_attachment_metadata( $img_id );
			if ( isset( $img_meta['width'] ) && isset( $img_meta['height'] ) ) {
				$dimensions = array(
					$img_meta['width'],
					$img_meta['height'],
				);
			} else {
				$dimensions = array(
					'',
					'',
				);
			}
		} else { // If not (could be an external URL)
			$dimensions = getimagesize( $image );
		}
	} else {
		$dimensions = array(
			'',
			'',
		);
	}
	list( $width, $height ) = $dimensions; // Get the uploaded image's width and height
	if ( $width != '' && $height != '' && $width < 321 ) { // If width is recognized, use it
		$w = $width . 'px auto';
		$h = 'height: ' . $height . 'px;';
	} elseif ( $width > 320 ) { // but if it's more than 320 pixels, force it to 320px
		$r = ( $width / $height ); // calculate ratio
		$w = '320px auto';
		$h = 'height: ' . ( 320 / $r ) . 'px;';
	} else {
		$w = 'auto 80px';
		$h = '';
	}
	$output = 'body.login div#login h1 a {
				background-image: url(' . $image . ');
				background-size: ' . $w . ';'
	          . $h .
	          'width: 100%;
				background-position: bottom;
			}';

	return ( slash_admin( 'logo' ) !== '' ) ? $output : '';
}

/* = Login page custom CSS
----------------------------------------------- */
function slashadmin_login_css() {
	$output = slash_admin( 'login_css' );

	return ( slash_admin( 'login_css' ) !== '' ) ? $output : '';
}

/* = Fix links at the login screen
----------------------------------------------- */
if ( slash_admin( 'login_links' ) == 1 ) {
	function slashadmin_login_page_url() {
		return get_bloginfo( 'url' );
	}

	add_filter( 'login_headerurl', 'slashadmin_login_page_url' );

	function slashadmin_login_page_url_title() {
		return esc_attr( get_bloginfo( 'name', 'display' ) );
	}

	add_filter( 'login_headertext', 'slashadmin_login_page_url_title' );
}

/* = Redirect to front page after login
----------------------------------------------- */
if ( slash_admin( 'homepage_redirect' ) == 1 ) {
	function slash_login_redirect( $redirect_to, $request, $user ) {
		$slash_roles = isset( $user->roles );
		if ( is_array( $slash_roles ) && in_array( 'administrator', $slash_roles ) ) {
			return admin_url();
		} else {
			return site_url();
		}
	}

	add_filter( 'login_redirect', 'slash_login_redirect', 10, 3 );
}

/* = Disable Admin Bar for non-Admins
----------------------------------------------- */
if ( slash_admin( 'disable_adminbar' ) == 1 ) {

	add_action( 'after_setup_theme', 'remove_admin_bar' );

	function remove_admin_bar() {
		if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
			show_admin_bar( false );
		}
	}
}
