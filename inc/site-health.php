<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 *   Remove Site Health from the Admin if the user is not the "Techie"
 */
add_action( 'admin_menu', 'slash_remove_site_health_submenu', 999 );
add_action( 'wp_dashboard_setup', 'slash_remove_site_health_dashboard' );

function slash_remove_site_health_dashboard() {
	if ( ! is_slash_techie() ) {
		global $wp_meta_boxes;
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
	}
}

function slash_remove_site_health_submenu() {
	if ( ! is_slash_techie() ) {
		$page = remove_submenu_page( 'tools.php', 'site-health.php' );
	}
}