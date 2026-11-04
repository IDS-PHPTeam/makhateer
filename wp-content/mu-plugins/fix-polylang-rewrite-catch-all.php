<?php
/**
 * Plugin Name: Fix Polylang / page slug rewrite
 * Description: Polylang + WP 7.1 can leave a catch-all rewrite that breaks pages (404 → redirect home). Also fixes leftover pagename+name rules that make WP_Query return 0 rows, and URL-encoded Arabic page slugs.
 */

if (!defined('ABSPATH')) {
	exit;
}

function ids_pll_valid_lang_slugs()
{
	if (function_exists('pll_languages_list')) {
		$slugs = pll_languages_list(array('fields' => 'slug'));
		if (is_array($slugs) && $slugs) {
			return array_map('strval', $slugs);
		}
	}
	return array('ar', 'en');
}

/**
 * Normalize the single-segment catch-all rewrite.
 * IMPORTANT: do NOT set both pagename and name — WP_Query then finds 0 posts.
 */
add_filter('rewrite_rules_array', function ($rules) {
	if (!is_array($rules)) {
		return $rules;
	}

	$key = '([^/]+)/?$';
	if (!isset($rules[$key])) {
		return $rules;
	}

	$current = $rules[$key];
	$bad = array(
		'index.php?lang=$matches[1]',
		'index.php?pagename=$matches[1]&name=$matches[1]',
		'index.php?name=$matches[1]&pagename=$matches[1]',
	);

	if (in_array($current, $bad, true)) {
		// Pages use postname permalinks on this site; resolve as pagename.
		$rules[$key] = 'index.php?pagename=$matches[1]';
	}

	return $rules;
}, 100);

/**
 * Request fixes:
 * - invalid lang=<slug> → pagename
 * - if pagename misses, try as post name
 * - normalize URL-encoded Arabic slugs stored encoded in post_name
 */
add_filter('request', function ($query_vars) {
	// Stolen by lang catch-all.
	if (!empty($query_vars['lang']) && is_string($query_vars['lang'])) {
		$lang = $query_vars['lang'];
		if (!in_array($lang, ids_pll_valid_lang_slugs(), true)) {
			unset($query_vars['lang'], $query_vars['taxonomy'], $query_vars['term']);
			if (isset($query_vars['post_type']) && $query_vars['post_type'] === 'post') {
				unset($query_vars['post_type']);
			}
			$query_vars['pagename'] = $lang;
			unset($query_vars['name']);
		}
	}

	// Never keep both — kills the main query.
	if (!empty($query_vars['pagename']) && !empty($query_vars['name'])) {
		unset($query_vars['name']);
	}

	$slug = '';
	if (!empty($query_vars['pagename']) && is_string($query_vars['pagename'])) {
		$slug = $query_vars['pagename'];
	} elseif (!empty($query_vars['name']) && is_string($query_vars['name'])) {
		$slug = $query_vars['name'];
	}

	if ($slug === '') {
		return $query_vars;
	}

	$candidates = array_unique(array_filter(array(
		$slug,
		rawurldecode($slug),
		rawurlencode(rawurldecode($slug)),
		// Undo accidental double-encoding (%25d8… → %d8…).
		str_replace('%25', '%', $slug),
	)));

	foreach ($candidates as $candidate) {
		$page = get_page_by_path($candidate);
		if ($page instanceof WP_Post) {
			$query_vars['pagename'] = $page->post_name;
			unset($query_vars['name']);
			return $query_vars;
		}
	}

	// Not a page — treat as post slug.
	if (!empty($query_vars['pagename']) && empty($query_vars['name'])) {
		$post = get_page_by_path($slug, OBJECT, 'post');
		if (!$post) {
			// get_page_by_path with post type post is odd; try WP_Query name.
			$q = new WP_Query(array(
				'name' => $slug,
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'no_found_rows' => true,
			));
			if ($q->have_posts()) {
				$query_vars['name'] = $slug;
				unset($query_vars['pagename']);
			}
		}
	}

	return $query_vars;
}, 1);

/**
 * Flush rewrite rules once after this fix / WP|Polylang version change.
 */
add_action('init', function () {
	$flag = 'ids_pll_rewrite_fix_v4';
	$pll = defined('POLYLANG_VERSION') ? POLYLANG_VERSION : '0';
	$mark = get_bloginfo('version') . '|pll:' . $pll . '|v4';
	if (get_option($flag) === $mark) {
		return;
	}
	flush_rewrite_rules(false);
	update_option($flag, $mark, true);
}, 99);
