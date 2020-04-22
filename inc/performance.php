<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* = Disable scripts
----------------------------------------------- */
if ( slash_admin( 'remove_emojis' ) ) {
	function slash_remove_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}

	add_action( 'init', 'slash_remove_emojis' );

	// Filter function used to remove the tinymce emoji plugin.
	function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}

/* = Disable embeds
----------------------------------------------- */

function slash_remove_embeds() {
	if ( slash_admin( 'remove_embeds' ) && ( slash_admin( 'remove_embeds' ) !== 'default' ) ) {
		if (
			( slash_admin( 'remove_embeds' ) === 'disable' ) || // disable everywhere
			( slash_admin( 'remove_embeds' ) === 'singles' && ( ! is_singular() || is_front_page() ) ) || // enable on single posts, pages, custom posts only
			( slash_admin( 'remove_embeds' ) === 'home' && ! is_front_page() ) || // enable only on homepage
			( slash_admin( 'remove_embeds' ) === 'not_archives' && is_archive() ) // disable on archives
		) {
			wp_deregister_script( 'wp-embed' );
		}
	}
}

add_action( 'wp_footer', 'slash_remove_embeds' );


/* = DNS prefetching
----------------------------------------------- */
function slash_dns_prefetch() {

	$get_urls = slash_admin( 'dns_prefetch' );
	$urls     = ( ! empty( $get_urls ) ) ? explode( "\n", $get_urls ) : '';

	if ( ! empty( $urls ) ) :
		$urls   = array_map( 'esc_url', $urls );
		$output = '';
		foreach ( $urls as $url ) {
			$output .= '<link rel="dns-prefetch" href="' . $url . '" />' . "\n";
		}

		echo '<meta http-equiv="x-dns-prefetch-control" content="on">' . "\n" . $output;

	endif;
}

add_action( 'wp_head', 'slash_dns_prefetch', 0 );

/* = Prefetch & Prerender pages
----------------------------------------------- */
function slash_prefetch_prerender() {
	global $wp_query;
	$prefetch_next  = slash_admin( 'prefetch_next' );
	$prerender_next = slash_admin( 'prerender_next' );
	$prefetch_home  = slash_admin( 'prefetch_home' );
	$prerender_home = slash_admin( 'prerender_home' );
	$paged          = $wp_query->query_vars['paged'];

	if ( is_archive() && ( $paged > 1 ) && ( $paged < $wp_query->max_num_pages ) ) {
		$prefetch  = $prefetch_next == 1 ? '<link rel="prefetch" href="' . get_next_posts_page_link() . '">' . "\n" : '';
		$prerender = $prerender_next == 1 ? '<link rel="prerender" href="' . get_next_posts_page_link() . '">' . "\n" : '';
	} elseif ( is_singular() ) {
		$home      = get_home_url();
		$prefetch  = $prefetch_home == 1 ? '<link rel="prefetch" href="' . $home . '">' . "\n" : '';
		$prerender = $prerender_home == 1 ? '<link rel="prerender" href="' . $home . '">' . "\n" : '';
	} else {
		$prefetch  = '';
		$prerender = '';
	}

	if ( $prefetch_next == 1 || $prerender_next == 1 || $prefetch_home == 1 || $prerender_home == 1 ) {
		echo $prefetch . $prerender;
	}
}

add_action( 'wp_head', 'slash_prefetch_prerender', 1 );
