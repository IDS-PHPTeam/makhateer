<?php
/**
 * Plugin Name: WP Trigger Error Compat
 * Description: Provides the wp_trigger_error() helper for WordPress versions that pre-date 6.4.
 * Version: 1.0.0
 *
 * @package makhateer
 */

if ( ! function_exists( 'wp_trigger_error' ) ) {
	/**
	 * Triggers a user-level error/warning/notice/deprecation message.
	 *
	 * Backports the core function that was introduced in WordPress 6.4 so that
	 * plugins that already expect it can run on older installations.
	 *
	 * @since 1.0.0
	 *
	 * @param string $function_name The function that was called.
	 * @param string $message       A message explaining what has been done incorrectly.
	 * @param int    $error_level   Optional. The designated error type for this error.
	 */
	function wp_trigger_error( $function_name, $message, $error_level = E_USER_NOTICE ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		/**
		 * Fires when the given function triggers a user-level error/warning/notice/deprecation message.
		 *
		 * @param string $function_name The function that was called.
		 * @param string $message       A message explaining what has been done incorrectly.
		 * @param int    $error_level   The designated error type for this error.
		 */
		do_action( 'wp_trigger_error_run', $function_name, $message, $error_level );

		if ( ! empty( $function_name ) ) {
			$message = sprintf( '%s(): %s', $function_name, $message );
		}

		if ( function_exists( 'wp_kses' ) ) {
			$message = wp_kses(
				$message,
				array(
					'a'      => array( 'href' ),
					'br'     => array(),
					'code'   => array(),
					'em'     => array(),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);
		}

		trigger_error( $message, $error_level );
	}
}

if ( ! function_exists( 'wp_is_serving_rest_request' ) ) {
	/**
	 * Determines whether WordPress is currently serving a REST API request.
	 *
	 * Backports the helper introduced in WordPress 6.5 so plugins can safely
	 * call it on older installations.
	 *
	 * @since 1.1.0
	 *
	 * @return bool True if this is a REST request, false otherwise.
	 */
	function wp_is_serving_rest_request() {
		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}
}

