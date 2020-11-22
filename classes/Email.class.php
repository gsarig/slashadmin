<?php

namespace SlashAdmin;

class Email {
	public function __construct() {
		add_filter( 'recovery_mode_email', array( $this, 'setRecoveryModeEmails' ) );
		add_filter( 'auto_plugin_theme_update_email', 'setAutoUpdateEmail', 10, 4 );
		if ( Settings::option( 'obfuscate_email' ) ) {
			add_filter( 'the_content', array( $this, 'obfuscate' ) );
		}
	}

	/**
	 * Obfuscate emails
	 * Can also be used independently on any content like so:
	 * \SlashAdmin\Email::obfuscate($your_content)
	 *
	 * @param $content
	 *
	 * @return string|void
	 */

	public static function obfuscate( $content ) {
		$pattern = "/[a-zA-Z\d]*@[a-zA-Z\d]*\.[a-zA-Z\.]*/";
		preg_match_all( $pattern, $content, $matches );

		$content = preg_replace_callback( $pattern,
			function ( $matches ) {
				return antispambot( $matches[0] );
			},
			$content
		);

		return $content;
	}

	/**
	 * Change the email that receives the plugin update notifications
	 *
	 * @param $email
	 *
	 * @return mixed
	 */
	public function setAutoUpdateEmail( $email ) {
		$techie_id = Settings::option( 'slash_techie' );
		if ( $techie_id && '0' !== $techie_id ) {
			$user         = get_users( [
				'include' => [ (int) $techie_id ],
			] );
			$techie_email = $user[0]->user_email;
			if ( $techie_email ) {
				$email['to'] = $techie_email;
			}
		}

		return $email;
	}

	/**
	 * Change the recovery mode email.
	 * We need to make sure that the values passed are actual emails and that the user
	 * didn't already set the RECOVERY_MODE_EMAIL constant, which would override our own setting.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/recovery_mode_email/
	 *
	 * @param $email
	 *
	 * @return mixed
	 */
	public function setRecoveryModeEmails( $email ) {
		$address = $this->getRecoveryModeEmails();
		if ( $address && ! defined( 'RECOVERY_MODE_EMAIL' ) ) {
			$email['to'] = $address;
		}

		return $email;
	}

	/**
	 * Get the addresses and make sure that they are valid emails.
	 * @return array
	 */
	private function getRecoveryModeEmails() {
		$overrides = Settings::option( 'recovery_mode_email' );
		$techie_id = Settings::option( 'slash_techie' );
		$output    = '';
		if ( $overrides ) {
			$output = array_filter( explode( ',', $overrides ), 'is_email' );
		} elseif ( $techie_id && '0' !== $techie_id ) {
			$user   = get_users( [
				'include' => [ (int) $techie_id ],
			] );
			$output = $user[0]->user_email;
		}

		return $output;
	}
}