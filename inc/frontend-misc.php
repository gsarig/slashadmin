<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
 * Remove "Category:" from archives
 */
function slashadmin_remove_category( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	}
	if ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}
	if ( is_tax() ) {
		$title = single_term_title( '', false );
	}

	if ( is_tag() ) {
		$title = single_tag_title( '', false );
	}

	return $title;
}

if ( slash_admin( 'remove_category' ) ) {
	add_filter( 'get_the_archive_title', 'slashadmin_remove_category', 10, 2 );
}

/*
 * Excerpts to pages
 */
function slashadmin_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}

if ( slash_admin( 'pages_excerpt' ) ) {
	add_action( 'init', 'slashadmin_add_excerpts_to_pages' );
}

/*
 * Shortcodes in Widgets
 */
if ( slash_admin( 'widget_shortcodes' ) ) {
	add_filter( 'widget_text', 'do_shortcode' );
}