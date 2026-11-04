<?php
/**
 * Safe [rb-resume-contact] template (no plugin edits).
 * Plugin contact.php uses $contact_info_html when contact fields are empty → PHP 8 warning.
 */

add_action('init', function () {
	if (!function_exists('Resume_Builder')) {
		return;
	}

	remove_shortcode('rb-resume-contact');
	add_shortcode('rb-resume-contact', 'makhateer_rb_resume_contact_shortcode');
}, 20);

/**
 * @param array|string $atts Shortcode attributes.
 * @return string|false
 */
function makhateer_rb_resume_contact_shortcode($atts)
{
	$plugin = Resume_Builder();
	if (empty($plugin->resume_shortcodes) || !is_object($plugin->resume_shortcodes)) {
		return '';
	}

	global $resume, $compact;

	$options = shortcode_atts(
		array(
			'id'    => false,
			'style' => false,
		),
		$atts
	);

	if (!$options['id']) {
		return false;
	}

	$resume_id = esc_html($options['id']);
	$resume    = $plugin->resume_shortcodes->get_resume($resume_id);
	$compact   = ($options['style'] === 'compact');

	if (!$resume || empty($resume)) {
		return false;
	}

	ob_start();
	include WPMU_PLUGIN_DIR . '/resume-builder-contact-safe/contact.php';
	return ob_get_clean();
}
