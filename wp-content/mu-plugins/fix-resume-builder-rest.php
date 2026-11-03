<?php
/**
 * Plugin Name: Fix Resume Builder REST (no plugin edits)
 * Description: WP REST API Controller disables rb_resume REST, which makes Resume Builder admin spin forever. Re-enable it with the rest_base the plugin JS expects.
 */

/**
 * Ensure controller settings expose rb_resume at /wp/v2/resumes/.
 */
add_action('plugins_loaded', function () {
	$option_key = 'wp_rest_api_controller_post_types_rb_resume';
	$options    = get_option($option_key, array());
	if (!is_array($options)) {
		$options = array();
	}

	$needs_update = empty($options['active']) || (string) $options['active'] !== '1'
		|| empty($options['rest_base']) || $options['rest_base'] !== 'resumes';

	if (!$needs_update) {
		return;
	}

	$options['active']    = '1';
	$options['rest_base'] = 'resumes';
	update_option($option_key, $options, false);
}, 5);

/**
 * After WP REST API Controller (init 100), force rb_resume back into the REST API.
 */
add_action('init', function () {
	global $wp_post_types;

	if (empty($wp_post_types['rb_resume']) || !is_object($wp_post_types['rb_resume'])) {
		return;
	}

	$wp_post_types['rb_resume']->show_in_rest = true;
	$wp_post_types['rb_resume']->rest_base    = 'resumes';
}, 101);

/**
 * Plugin api.php reads $resume_meta['resume_file'] without a guard; on PHP 8+ that warning
 * is printed into the JSON body and the admin Vue app never leaves its loading state.
 */
add_action('rest_api_init', function () {
	$safe_file_callback = static function ($object) {
		$post_id     = isset($object['id']) ? (int) $object['id'] : 0;
		$resume_meta = get_post_meta($post_id, '_resume_settings', true);
		if (!is_array($resume_meta)) {
			return null;
		}
		return isset($resume_meta['resume_file']) ? $resume_meta['resume_file'] : null;
	};

	foreach (array('rb_resume', 'search-result') as $object_type) {
		register_rest_field(
			$object_type,
			'resume_file',
			array(
				'get_callback' => $safe_file_callback,
				'schema'       => null,
			)
		);
	}
}, 20);