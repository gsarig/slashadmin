<?php

namespace SlashAdmin;

class Settings {
	/**
	 * Get plugin options
	 *
	 * @param $option
	 *
	 * @return mixed|bool|string
	 */
	public static function option( $option ) {
		if ( 'loader_enabled' === $option ) {
			$output = slash_admin( 'loading_enabled' ) !== false && slash_admin( 'loading_enabled' ) !== 'disabled';
		} else {
			$output = slash_admin( $option );
		}

		return isset( $output ) ? $output : '';
	}

	/**
	 * Check if current user is set as "Techie"
	 * @return bool
	 */
	public static function isTechie() {
		$techie_id  = self::option( 'slash_techie' );
		$current_id = get_current_user_id();

		return ! ( $techie_id && '0' !== $techie_id && $current_id !== (int) $techie_id );
	}

	/**
	 * Get the roles with editing privileges
	 * @return array
	 */
	public static function editorRoles() {
		return array(
			get_role( 'editor' ),
			get_role( 'shop_manager' ),
		);
	}
}