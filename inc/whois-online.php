<?php
/**
 * Who is online
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//Update user online status
add_action( 'init', 'slashadmin_users_status_init' );
add_action( 'admin_init', 'slashadmin_users_status_init' );
function slashadmin_users_status_init() {
	$logged_in_users = get_transient( 'users_status' ); //Get the active users from the transient.
	$user            = wp_get_current_user(); //Get the current user's data

	//Update the user if they are not on the list, or if they have not been online in the last 900 seconds (15 minutes)
	if ( ! isset( $logged_in_users[ $user->ID ]['last'] ) || $logged_in_users[ $user->ID ]['last'] <= time() - 900 ) {
		$logged_in_users[ $user->ID ] = array(
			'id'       => $user->ID,
			'username' => $user->user_login,
			'last'     => time(),
		);
		set_transient( 'users_status', $logged_in_users, 900 ); //Set this transient to expire 15 minutes after it is created.
	}
}

//Check if a user has been online in the last 15 minutes
function slashadmin_is_user_online( $id ) {
	$logged_in_users = get_transient( 'users_status' ); //Get the active users from the transient.

	return isset( $logged_in_users[ $id ]['last'] ) && $logged_in_users[ $id ]['last'] > time() - 900; //Return boolean if the user has been online in the last 900 seconds (15 minutes).
}

//Check when a user was last online.
function slashadmin_user_last_online( $id ) {
	$logged_in_users = get_transient( 'users_status' ); //Get the active users from the transient.

	//Determine if the user has ever been logged in (and return their last active date if so).
	if ( isset( $logged_in_users[ $id ]['last'] ) ) {
		return $logged_in_users[ $id ]['last'];
	} else {
		return false;
	}
}

//Get an array of online user IDs.
function slashadmin_online_users() {
	$logged_in_users = get_transient( 'users_status' );

	//If no users are online
	if ( empty( $logged_in_users ) ) {
		return false;
	}

	$user_online_count = 0;
	$online_users      = array();
	foreach ( $logged_in_users as $user ) {
		if ( ! empty( $user['username'] ) && isset( $user['last'] ) && $user['last'] > time() - 900 ) { //If the user has been online in the last 900 seconds, add them to the array and increase the online count.
			$online_users[] = $user;
			$user_online_count ++;
		}
	}

	return $online_users;
}

// Output the results
function slash_whois_online() {

	global $pagenow;

	if ( $pagenow === 'tools.php' && current_user_can( 'install_plugins' ) ) :
		$users     = slashadmin_online_users();
		$current   = get_current_user_id();
		$usernames = array();
		foreach ( $users as $user ) {
			if ( $user['id'] !== $current ) {
				$usernames[] = '<strong>' . $user['username'] . '</strong>';
			}
		}
		if ( $usernames ) {
			echo '<div class="notice-info notice"><p>' . __( 'Recently online (logged in less than 15 minutes ago): ', 'slash-admin' ) . implode( ', ', $usernames ) . '</p></div>';
		}
	endif;

}

add_action( 'admin_notices', 'slash_whois_online' );