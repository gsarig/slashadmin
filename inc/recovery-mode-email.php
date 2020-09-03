<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Change the recovery mode email.
 * We need to make sure that the values passed are actual emails and that the user
 * didn't already set the RECOVERY_MODE_EMAIL constant, which would override our own setting.
 *
 * @link https://developer.wordpress.org/reference/hooks/recovery_mode_email/
 */
$address = slash_get_recovery_mode_emails();

if ( $address && ! defined( 'RECOVERY_MODE_EMAIL' ) && ! empty( $address ) ) {
	add_filter(
		'recovery_mode_email',
		function ( $email ) {
			$email['to'] = slash_get_recovery_mode_emails();

			return $email;
		}
	);
}

/**
 * Get the addresses and make sure that they are valid emails.
 * @return array
 */
function slash_get_recovery_mode_emails() {
	$overrides = slash_admin( 'recovery_mode_email' );
	$techie_id = slash_admin( 'slash_techie' );
	$output    = '';
	if ( $overrides ) {
		$output = array_filter( explode( ',', $overrides ), 'is_email' );
	} elseif ( $techie_id && '0' !== $techie_id ) {
		$user   = get_users( [
			'include' => [ intval( $techie_id ) ],
		] );
		$output = $user[0]->user_email;
	}

	return $output;
}