<?php

namespace SlashAdmin;

class SiteHealth {

	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'removeDashboard' ) );
		add_action( 'admin_menu', array( $this, 'removeSubmenu' ), 999 );
	}

	public function removeDashboard() {
		if ( Settings::isTechie() ) {
			return;
		}
		global $wp_meta_boxes;
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
	}

	public function removeSubmenu() {
		if ( Settings::isTechie() ) {
			return;
		}
		remove_submenu_page( 'tools.php', 'site-health.php' );
	}
}