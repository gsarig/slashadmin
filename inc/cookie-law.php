<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$cookie_law = slash_admin( 'cookielaw_enable' );
if ( ! isset( $_COOKIE['eucookie'] ) && isset( $cookie_law ) && false !== $cookie_law ) {

	/* = Enqueeue scripts and styles
	----------------------------------------------- */
	add_action( 'wp_enqueue_scripts', 'slashadmin_cookielaw_enqueues' );

	function slashadmin_cookielaw_enqueues() {
		wp_enqueue_style( 'slashadmin-cookielaw', plugins_url( 'css/cookielaw.css', dirname( __FILE__ ) ) );
		wp_enqueue_script( 'slashadmin-cookielaw',
			plugins_url( 'js/cookielaw.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			SLASH_ADMIN_VERSION,
			false );
	}

	/* = The consent box
	----------------------------------------------- */
	function slashadmin_cookiebox() {
		$style  = slash_admin( 'cookielaw_style' );
		$pos    = slash_admin( 'cookielaw_position' );
		$class  = ( 'dark' !== $style ) ? $style : 'dark';
		$pos_cl = ( 'bottom' !== $pos ) ? $pos : 'bottom';

		$pageid      = slash_admin( 'cookielaw_url' );
		$url         = ( function_exists( 'icl_object_id' ) ) ? icl_object_id( $pageid, 'page', true ) : $pageid;
		$get_excerpt = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $url ) );
		$excerpt     = wp_strip_all_tags( $get_excerpt, true );
		$accept_meta = get_post_meta( $url, 'slash_accept', true );
		$accept      = ( isset( $accept_meta ) && ! empty( $accept_meta ) ) ? $accept_meta : slash_admin( 'cookielaw_accept' );
		if ( slash_admin( 'cookielaw_pagedata' ) && ! empty( $url ) && 'none' !== $url ) {
			$text = ( ! empty( $excerpt ) ) ? $excerpt : get_the_title( $url );
			$more = ( ! empty( $excerpt ) ) ? get_the_title( $url ) : slash_admin( 'cookielaw_readmore' );
			$link = ' <a id="slashadmin_cookiemore" href="' . get_the_permalink( $url ) . '">' . $more . '</a>';
		} elseif ( ! empty( $url ) && 'none' !== $url ) {
			$text = slash_admin( 'cookielaw_message' );
			$more = slash_admin( 'cookielaw_readmore' );
			$link = ' <a id="slashadmin_cookiemore" href="' . get_the_permalink( $url ) . '">' . $more . '</a>';
		} else {
			$text = slash_admin( 'cookielaw_message' );
			$link = '';
		}
		echo '<div id="slashadmin_eucookielaw" class="cl_' . $pos_cl . ' cl_' . $class . '">
					<p>' . $text . $link . '<span id="slashadmin_removecookie">' . $accept . '</span></p>
				</div>';
	}

	add_action( 'wp_footer', 'slashadmin_cookiebox' );

	$pageid = slash_admin( 'cookielaw_url' );
	$url    = ( function_exists( 'icl_object_id' ) ) ? icl_object_id( $pageid, 'page', true ) : $pageid;
	if ( slash_admin( 'cookielaw_pagedata' ) && ! empty( $url ) && 'none' !== $url ) {
		add_post_meta( $url, 'slash_accept', 'Accept', true );
	}
}
