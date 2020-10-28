<?php

use SlashAdmin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( slash_admin( 'hide_update_notices' ) == 1 ) {

	function my_admin_theme_style() {
		if ( ! current_user_can( 'manage_options' ) || ! Settings::isTechie() ) {
			echo '<style>.update-nag, .updated { display: none; }</style>';
		}
	}

	add_action( 'admin_enqueue_scripts', 'my_admin_theme_style' );
	add_action( 'login_enqueue_scripts', 'my_admin_theme_style' );
}

// Remove unnecessary options for non-admins
if ( slash_admin( 'exclude_links' ) && slash_admin( 'exclude_comments' ) && slash_admin( 'exclude_media' ) && slash_admin( 'exclude_posts' ) && slash_admin( 'exclude_tools' ) && slash_admin( 'exclude_pages' ) && slash_admin( 'exclude_profile' ) == 0 ) {
	echo '';
} else {
	$role = get_role( 'administrator' ); // get the "author" role object
	$role->add_cap( 'see_all_menus' ); // add "see_all_menus" to this role object

	function remove_menu_items() {
		if ( ! current_user_can( 'see_all_menus' ) ) { // hide items only for non-admins
			global $menu;
			if ( slash_admin( 'exclude_comments' ) == 1 ) {
				$comments = 'Comments';
			} else {
				$comments = '';
			}
			if ( slash_admin( 'exclude_media' ) == 1 ) {
				$media = 'Media';
			} else {
				$media = '';
			}
			if ( slash_admin( 'exclude_posts' ) == 1 ) {
				$posts = 'Posts';
			} else {
				$posts = '';
			}
			if ( slash_admin( 'exclude_tools' ) == 1 ) {
				$tools = 'Tools';
			} else {
				$tools = '';
			}
			if ( slash_admin( 'exclude_pages' ) == 1 ) {
				$pages = 'Pages';
			} else {
				$pages = '';
			}
			if ( slash_admin( 'exclude_profile' ) == 1 ) {
				$profile = 'Profile';
			} else {
				$profile = '';
			}
			if ( slash_admin( 'exclude_custom' ) !== '' ) { // Set up to 5 custom excludes
				$custom = slash_admin( 'exclude_custom' );
				$values = explode( ',', $custom );
				if ( isset( $values[0] ) ) {
					$cfirst = $values[0];
				} else {
					$cfirst = '';
				}
				if ( isset( $values[1] ) ) {
					$csecond = $values[1];
				} else {
					$csecond = '';
				}
				if ( isset( $values[2] ) ) {
					$cthird = $values[2];
				} else {
					$cthird = '';
				}
				if ( isset( $values[3] ) ) {
					$cfourth = $values[3];
				} else {
					$cfourth = '';
				}
				if ( isset( $values[4] ) ) {
					$cfifth = $values[4];
				} else {
					$cfifth = '';
				} // Custom excludes end here
			} else {
				$cfirst  = '';
				$csecond = '';
				$cthird  = '';
				$cfourth = '';
				$cfifth  = '';
			}
			$restricted = array(
				__( $comments ),
				__( $media ),
				__( $posts ),
				__( $tools ),
				__( $pages ),
				__( $profile ),
				__( $cfirst ),
				__( $csecond ),
				__( $cthird ),
				__( $cfourth ),
				__( $cfifth ),
			);
			end( $menu );
			while ( prev( $menu ) ) {
				$value = explode( ' ', $menu[ key( $menu ) ][0] );
				if ( in_array( $value[0] != null ? $value[0] : "", $restricted ) ) {
					unset( $menu[ key( $menu ) ] );
				}
			}
		}
	}

	add_action( 'admin_menu', 'remove_menu_items' );
}

// Exclude menu options by URL
add_action( 'admin_head', 'slash_css_hide' );
function slash_css_hide() {
	$ids    = sanitize_text_field( slash_admin( 'css_excludes' ) );
	$output = '<style>
			 ' . $ids . '{
				display: none;
			} 
		</style>';

	if ( ! current_user_can( 'install_plugins' ) ) {
		echo $output;
	}
}

// Remove Jetpack for non-admins
function slash_remove_jetpack() {
	if ( slash_admin( 'exclude_jetpack' ) == 1 && class_exists( 'Jetpack' ) && ! current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'jetpack' );
	}
}

add_action( 'admin_menu', 'slash_remove_jetpack', 999 );


// Prevent Post Updates and Deletion After a Set Period
if ( slash_admin( 'prevent_editing' ) !== 'default' ) {

	function slashadmin_restrict_editing( $allcaps, $cap, $args ) {
		if ( 'edit_post' != $args[0] && 'delete_post' != $args[0] // Bail out if we're not asking to edit or delete a post
		     || ! empty( $allcaps['manage_options'] ) // or user is admin
		     || empty( $allcaps['edit_posts'] )
		) // or user already cannot edit the post
		{
			return $allcaps;
		}

		$post = get_post( $args[2] ); // Load the post data
		if ( 'publish' != $post->post_status ) // Bail out if the post isn't published
		{
			return $allcaps;
		}

		if ( slash_admin( 'prevent_editing' ) == 'oneday' ) {
			$days = '-1 day';
		} elseif ( slash_admin( 'prevent_editing' ) == 'oneweek' ) {
			$days = '-7 day';
		} elseif ( slash_admin( 'prevent_editing' ) == 'twoweeks' ) {
			$days = '-14 day';
		} elseif ( slash_admin( 'prevent_editing' ) == 'onemonth' ) {
			$days = '-30 day';
		}
		if ( strtotime( $post->post_date ) < strtotime( $days ) ) { //if post is older than 30 days
			$allcaps[ $cap[0] ] = false; // Disallow editing
		}

		return $allcaps;
	}

	add_filter( 'user_has_cap', 'slashadmin_restrict_editing', 10, 3 );
}

/*
 * Remove tags and categories
 */
if ( slash_admin( 'remove_tags' ) || slash_admin( 'remove_categories' ) ) {
	function slash_unregister_taxonomy() {
		if ( slash_admin( 'remove_tags' ) ) {
			register_taxonomy( 'post_tag', array() );
		}
		if ( slash_admin( 'remove_categories' ) ) {
			register_taxonomy( 'category', array() );
		}
	}

	add_action( 'init', 'slash_unregister_taxonomy' );
}

/*
 * Hide specific pages for non admins
 */

if ( slash_admin( 'hide_specific_pages' ) ) {

	function slash_disable_page_edit() {

		// Get the pages that you need to hide
		function slash_get_excluded_pages() {
			$output = array();
			foreach ( slash_admin( 'hide_specific_pages' ) as $page ) {
				$output[] = (int) $page;
			}

			return $output;
		}

		// Hide them from the backend for non-admins
		add_action( 'pre_get_posts', 'slash_hide_pages_backend' );
		function slash_hide_pages_backend( $query ) {
			if ( is_admin() && ! empty( $_GET['post_type'] ) && $_GET['post_type'] == 'page' && $query->query['post_type'] == 'page' && ! current_user_can( 'administrator' ) ) {
				$query->set( 'post__not_in',
					slash_get_excluded_pages()
				);
			}
		}

		// Remove the Edit link from the admin bar (we add is_home() to include the blogposts page as well)
		function slash_remove_admin_bar_edit_link() {

			if ( ! current_user_can( 'administrator' ) && ( is_page( slash_get_excluded_pages() ) || is_home() ) ) {

				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'edit' );

			}
		}

		add_action( 'wp_before_admin_bar_render', 'slash_remove_admin_bar_edit_link' );
	}

	slash_disable_page_edit();

}
