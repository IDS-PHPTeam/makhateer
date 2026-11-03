<?php
/**
 * Plugin Name: Fix Admin PHP 8.5 Compat (no plugin edits)
 * Description: Softens admin notices from third-party plugins without modifying plugin files.
 */

// ids_poll_ml: avoid Undefined array key "page" on unrelated admin screens.
add_action('admin_enqueue_scripts', function () {
	remove_action('admin_enqueue_scripts', 'ids_load_admin_scripts');
	remove_action('admin_enqueue_scripts', 'ids_load_admin_styles');

	if (!function_exists('ids_load_admin_scripts')) {
		return;
	}

	$is_poll_page = isset($_GET['page']) && $_GET['page'] === 'ids_poll_ml_add';
	if (!$is_poll_page) {
		return;
	}

	wp_enqueue_script('bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), true);
	wp_enqueue_script('ids-admin-poll-js', get_site_url() . '/wp-content/plugins/ids_poll_ml/js/admin.js', array('jquery'), true);
	wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
	wp_enqueue_style('ids-admin-poll-css', get_site_url() . '/wp-content/plugins/ids_poll_ml/css/admin.css');
}, 0);
