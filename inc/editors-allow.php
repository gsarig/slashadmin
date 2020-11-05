<?php

use SlashAdmin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( slash_admin( 'editors_allow_menus' ) || slash_admin( 'editors_allow_widgets' ) || slash_admin( 'editors_allow_customizer' ) || slash_admin( 'editors_allow_background' ) ) {

	// Allow editors to view theme options
	function slashadmin_add_caps() {
		foreach ( Settings::editorRoles() as $role ) {
			if ( $role && ! $role->has_cap( 'edit_theme_options' ) ) {
				$role->add_cap( 'edit_theme_options' );
			}
		}
	}

	add_action( 'admin_init', 'slashadmin_add_caps' );

	// Call the script that hides the unwanted options from the Admin interface
	function editors_allow_scripts() {
		if ( ! current_user_can( 'activate_plugins' ) && is_user_logged_in() ) { // Check if current user is an administrator. If not:
			// Call the editors-allow.js script
			wp_enqueue_script( 'editors-allow-scripts',
				plugins_url( 'js/editors-allow.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				'1.0',
				true );
			// Set the variables
			$themes       = 'li#menu-appearance.wp-has-submenu li a[href="themes.php"]';
			$submenu      = 'li#menu-appearance.wp-has-submenu a.wp-has-submenu';
			$themes_front = 'li#wp-admin-bar-themes';

			if ( ! slash_admin( 'editors_allow_menus' ) ) {
				$menus       = 'li#menu-appearance.wp-has-submenu li a[href="nav-menus.php"]';
				$menus_front = 'li#wp-admin-bar-menus';
			} else {
				$menus       = '';
				$menus_front = '';
			}
			if ( ! slash_admin( 'editors_allow_widgets' ) ) {
				$widgets       = 'li#menu-appearance.wp-has-submenu li a[href^="widgets.php"]';
				$widgets_front = 'li#wp-admin-bar-widgets';
			} else {
				$widgets       = '';
				$widgets_front = '';
			}
			if ( ! slash_admin( 'editors_allow_customizer' ) ) {
				$customizer       = 'li#menu-appearance.wp-has-submenu li a[href^="customize.php"]';
				$customizer_front = 'li#wp-admin-bar-customize';
			} else {
				$customizer       = '';
				$customizer_front = '';
			}
			if ( ! slash_admin( 'editors_allow_background' ) ) {
				$background       = 'li#menu-appearance.wp-has-submenu li a[href*="background"]';
				$background_front = 'li#wp-admin-bar-background';
			} else {
				$background       = '';
				$background_front = '';
			}
			$params = array(
				'themes'      => $themes,
				'submenu'     => $submenu,
				'menus'       => $menus,
				'widgets'     => $widgets,
				'customize'   => $customizer,
				'bg'          => $background,
				'themes_f'    => $themes_front,
				'menus_f'     => $menus_front,
				'widgets_f'   => $widgets_front,
				'customize_f' => $customizer_front,
				'bg_f'        => $background_front,

			);
			// Pass the variables to the script via wp_localize_script
			wp_localize_script( 'editors-allow-scripts', 'editorsAllow', $params );

		} // if( !current_user_can('activate_plugins') )

	} // function editors_allow_scripts()

	// Enqueue the script on both backend and frontend
	add_action( 'admin_enqueue_scripts', 'editors_allow_scripts' );
	add_action( 'wp_enqueue_scripts', 'editors_allow_scripts' );

} else {
	// If no option is selected and if the editor has access to the theme options, revoke that access
	function slashadmin_remove_caps() {
		foreach ( Settings::editorRoles() as $role ) {
			if ( $role && $role->has_cap( 'edit_theme_options' ) ) {
				$role->remove_cap( 'edit_theme_options' );
			}
		}
	}

	add_action( 'admin_init', 'slashadmin_remove_caps' );
}

/**
 * Allow Editors to view the Gravity Forms Entries
 *
 * Run only if Gravity Forms plugin is active
 * and perform checks to make sure that it only runs once,
 * to avoid unnecessary db calls every time the admin loads.
 *
 */
add_action( 'admin_init', 'slashadmin_gravityforms_permissions' );
function slashadmin_gravityforms_permissions() {
	if ( is_plugin_active( 'gravityforms/gravityforms.php' ) && ! function_exists( 'slash_bypass_gravityforms_permissions' ) ) {
		$role1 = get_role( 'editor' );
		$role2 = get_role( 'shop_manager' );
		$sa    = slash_admin( 'editors_gravityforms' ) ? true : false;
		$gf1   = ( ! empty( $role1->capabilities['gravityforms_view_entries'] ) ) ? $role1->capabilities['gravityforms_view_entries'] : false;
		$gf2   = ( ! empty( $role2->capabilities['gravityforms_view_entries'] ) ) ? $role2->capabilities['gravityforms_view_entries'] : false;

		// Run add_cap only if Slash Admin option is set to active and capability isn't already set.
		if ( $sa && $gf1 === false ) {
			$role1->add_cap( 'gravityforms_view_entries' );
			if ( $role2 ) {
				$role2->add_cap( 'gravityforms_view_entries' );
			}
		}
		// Run remove_cap only if Slash Admin option is empty or false and capability is set.
		if ( ( empty( $sa ) || $sa === false ) && ( $gf1 === true || $gf2 === true ) ) {
			$role1->remove_cap( 'gravityforms_view_entries' );
			if ( $role2 ) {
				$role2->remove_cap( 'gravityforms_view_entries' );
			}
		}
	}

	/**
	 *  Remove Gravity Forms' "Add Form" button from all WYSIWYG editors
	 */
	if ( is_plugin_active( 'gravityforms/gravityforms.php' ) && slash_admin( 'editors_gravityforms_remove_button' ) && ! function_exists( 'slash_bypass_gravityforms_permissions' ) ) {
		add_filter( 'gform_display_add_form_button',
			function () {
				return false;
			} );
	}
}
