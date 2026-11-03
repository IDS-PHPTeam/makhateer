<?php
/**
 * Plugin Name: Fix ids_poll missing lang attr (no plugin edits)
 * Description: ids_poll shortcode requires [lang]; default it from locale so PHP 8 does not warn.
 */

add_action('init', function () {
	if (!function_exists('ids_poll_form_shortcode')) {
		return;
	}

	remove_shortcode('ids_poll');
	add_shortcode('ids_poll', function ($attributes) {
		$attributes = shortcode_atts(
			array(
				'id'   => 0,
				'lang' => (get_locale() === 'ar') ? 'ar' : 'en',
			),
			is_array($attributes) ? $attributes : array(),
			'ids_poll'
		);

		ob_start();
		ids_poll_form_shortcode($attributes);
		return ob_get_clean();
	});
}, 20);
