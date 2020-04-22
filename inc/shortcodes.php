<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* = Protect mail from harvesters
----------------------------------------------- */
if ( slash_admin( 'shortcode_mail' ) == 1 ) {

	// [slash_mail address="address-value"]
	function slash_mail_func( $atts ) {
		$a = shortcode_atts( array(
			'address' => get_the_author_meta( 'user_email' ),
		), $atts );

		return antispambot( $a['address'] );
	}

	add_shortcode( 'slash_mail', 'slash_mail_func' );

	// [slash_mailto address="address-value"]
	function slash_mailto_func( $atts ) {
		$a       = shortcode_atts( array(
			'address' => get_the_author_meta( 'user_email' ),
		), $atts );
		$address = antispambot( $a['address'] );

		return '<a href="mailto:' . $address . '">' . $address . '</a>';
	}

	add_shortcode( 'slash_mailto', 'slash_mailto_func' );
}

/* = Enable relative URLs
----------------------------------------------- */
if ( slash_admin( 'shortcode_url' ) == 1 ) {

	// [slash_home]
	function slash_url_home( $atts ) {

		return home_url();
	}

	add_shortcode( 'slash_home', 'slash_url_home' );

	// [slash_theme]
	function slash_url_theme( $atts ) {

		return get_template_directory_uri();
	}

	add_shortcode( 'slash_theme', 'slash_url_theme' );

	// [slash_child]
	function slash_url_child( $atts ) {

		return get_stylesheet_directory_uri();
	}

	add_shortcode( 'slash_child', 'slash_url_child' );
}

/* = Display phone numbers
----------------------------------------------- */
if ( slash_admin( 'shortcode_phone' ) == 1 ) {
	function slash_phone_shortcode( $atts ) {
		$a = shortcode_atts( array(
			'number' => null,
			'prefix' => null,
			'text'   => null
		), $atts );

		$tel    = filter_var( $a['number'], FILTER_SANITIZE_NUMBER_INT );
		$prefix = filter_var( $a['prefix'], FILTER_SANITIZE_NUMBER_INT );
		$txt    = ( $a['text'] ) ? filter_var( $a['text'], FILTER_SANITIZE_STRING ) : $tel;

		return ( $tel ) ? '<a href="tel:' . $prefix . $tel . '">' . $txt . '</a>' : '';
	}

	add_shortcode( 'slash_phone', 'slash_phone_shortcode' );

}
