<?php
/**
 * Plugin Name: Fix Stats SVG Breaks
 * Description: Keeps homepage donut chart CSS inside style tags and strips wpautop br/p from SVG/CSS.
 */

/**
 * Before wpautop: ensure chart CSS is wrapped in <style> so it is not turned into visible text.
 */
add_filter('the_content', function ($content) {
	if (strpos($content, 'ani_donut') === false) {
		return $content;
	}
	if (preg_match('/<style[^>]*>[\s\S]*ani_donut[\s\S]*<\/style>/i', $content)) {
		return $content;
	}
	return preg_replace_callback(
		'/(#chart0\s*\.ani_donut[\s\S]*?)(?=<div\s+id=["\']Stage["\']|<svg\b)/i',
		static function ($m) {
			$css = preg_replace('/<br\s*\/?>/i', '', $m[1]);
			$css = preg_replace('/<\/?p\b[^>]*>/i', '', $css);
			return '<style type="text/css">' . trim($css) . '</style>';
		},
		$content,
		1
	);
}, 8);

/**
 * After wpautop: strip <br>/<p> injected into style/svg markup.
 */
add_filter('the_content', function ($content) {
	if (strpos($content, 'ani_donut') === false) {
		return $content;
	}

	// Recover if CSS still leaked into a paragraph.
	$content = preg_replace_callback(
		'/<p>\s*(#chart0\s*\.ani_donut[\s\S]*?)<\/p>/i',
		static function ($m) {
			$css = preg_replace('/<br\s*\/?>/i', '', $m[1]);
			return '<style type="text/css">' . trim($css) . '</style>';
		},
		$content
	);

	$content = preg_replace_callback(
		'/(<style\b[^>]*>)(.*?)(<\/style>)/is',
		static function ($m) {
			return $m[1] . preg_replace('/<br\s*\/?>/i', '', $m[2]) . $m[3];
		},
		$content
	);
	$content = preg_replace_callback(
		'/(<svg\b[^>]*>)(.*?)(<\/svg>)/is',
		static function ($m) {
			$svg = preg_replace('/<br\s*\/?>/i', '', $m[2]);
			$svg = preg_replace('/<\/?p\b[^>]*>/i', '', $svg);
			return $m[1] . $svg . $m[3];
		},
		$content
	);
	return $content;
}, 20);
