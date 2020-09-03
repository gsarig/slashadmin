<?php 

/**
 * Uninstall Slash Admin
 * 
 * @package Slash Admin
 * @since 1.0
 */

	// If uninstall is not called from WordPress, exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}

	$option_name = 'slashadmin_options';

	delete_option( $option_name );
