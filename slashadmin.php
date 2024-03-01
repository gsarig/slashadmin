<?php
/*
 * Plugin Name: Slash Admin
 * Plugin URI: http://wordpress.org/plugins/slash-admin/
 * Description: Slash Admin lets you change various different options in a WordPress website, keeps them active even if you switch your theme and helps you create a friendlier Admin Panel for you and your editors. 
 * Version: 3.8.3
 * Author: Giorgos Sarigiannidis
 * Author URI: http://www.gsarigiannidis.gr
 * Text Domain: slash-admin
 * Domain Path: /languages
*/

use SlashAdmin\{ACF, Consent, Email, Fonts, InternetExplorer, Loader, Scripts, SiteHealth, TaxonomyOrder};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SLASH_ADMIN_VERSION', '3.8.3' );

load_plugin_textdomain( 'slash-admin', false, basename( dirname( __FILE__ ) ) . '/languages' ); // Localize it

if ( is_admin() ) // Display the plugin's options at the backend
{
	require_once dirname( __FILE__ ) . '/options.php';
}

function slash_admin( $option
) { // enables us to reference any saved option we want via "echo slash_admin('option_id');".
	$options = get_option( 'slashadmin_options' );
	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	} else {
		return false;
	}
}

function slashadmin_settings_link( $links ) { // Add settings link on plugin page
	$settings_link = '<a href="tools.php?page=slashadmin-options">' . __( 'Settings', 'slash-admin' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'slashadmin_settings_link' );

// Get the site's pages in an array of the form: array( $page_id => $page_title )
function slashadmin_get_pages( $multiple = false ) {
	$get_pages = get_pages();
	$pages     = ( $multiple ) ? array() : array( 'none' => __( 'None', 'slash-admin' ) );
	foreach ( $get_pages as $page ) {
		$pages[ $page->ID ] = $page->post_title;
	}

	return $pages;
}

// Autoload Classes
include_once dirname( __FILE__ ) . '/inc/autoloader.php';

new TaxonomyOrder();
new InternetExplorer();
new Loader();
new Consent();
new Fonts();
new SiteHealth();
new ACF();
new Scripts();
new Email();

// Login screen
include_once dirname( __FILE__ ) . '/inc/login.php';

// Hide options for non Admins
include_once dirname( __FILE__ ) . '/inc/non-admins.php';

// Allow Editors to view additional options
include_once dirname( __FILE__ ) . '/inc/editors-allow.php';

// Enable maintenance mode
include_once dirname( __FILE__ ) . '/inc/maintenance.php';

// Limit revisions
include_once dirname( __FILE__ ) . '/inc/limit-revisions.php';

// White label
include_once dirname( __FILE__ ) . '/inc/white-label.php';

// Shortcodes
include_once dirname( __FILE__ ) . '/inc/shortcodes.php';

// Performance
include_once dirname( __FILE__ ) . '/inc/performance.php';

// Admin notices
include_once dirname( __FILE__ ) . '/inc/admin_notices.php';

// Developer functions
include_once dirname( __FILE__ ) . '/inc/dev-functions.php';

// Frontend Miscellaneous
include_once dirname( __FILE__ ) . '/inc/frontend-misc.php';

// Who is online
include_once dirname( __FILE__ ) . '/inc/whois-online.php';

// Jetpack
include_once dirname( __FILE__ ) . '/inc/jetpack.php';

// Custom Splash Page
include_once dirname( __FILE__ ) . '/inc/custom-splash.php';