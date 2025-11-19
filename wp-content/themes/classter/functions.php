<?php
 
 

/**
 * Theme functions and definitions
 */

use function PHPSTORM_META\type;

// Framework directory path from theme root
if (!defined('THEMEREX_THEME_PATH'))	define('THEMEREX_THEME_PATH',	trailingslashit(get_template_directory()));

/* ========================= Theme setup section ============================== */

require_once 'notifications_functions.php';

add_action('after_setup_theme', 'themerex_theme_setup');
if (!function_exists('themerex_theme_setup')) {
	function themerex_theme_setup()
	{

		// Set the content width based on the theme's design and stylesheet.
		if (!isset($content_width))
			$content_width = 1170; /* pixels */

		// Theme image dimensions
		// 16:9
		add_image_size('fullpost', 1170, 647, true);
		add_image_size('excerpt',   760, 420, true);
		add_image_size('classic3',  400, 280, true);
		add_image_size('classic4',  350, 280, true);
		// Non 16:9
		add_image_size('portfolio3', 714, 540, true);
		add_image_size('portfolio4', 550, 400, true);
		add_image_size('widgets',    75,  75, true);

		// Filters wp_title / version > 4.1
		add_theme_support('title-tag');

		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');

		// Enable support for Post Thumbnails
		add_theme_support('post-thumbnails');

		// Custom header setup
		add_theme_support('custom-header', array('header-text' => false));

		// Custom backgrounds setup
		add_theme_support('custom-background');

		// Supported posts formats
		add_theme_support('post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat'));

		// Add user menu
		add_theme_support('nav-menus');
		if (function_exists('register_nav_menus')) {
			register_nav_menus(
				array(
					'mainmenu' => 'Main Menu',
					'sidemenu' => 'Side Menu',
					'panelmenu' => 'Panel Menu'
				)
			);
		}

		// WooCommerce Support
		add_theme_support('woocommerce');

		// Editor custom stylesheet - for user
		add_editor_style('css/editor-style.css');

		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain('themerex', (is_dir(get_stylesheet_directory() . '/languages') ? get_stylesheet_directory() : get_template_directory()) . '/languages');

		// ---------------------------------- Actions and filters ------------------------------
		// Register theme widgets
		add_action('widgets_init', 'themerex_widgets_init');

		// Frontend actions:

		// Enqueue scripts and styles
		add_action('wp_enqueue_scripts', 'themerex_scripts');
		// PRE QUERY - posts per page selector
		add_action('pre_get_posts', 'themerex_posts_per_page_selector');
		// Filter categories list - exclude unwanted cats from widget output
		add_action('widget_categories_args', 'themerex_categories_args_filter');
		add_action('widget_categories_dropdown_args', 'themerex_categories_args_filter');
		add_action('widget_posts_args', 'themerex_posts_args_filter');
		// AJAX: Save e-mail in subscribe list
		add_action('wp_ajax_emailer_submit', 'themerex_callback_emailer_submit');
		add_action('wp_ajax_nopriv_emailer_submit', 'themerex_callback_emailer_submit');
		// AJAX: Confirm e-mail in subscribe list
		add_action('wp_ajax_emailer_confirm', 'themerex_callback_emailer_confirm');
		add_action('wp_ajax_nopriv_emailer_confirm', 'themerex_callback_emailer_confirm');
		// AJAX: Get subscribers list if group changed
		add_action('wp_ajax_emailer_group_getlist', 'themerex_callback_emailer_group_getlist');
		add_action('wp_ajax_nopriv_emailer_group_getlist', 'themerex_callback_emailer_group_getlist');
		// AJAX: Set post likes/views count
		add_action('wp_ajax_post_counter', 'themerex_callback_post_counter');
		add_action('wp_ajax_nopriv_post_counter', 'themerex_callback_post_counter');
		// AJAX: Get attachment url
		add_action('wp_ajax_get_attachment_url', 'themerex_callback_get_attachment_url');
		add_action('wp_ajax_nopriv_get_attachment_url', 'themerex_callback_get_attachment_url');
		// AJAX: Send contact form data
		add_action('wp_ajax_send_contact_form', 'themerex_callback_send_contact_form');
		add_action('wp_ajax_nopriv_send_contact_form', 'themerex_callback_send_contact_form');
		// AJAX: Get next page on blog streampage
		add_action('wp_ajax_view_more_posts', 'themerex_callback_view_more_posts');
		add_action('wp_ajax_nopriv_view_more_posts', 'themerex_callback_view_more_posts');
		// AJAX: Incremental search
		add_action('wp_ajax_ajax_search', 'themerex_callback_ajax_search');
		add_action('wp_ajax_nopriv_ajax_search', 'themerex_callback_ajax_search');
		// AJAX: Change month in the calendar widget
		add_action('wp_ajax_calendar_change_month', 'themerex_callback_calendar_change_month');
		add_action('wp_ajax_nopriv_calendar_change_month', 'themerex_callback_calendar_change_month');
		// Frontend editor: Save post data
		add_action('wp_ajax_frontend_editor_save', 'themerex_callback_frontend_editor_save');
		add_action('wp_ajax_nopriv_frontend_editor_save', 'themerex_callback_frontend_editor_save');
		// Frontend editor: Delete post
		add_action('wp_ajax_frontend_editor_delete', 'themerex_callback_frontend_editor_delete');
		add_action('wp_ajax_nopriv_frontend_editor_delete', 'themerex_callback_frontend_editor_delete');
		// Add og:image meta tag for facebook
		add_action('wp_head', 'themerex_facebook_og_tags', 5);



		// Frontend filters:

		// PRE_QUERY: - add filter to main query
		add_filter('posts_where', 'themerex_filter_where');
		// Substitute audio, video and galleries in widget text
		add_filter('widget_text', 'themerex_widget_text_filter');
		// Get theme calendar
		add_filter('get_calendar', 'themerex_get_calendar_filter');
		// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link
		add_filter('wp_page_menu_args', 'themerex_page_menu_args');
		// Adds custom classes to the array of body classes
		add_filter('body_class', 'themerex_body_classes');
		// Filters wp_title to print a neat <title> tag based on what is being viewed
		if (floatval(get_bloginfo('version')) < "4.1") {
			add_filter('wp_title', 'themerex_wp_title', 10, 2);
		}
		// Add main menu classes
		//add_filter('wp_nav_menu_objects', 'themerex_nav_menu_classes', 10, 2);
		// Add class "widget-number-#' for each widget
		add_filter('dynamic_sidebar_params', 'themerex_add_widget_number', 10, 1);
		// Add theme-specific vars to post_data
		add_filter('themerex_get_post_data', 'themerex_get_post_data_for_theme', 10, 3);
		// Enable/disable shortcodes in excerpt
		add_filter('the_excerpt', 'sc_excerpt_shortcodes');


		// Admin actions:
		// Enqueue scripts and styles
		add_action('admin_enqueue_scripts', 'themerex_admin_scripts');
		// Add categories (taxonomies) filter for custom posts types
		add_action('restrict_manage_posts', 'themerex_admin_taxonomy_filter');
		// Add Theme Options in menu Appearance
		add_action('admin_menu', 'themerex_options_admin_menu_item');
		// Init TGM Activation Plugin
		add_action('tgmpa_register', 'themerex_admin_register_plugins');
		// Clear taxonomies cache when save or delete post or category
		add_action('save_post', 'themerex_clear_all_terms_filters', 10, 2);
		add_action('delete_post', 'themerex_clear_all_terms_filters', 10, 2);
		add_action('edit_category', 'themerex_clear_categories_filters',   10, 1);
		add_action('create_category', 'themerex_clear_categories_filters', 10, 1);
		add_action('delete_category', 'themerex_clear_categories_filters', 10, 1);

		// Prepare shortcodes in the content
		if (function_exists('sc_prepare_content')) sc_prepare_content();

		// Theme filters:
		// Prepare logo text
		add_filter('theme_logo_text', 'themerex_logo_text', 10, 2);
	}
}
-
require get_template_directory() . '/shortcode_bids.php';

// wp_enqueue_script( 'recaptchav3', 'https://www.recaptcha.net/recaptcha/api.js?render=6Le3tzAmAAAAAMfKu6IVM6vdbwh56e0aeSHnipzi');

/**
 * Register widgetized area and update sidebar with default widgets
 */
if (!function_exists('themerex_widgets_init')) {
	function themerex_widgets_init($widgets = array())
	{
		if (function_exists('register_sidebar')) {
			if (!is_array($widgets) || count($widgets) == 0) {
				$widgets = array();
				$widgets['sidebar-main']   = __('Main Sidebar', 'themerex');
				$widgets['sidebar-footer'] = __('Footer Sidebar', 'themerex');
				if (function_exists('is_woocommerce')) {
					$widgets['sidebar-cart']  = __('WooCommerce Cart Sidebar', 'themerex');
				}
				// Custom sidebars
				$sidebars = get_theme_option('custom_sidebars');
				if (is_array($sidebars) && count($sidebars) > 0) {
					foreach ($sidebars as $i => $sb) {
						if (trim(chop($sb)) == '') continue;
						$widgets['custom-sidebar-' . $i]  = $sb;
					}
				}
			}
			if (count($widgets) > 0) {
				foreach ($widgets as $id => $name) {
					register_sidebar(array(
						'name'          => $name,
						'id'            => $id,
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h5 class="title">',
						'after_title'   => '</h5>',
					));
				}
			}
		}
	}
}

/**
 * Enqueue scripts and styles
 */
if (!function_exists('themerex_scripts')) {
	function themerex_scripts()
	{
		global $wp_styles, $concatenate_scripts;
		$concatenate_scripts = false;

		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		$fonts = getThemeFontsList(false);
		$theme_fonts = array();
		// Prepare custom fonts
		if (get_custom_option('typography_custom') == 'yes') {
			$selectors = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
			foreach ($selectors as $s) {
				$font = get_custom_option('typography_' . $s . '_font');
				if (!empty($font)) $theme_fonts[$font] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('theme_skin_use_fonts', $theme_fonts);
		// Link to selected fonts
		foreach ($theme_fonts as $font => $v) {
			if (isset($fonts[$font])) {
				$font_name = ($pos = themerex_strpos($font, ' (')) !== false ? themerex_substr($font, 0, $pos) : $font;
				$css = !empty($fonts[$font]['css'])
					? $fonts[$font]['css']
					: themerex_get_protocol() . '://fonts.googleapis.com/css?family=' . (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name) . ':100,100italic,300,300italic,400,400italic,600,600italic,700,700italic') . '&subset=latin,latin-ext,cyrillic,cyrillic-ext';
				themerex_enqueue_style('theme-font-' . str_replace(' ', '-', $font_name), $css, array(), null);
			}
		}
		// Fontello styles must be loaded before main stylesheet
		themerex_enqueue_style('fontello',  themerex_get_file_url('/css/fontello/css/fontello.css'),  array(), null);
		themerex_enqueue_style('animation', themerex_get_file_url('/css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		themerex_enqueue_style('main-style', get_stylesheet_uri(), array(), null);

		// WooCommerce customizer
		if (function_exists('is_woocommerce')) {
			themerex_enqueue_style('woo-style',  themerex_get_file_url('/css/woo-style.css'), array('main-style'), null);
		}
		// Tribe Events
		if (class_exists('TribeEvents')) {
			$wp_styles->done[] = 'tribe-events-custom-jquery-styles';
			themerex_enqueue_style('tribe-style',  themerex_get_file_url('/css/tribe-style.css'), array('main-style'), null);
		}
		// BuddyPress customizer
		if (class_exists('BuddyPress')) {
			themerex_enqueue_style('buddy-style',  themerex_get_file_url('/css/buddy-style.css'), array('main-style'), null);
		}
		// BB Press customizer
		if (class_exists('bbPress')) {
			themerex_enqueue_style('bbpress-style',  themerex_get_file_url('/css/bbpress-style.css'), array('main-style'), null);
		}
		// Messages
		themerex_enqueue_style('messages-style', themerex_get_file_url('/js/messages/_messages.css'), array('main-style'), null);
		// Shortcodes
		themerex_enqueue_style('shortcodes',  themerex_get_file_url('/shortcodes/shortcodes.css'), array('main-style'), null);
		// Main slider
		if (get_custom_option('slider_show') == 'yes') {
			themerex_enqueue_style('swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null);
			themerex_enqueue_style('swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null);
			themerex_enqueue_style('main-slider-style',  themerex_get_file_url('/css/slider-style.css'), array(), null);
			themerex_enqueue_script('swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true);
			themerex_enqueue_script('swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true);
			themerex_enqueue_script('flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true);
		}

		// Theme skin stylesheet
		do_action('theme_skin_add_stylesheets');

		// Custom fonts and colors
		if (get_custom_option('theme_skin') != '')
			wp_add_inline_style('theme-skin', prepareThemeCustomStyles());
		else
			wp_add_inline_style('shortcodes', prepareThemeCustomStyles());

		// Responsive
		if (get_theme_option('responsive_layouts') == 'yes') {
			themerex_enqueue_style('responsive',  themerex_get_file_url('/css/responsive.css'), array('main-style'), null);
			do_action('theme_skin_add_responsive');
			if (get_custom_option('theme_skin') != '') {
				$css = apply_filters('theme_skin_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style('responsive', $css);
			}
		}


		// Enqueue scripts
		//----------------------------------------------------------------------------------------------------------------------------
		themerex_enqueue_script('jquery', false, array(), null, true);
		themerex_enqueue_script('jquery-ui-core', false, array(), null, true);
		themerex_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		themerex_enqueue_script('jquery-effects-core', false, array(), null, true);
		themerex_enqueue_script('jquery-effects-fade', false, array('jquery', 'jquery-effects-core'), null, true);

		// Load separate theme scripts
		themerex_enqueue_script('jquery-cookie', themerex_get_file_url('/js/jquery.cookie.js'), array('jquery'), null, true);
		themerex_enqueue_script('jquery-easing', themerex_get_file_url('/js/jquery.easing.js'), array('jquery'), null, true);

		themerex_enqueue_script('superfish', themerex_get_file_url('/js/superfish.min.js'), array('jquery'), null, true);

		//		themerex_enqueue_script( 'formstyler', themerex_get_file_url('/js/form_styler/jquery.formstyler.js'),  array(), null, true );

		themerex_enqueue_script('messages', themerex_get_file_url('/js/messages/_messages.js'),  array(), null, true);

		themerex_enqueue_script('smooth-scroll', themerex_get_file_url('/js/SmoothScroll.min.js'), array('jquery'), null, true);

		themerex_enqueue_script('shortcodes-init', themerex_get_file_url('/shortcodes/shortcodes_init.js'), array(), null, true);

		themerex_enqueue_script('_utils', themerex_get_file_url('/js/_utils.js'), array(), null, true);
		themerex_enqueue_script('_front', themerex_get_file_url('/js/_front.js'), array(), null, true);

		// Chop slider
		if (file_exists(themerex_get_file_dir('/js/chopslider/jquery.id.chopslider-2.0.0.free.min.js'))) {
			themerex_enqueue_script('chopslider', themerex_get_file_url('/js/chopslider/jquery.id.chopslider-2.0.0.free.min.js'), array('jquery'), null, true);
			themerex_enqueue_script('cstransitions', themerex_get_file_url('/js/chopslider/jquery.id.cstransitions-1.0.min.js'), array('jquery'), null, true);
		}

		// Video background
		if (get_custom_option('show_video_bg') == 'yes') {
			// Media elements library
			if (get_theme_option('use_mediaelement') == 'yes') {
				if (floatval(get_bloginfo('version')) < "3.6") {
					themerex_enqueue_style('mediaplayer-style',  themerex_get_file_url('/js/mediaplayer/mediaplayer.css'), array(), null);
					themerex_enqueue_script('mediaplayer', themerex_get_file_url('/js/mediaplayer/mediaelement.min.js'), array(), null, true);
				} else {
					wp_enqueue_style('mediaelement');
					wp_enqueue_style('wp-mediaelement');
					wp_enqueue_script('mediaelement');
					wp_enqueue_script('wp-mediaelement');
				}
			} else {
				global $wp_scripts;
				$wp_scripts->done[] = 'mediaelement';
				$wp_scripts->done[] = 'wp-mediaelement';
				$wp_styles->done[] = 'mediaelement';
				$wp_styles->done[] = 'wp-mediaelement';
			}
		}

		if (get_custom_option('show_video_bg') == 'yes' && get_custom_option('video_bg_youtube_code') != '') {
			themerex_enqueue_script('video-bg', themerex_get_file_url('/js/jquery.tubular.1.0.js'), array('jquery'), null, true);
		}

		// Google map

		// IDS Code Start.

		// Load maps JS scripts only on the "Contact Us" page.

		$current_page_id = get_the_ID();

		if ($current_page_id == 2512 || $current_page_id == 8219) {
			if (get_custom_option('googlemap_show') == 'yes') {
				$api_key = get_theme_option('api_google');
				themerex_enqueue_script('googlemap', themerex_get_protocol() . '://maps.google.com/maps/api/js' . ($api_key ? '?key=' . $api_key : ''), array(), null, true);
				themerex_enqueue_script('googlemap_init', themerex_get_file_url('/js/_googlemap_init.js'), array(), null, true);
			}
		}

		// IDS Code End.

		// Theme skin script
		do_action('theme_skin_add_scripts');
	}
}

// Admin side setup
if (is_admin()) {
	if (!function_exists('themerex_admin_scripts')) {
		function themerex_admin_scripts()
		{
			themerex_enqueue_script('jquery', false, array(), null, true);
			themerex_enqueue_script('jquery-ui-core', false, array('jquery'), null, true);
			themerex_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
			themerex_enqueue_script('jquery-cookie', themerex_get_file_url('/js/jquery.cookie.js'), array('jquery'), null, true);

			themerex_enqueue_style('wp-color-picker', array(), null);
			themerex_enqueue_script('wp-color-picker', false, array(), null, true);

			themerex_enqueue_style('theme-admin-style',  themerex_get_file_url('/css/admin-style.css'), array(), null);
			themerex_enqueue_style('fontello-admin', themerex_get_file_url('/admin/css/fontello/css/fontello-admin.css'), array(), null);
			themerex_enqueue_style('fontello', themerex_get_file_url('/css/fontello/css/fontello.css'), array(), null);

			themerex_enqueue_script('_utils',   themerex_get_file_url('/js/_utils.js'), array(), null, true);
			themerex_enqueue_script('_admin',   themerex_get_file_url('/js/_admin.js'), array('jquery'), null, true);
		}
	}

	// Add categories (taxonomies) filter for custom posts types
	if (!function_exists('themerex_admin_taxonomy_filter')) {
		function themerex_admin_taxonomy_filter()
		{
			if (get_theme_option('admin_add_filters') != 'yes') return;
			$page = get_query_var('post_type');
			if ($page == 'post')
				$taxes = array('post_format', 'post_tag');
			else if ($page == 'attachment')
				$taxes = array('media_folder');
			else
				return;
			echo getTermsFilters($taxes);
		}
	}

	// Register optional plugins
	if (!function_exists('themerex_admin_register_plugins')) {
		function themerex_admin_register_plugins()
		{

			$plugins = array(
				array(
					'name' 		=> 'ThemeREX Utilities',
					'slug' 		=> 'trx_utils',
					'source'	=> themerex_get_file_dir('/plugins/trx_utils.zip'),
					'required' 	=> true
				),
				array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				),
				array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> themerex_get_file_dir('/plugins/js_composer.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> themerex_get_file_dir('/plugins/revslider.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'source'	=> themerex_get_file_dir('/plugins/wp-instagram-widget.zip'),
					'required' 	=> false
				)
			);
			$theme_text_domain = 'themerex';
			$config = array(
				'domain'			=> $theme_text_domain,			// Text domain - likely want to be the same as your theme.
				'default_path'		=> '',							// Default absolute path to pre-packaged plugins
				//'parent_menu_slug'	=> 'themes.php',				// Default parent menu slug
				//'parent_url_slug'	=> 'themes.php',				// Default parent URL slug
				'menu'				=> 'install-required-plugins',	// Menu slug
				'has_notices'		=> true,						// Show admin notices or not
				'is_automatic'		=> true,						// Automatically activate plugins after installation or not
				'message'			=> '',							// Message to output right before the plugins table
				'strings'			=> array(
					'page_title'						=> __('Install Required Plugins', 'themerex'),
					'menu_title'						=> __('Install Plugins', 'themerex'),
					'installing'						=> __('Installing Plugin: %s', 'themerex'), // %1$s = plugin name
					'oops'								=> __('Something went wrong with the plugin API.', 'themerex'),
					'notice_can_install_required'		=> _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'), // %1$s = plugin name(s)
					'notice_can_install_recommended'	=> _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s)
					'notice_cannot_install'				=> _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s)
					'notice_can_activate_required'		=> _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
					'notice_can_activate_recommended'	=> _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
					'notice_cannot_activate'			=> _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s)
					'notice_ask_to_update'				=> _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s)
					'notice_cannot_update'				=> _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s)
					'install_link'						=> _n_noop('Begin installing plugin', 'Begin installing plugins'),
					'activate_link'						=> _n_noop('Activate installed plugin', 'Activate installed plugins'),
					'return'							=> __('Return to Required Plugins Installer', 'themerex'),
					'plugin_activated'					=> __('Plugin activated successfully.', 'themerex'),
					'complete'							=> __('All plugins installed and activated successfully. %s', 'themerex'), // %1$s = dashboard link
					'nag_type'							=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
				)
			);

			tgmpa($plugins, $config);
		}
	}
}



// Init theme template - prepare global variables
function themerex_init_template()
{
	// AJAX Queries settings
	global $THEMEREX_ajax_nonce, $THEMEREX_ajax_url;
	$THEMEREX_ajax_nonce = wp_create_nonce('ajax_nonce');
	$THEMEREX_ajax_url = admin_url('admin-ajax.php');

	// Set theme params from GET
	if (isset($_GET['set']) && $_GET['set'] == 1) {
		foreach ($_GET as $k => $v) {
			if (get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop
	load_custom_options();

	// Reject old browsers support
	global $THEMEREX_jreject;
	$THEMEREX_jreject = false;
	if (!isset($_COOKIE['jreject'])) {
		themerex_enqueue_style('jquery_reject-style',  themerex_get_file_url('/js/jreject/css/jquery.reject.css'), array(), null);
		themerex_enqueue_script('jquery_reject', themerex_get_file_url('/js/jreject/jquery.reject.js'), array('jquery'), null, true);
		setcookie('jreject', 1, 0, '/');
		$THEMEREX_jreject = true;
	}

	// Main menu
	global $THEMEREX_mainmenu;
	if (get_custom_option('show_top_panel') != 'hide') {
		$menu_slug = get_custom_option('menu_main');
		$args = array(
			'menu'              => empty($menu_slug) || $menu_slug == 'default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'mainmenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'mainmenu'
		);
		if (get_theme_option('custom_menu') == 'yes' && class_exists('themerex_walker')) {
			$args['walker'] = new themerex_walker;
		}
		$THEMEREX_mainmenu = wp_nav_menu($args);
	} else
		$THEMEREX_mainmenu = '';

	// Side menu
	global $THEMEREX_sidemenu;
	if (get_custom_option('show_left_panel') == 'yes') {
		$menu_slug = get_custom_option('menu_side');
		$THEMEREX_sidemenu = wp_nav_menu(array(
			'menu'              => empty($menu_slug) || $menu_slug == 'default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'sidemenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'sidemenu'
		));
	} else
		$THEMEREX_sidemenu = '';

	// Panel menu
	global $THEMEREX_panelmenu;
	if (get_custom_option('show_right_panel') == 'yes') {
		$menu_slug = get_custom_option('menu_panel');
		$THEMEREX_panelmenu = wp_nav_menu(array(
			'menu'              => empty($menu_slug) || $menu_slug == 'default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'panelmenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'panelmenu'
		));
	} else
		$THEMEREX_panelmenu = '';

	// Include current skin
	$skin = themerex_escape_shell_cmd(get_custom_option('theme_skin'));
	if (file_exists(themerex_get_file_dir('/skins/' . $skin . '/' . $skin . '.php'))) {
		require_once(themerex_get_file_dir('/skins/' . $skin . '/' . $skin . '.php'));
	}

	// Logo image and icon from skin
	global $logo_text, $logo_image, $logo_side, $logo_fixed, $logo_footer, $bg_custom_image_style, $bg_custom_image_style_header;
	$logo_text = get_custom_option('logo_text');
	$menu_align = get_custom_option('menu_align');

	if (get_custom_option('bg_custom_image_style') == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/background.jpg')))
		$bg_custom_image_style = themerex_get_file_url('/skins/' . $skin . '/images/background.jpg');
	else if (get_custom_option('bg_custom_image_style') != '')
		$bg_custom_image_style = get_custom_option('bg_custom_image_style');

	$bg_custom_image_style_header = '';
	if (get_custom_option('bg_custom_image_style_header') != '')
		$bg_custom_image_style_header = get_custom_option('bg_custom_image_style_header');

	if ($menu_align == 'left' || $menu_align == 'center') {
		if (($logo_image = get_custom_option('logo_top')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-top.png')))
			$logo_image = themerex_get_file_url('/skins/' . $skin . '/images/logo-top.png');
	}

	if ($logo_image == '' && ($logo_image = get_custom_option('logo_image')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo.png')))
		$logo_image = themerex_get_file_url('/skins/' . $skin . '/images/logo.png');

	if (($logo_side = get_custom_option('logo_side')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-side.png')))
		$logo_side = themerex_get_file_url('/skins/' . $skin . '/images/logo-side.png');
	if ($logo_side == '') $logo_side = $logo_image;

	if (($logo_fixed = get_custom_option('logo_fixed')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-fixed.png')))
		$logo_fixed = themerex_get_file_url('/skins/' . $skin . '/images/logo-fixed.png');
	if ($logo_fixed == '') $logo_fixed = $logo_image;

	if (($logo_footer = get_custom_option('logo_image_footer')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-footer.png')))
		$logo_footer = themerex_get_file_url('/skins/' . $skin . '/images/logo-footer.png');
	if ($logo_footer == '') $logo_footer = $logo_image;
}

if (!function_exists('themerex_get_nav_menu')) {
	function themerex_get_nav_menu($slug = '', $custom_walker = false, $mobile = '')
	{
		$menu = !empty($slug) ? get_custom_option($slug) : '';
		$menu = empty($menu) || $menu == 'default' || is_inherit_option($menu) ? '' : $menu;
		$list = themerex_get_theme_option('use_menu_cache') == 'yes' ? get_transient('themerex_menu') : array();
		if (!is_array($list)) $list = array();
		$html = '';
		if (!empty($slug) && !empty($list[trim($slug) . '_' . trim($menu)])) {
			$html = $list[trim($slug) . '_' . trim($menu)];
		}
		if (empty($html)) {
			$args = array(
				'menu'				=> $menu,
				'container'			=> '',
				'container_class'	=> '',
				'container_id'		=> '',
				'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'menu_class'		=> (!empty($slug) ? $slug : 'menu_main') . '_nav',
				'menu_id'  			=> (!empty($slug) ? $slug . ($mobile == 'mobile' ? '_mobile' : '') : ($mobile == 'mobile' ? 'menu_main_mobile' : 'menu_main')),
				'echo'				=> false,
				'fallback_cb'		=> '',
				'before'			=> '',
				'after'				=> '',
				'link_before'       => '',
				'link_after'        => '',
				'depth'             => 11
			);
			if (!empty($slug))
				$args['theme_location'] = $slug;
			if ($custom_walker && class_exists('themerex_custom_menu_walker') && themerex_get_theme_option('custom_menu') == 'yes')
				$args['walker'] = new themerex_walker;
			$html = wp_nav_menu(apply_filters('themerex_filter_get_nav_menu', $args));
			if (!empty($slug) && themerex_get_theme_option('use_menu_cache') == 'yes') {
				$list[trim($slug) . '_' . trim($menu)] = $html;
				set_transient('themerex_menu', $list, 24 * 60 * 60);
			}
		}
		return $html;
	}
}

/* ========================= Filters and action handlers ============================== */

// Add facebook meta tags for post/page sharing
function themerex_facebook_og_tags()
{
	global $post;
	if (!is_singular()) return;
	if (has_post_thumbnail($post->ID)) {
		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		echo '<meta property="og:image" content="' . esc_attr($thumbnail_src[0]) . '"/>' . "\n";
	}
	echo '<meta property="og:title" content="' . esc_attr(strip_tags(get_the_title())) . '" />' . "\n"
		. '<meta property="og:description" content="' . esc_attr(strip_tags(strip_shortcodes(get_the_excerpt()))) . '" />' . "\n"
		. '<meta property="og:url" content="' . esc_attr(get_permalink()) . '" />';
}

// PRE_QUERY - add filter to main query
if (!function_exists('themerex_filter_where')) {
	function themerex_filter_where($where = '')
	{
		global $wpdb, $wp_query;
		if (is_admin() || $wp_query->is_attachment()) return $where;
		if (themerex_strpos($where, 'post_status') === false && (!isset($_REQUEST['preview']) || $_REQUEST['preview'] != 'true') && (!isset($_REQUEST['vc_editable']) || $_REQUEST['vc_editable'] != 'true')) {
			$prv = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? " OR " . esc_sql($wpdb->posts) . ".post_status='private'" : '';
			$where .= " AND " . (!empty($prv) ? '(' : '') . "" . esc_sql($wpdb->posts) . ".post_status='publish'" . (!empty($prv) ? $prv : '') . (!empty($prv) ? ')' : '');
		}
		return $where;
	}
}

// PRE QUERY - posts per page selector
if (!function_exists('themerex_posts_per_page_selector')) {
	function themerex_posts_per_page_selector($query)
	{
		if (is_admin() || !$query->is_main_query()) return;
		$orderby_set = true;
		$orderby = get_theme_option('blog_sort');
		$order = get_theme_option('blog_order');
		// Set posts per page
		$ppp = (int) get_theme_option('posts_per_page');
		$ppp2 = 0;
		if ($query->is_category()) {
			$cat = (int) $query->get('cat');
			if (empty($cat))
				$cat = $query->get('category_name');
			if (!empty($cat)) {
				//$ppp2 = (int) get_category_inherited_property($cat, 'posts_per_page', 0);
				$cat_options = get_category_inherited_properties($cat);
				if (isset($cat_options['posts_per_page']) && !empty($cat_options['posts_per_page']) && !is_inherit_option($cat_options['posts_per_page'])) $ppp2 = (int) $cat_options['posts_per_page'];
				if (isset($cat_options['blog_sort']) && !empty($cat_options['blog_sort']) && !is_inherit_option($cat_options['blog_sort'])) $orderby = $cat_options['blog_sort'];
				if (isset($cat_options['blog_order']) && !empty($cat_options['blog_order']) && !is_inherit_option($cat_options['blog_order'])) $order = $cat_options['blog_order'];
			}
		} else {
			if ($query->get('post_type') == 'product' || $query->get('product_cat') != '' || $query->get('product_tag') != '') {
				$orderby_set = false;
				$page_id = get_option('woocommerce_shop_page_id');
			} else if ($query->is_archive()) {
				$page_id = getTemplatePageId('archive');
			} else if ($query->is_search()) {
				$page_id = getTemplatePageId('search');
				if (get_theme_option('use_ajax_search') == 'yes') {
					$show_types = get_theme_option('ajax_search_types');
					if (!empty($show_types))
						$query->set('post_type', explode(',', $show_types));
				}
			} else if ($query->is_posts_page == 1) {
				$page_id = isset($query->queried_object_id) ? $query->queried_object_id : getTemplatePageId('blog');
			} else {
				$page_id = 0;
			}
			if ($page_id > 0) {
				$post_options = get_post_meta($page_id, 'post_custom_options', true);
				if (isset($post_options['posts_per_page']) && !empty($post_options['posts_per_page']) && !is_inherit_option($post_options['posts_per_page'])) $ppp2 = (int) $post_options['posts_per_page'];
				if ($orderby_set) {
					if (isset($post_options['blog_sort']) && !empty($post_options['blog_sort']) && !is_inherit_option($post_options['blog_sort'])) $orderby = $post_options['blog_sort'];
					if (isset($post_options['blog_order']) && !empty($post_options['blog_order']) && !is_inherit_option($post_options['blog_order'])) $order = $post_options['blog_order'];
				}

				if ($query->get('post_type') == 'product' || $query->get('product_cat') != '' || $query->get('product_tag') != '') {
					$cols = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() : 2;
					$rows = function_exists('wc_get_default_product_rows_per_page') ? wc_get_default_product_rows_per_page() : 4;
					$ppp =  $query->get('posts_per_page') ? $query->get('posts_per_page') : apply_filters('loop_shop_per_page', $cols * $rows);
				}
			}
		}
		if ($ppp2 > 0)	$ppp = $ppp2;
		if ($ppp > 0) 	$query->set('posts_per_page', $ppp);
		if ($orderby_set) addSortOrderInQuery($query, $orderby, $order);
		// Exclude categories
		$ex = get_theme_option('exclude_cats');
		if (!empty($ex))
			$query->set('category__not_in', explode(',', $ex));
	}
}

// Filter categories list - exclude unwanted cats from widget output
if (!function_exists('themerex_categories_args_filter')) {
	function themerex_categories_args_filter($args)
	{
		if (!is_admin()) {
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex))
				$args['exclude'] = $ex;
		}
		return $args;
	}
}
if (!function_exists('themerex_posts_args_filter')) {
	function themerex_posts_args_filter($args)
	{
		if (!is_admin()) {
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex)) {
				$args['category__not_in'] = explode(',', $ex);
			}
		}
		return $args;
	}
}

// Substitute audio, video and galleries in widget text
if (!function_exists('themerex_widget_text_filter')) {
	function themerex_widget_text_filter($text)
	{
		return substituteAll($text);
	}
}

// Get theme calendar
if (!function_exists('themerex_get_calendar_filter')) {
	function themerex_get_calendar_filter($text)
	{
		return getThemeRexCalendar($text);
	}
}

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
if (!function_exists('themerex_page_menu_args')) {
	function themerex_page_menu_args($args)
	{
		$args['show_home'] = true;
		return $args;
	}
}

// Adds custom classes to the array of body classes.
if (!function_exists('themerex_body_classes')) {
	function themerex_body_classes($classes)
	{
		// Adds a class of group-blog to blogs with more than 1 published author
		if (is_multi_author()) {
			$classes[] = 'group-blog';
		}

		return $classes;
	}
}

// Filters wp_title to print a neat <title> tag based on what is being viewed.
if (!function_exists('themerex_wp_title')) {
	function themerex_wp_title($title, $sep)
	{
		global $page, $paged;
		if (is_feed()) return $title;
		// Add the blog name
		$title .= get_bloginfo('name');
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo('description', 'display');
		if ($site_description && (is_home() || is_front_page()))
			$title .= " $sep $site_description";
		// Add a page number if necessary:
		if ($paged >= 2 || $page >= 2)
			$title .= " $sep " . sprintf(__('Page %s', 'themerex'), max($paged, $page));
		return $title;
	}
}

// Add main menu classes
if (!function_exists('themerex_nav_menu_classes')) {
	function themerex_nav_menu_classes($items, $args)
	{
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu') {
			foreach ($items as $k => $item) {
				if ($item->menu_item_parent == 0) {
					if ($item->type == 'taxonomy' && $item->object == 'category') {
						$cur_theme = get_category_inherited_property($item->object_id, 'blog_theme');
						if (!empty($cur_theme) && !is_inherit_option($cur_theme))
							$items[$k]->classes[] = 'theme_' . $cur_theme;
					}
				}
			}
		}
		return $items;
	}
}

// Add theme-specific vars to post_data
if (!function_exists('themerex_get_post_data_for_theme')) {
	function themerex_get_post_data_for_theme($post_data, $opt, $post_obj)
	{
		$post_data['post_accent_color'] = $opt['parent_cat_id'] > 0 ? (empty($opt['accent_color']) ? get_category_inherited_property($opt['parent_cat_id'], 'theme_accent_color') : $opt['accent_color']) : '';
		if ($post_data['post_accent_color'] == '') {
			$ex_cats = explode(',', get_theme_option('exclude_cats'));
			for ($i = 0; $i < count($post_data['post_categories_list']); $i++) {
				if (in_array($post_data['post_categories_list'][$i]['term_id'], $ex_cats)) continue;
				if (get_theme_option('close_category') == 'parental') {
					$parent_cat = getParentCategory($post_data['post_categories_list'][$i]['term_id'], $opt['parent_cat_id']);
					if ($parent_cat) {
						$post_data['post_accent_color'] = get_category_inherited_property($parent_cat['term_id'], 'theme_accent_color');
					}
				} else {
					$post_data['post_accent_color'] = get_category_inherited_property($post_data['post_categories_list'][$i]['term_id'], 'theme_accent_color');
				}
				if ($post_data['post_accent_color'] != '') break;
			}
		}
		return $post_data;
	}
}

// Add class "widget-number-#' for each widget
if (!function_exists('themerex_add_widget_number')) {
	function themerex_add_widget_number($prm)
	{
		if (is_admin()) return $prm;
		static $num = 0, $last_sidebar = '', $last_sidebar_count = 0, $sidebars_widgets = array();
		global $THEMEREX_CURRENT_SIDEBAR;
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $THEMEREX_CURRENT_SIDEBAR) {
			$num = 0;
			$last_sidebar = $THEMEREX_CURRENT_SIDEBAR;
			$last_sidebar_count = count($sidebars_widgets[$prm[0]['id']]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget-number-' . $num . ' ', $prm[0]['before_widget']);
		if ($last_sidebar_count > $num && $last_sidebar == 'main') {
			$prm[0]['before_widget'] = str_replace(' class="', ' class=" ', $prm[0]['before_widget']);
		}
		if ($last_sidebar == 'top') {
			$prm[0]['before_widget'] = str_replace(' class="', ' class="widgetTop ' . ($num > 1 ? 'clr ' : ''), $prm[0]['before_widget']);
			$prm[0]['before_title'] = str_replace(' class="', ' class="titleHide ', $prm[0]['before_title']);
		} else {
			$prm[0]['before_widget'] = str_replace(' class="', ' class="widgetWrap ', $prm[0]['before_widget']);
			if ($last_sidebar == 'footer') {
				$columns = max(1, min(4, $last_sidebar_count));
				$prm[0]['before_widget'] = str_replace(' class="', ' class="columns1_' . $columns . ' ', $prm[0]['before_widget']);
				if ($num == 1)
					$prm[0]['before_widget'] = '<div class="columnsWrap">' . $prm[0]['before_widget'];
				if ($last_sidebar_count == $num)
					$prm[0]['after_widget'] .= '</div>';
				else if ($num % $columns == 0) {
					$prm[0]['after_widget'] .= '</div><div class="columnsWrap">';
				}
			}
		}
		return $prm;
	}
}

// Prepare logo text
if (!function_exists('themerex_logo_text')) {
	function themerex_logo_text($text, $where)
	{
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}




/* ========================= AJAX queries handlers ============================== */

// Save e-mail in subscribe list
if (!function_exists('themerex_callback_emailer_submit')) {
	function themerex_callback_emailer_submit()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '');

		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];

		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = themerex_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email]))
				$response['error'] = __('E-mail address already in the subscribers list!', 'themerex');
			else {
				$subscribers[$group][$email] = md5(mt_rand());
				update_option('emailer_subscribers', $subscribers);
				$subj = sprintf(__('Site %s - Subscribe confirmation', 'themerex'), get_bloginfo('site_name'));
				$url = admin_url('admin-ajax.php');
				$link = $url . (themerex_strpos($url, '?') === false ? '?' : '') . 'action=emailer_confirm&nonce=' . urlencode($subscribers[$group][$email]) . '&email=' . urlencode($email) . '&group=' . urlencode($group);
				$msg = sprintf(__("You or someone else added this e-mail address into our subcribtion list.\nPlease, confirm your wish to receive newsletters from our website by clicking on the link below:\n\n<a href=\"%s\">%s</a>\n\nIf you do not wiish to subscribe to our newsletters, simply ignore this message.", 'themerex'), $link, $link);
				add_filter('wp_mail_content_type', 'set_html_content_type');
				$sender_name = get_bloginfo('name');
				$sender_email = get_theme_option('contact_email');
				if (empty($sender_email)) $sender_email = get_bloginfo('admin_email');
				$headers = 'From: ' . $sender_name . ' <' . $sender_email . '>' . "\r\n";
				$mail = get_theme_option('mail_function');
				if (!@$mail($email, $subj, nl2br($msg), $headers)) {
					$response['error'] = __('Error send message!', 'themerex');
				}
				remove_filter('wp_mail_content_type', 'set_html_content_type');
			}
		} else
			$response['error'] = __('E-mail address is not valid!', 'themerex');
		echo json_encode($response);
		die();
	}
}

// Confirm e-mail in subscribe list
if (!function_exists('themerex_callback_emailer_confirm')) {
	function themerex_callback_emailer_confirm()
	{
		global $_REQUEST;

		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];
		$nonce = $_REQUEST['nonce'];
		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = themerex_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email])) {
				if ($subscribers[$group][$email] == $nonce) {
					$subscribers[$group][$email] = '';
					update_option('emailer_subscribers', $subscribers);
					themerex_set_message(__('Confirmation complete! E-mail address succefully added in the subscribers list!', 'themerex'), 'success');
					header('Location: ' . home_url());
				} else if ($subscribers[$group][$email] != '') {
					themerex_set_message(__('Bad confirmation code!', 'themerex'), 'error');
					header('Location: ' . home_url());
				} else {
					themerex_set_message(__('E-mail address already exists in the subscribers list!', 'themerex'), 'error');
					header('Location: ' . home_url());
				}
			}
		}
		die();
	}
}


// Get subscribers list if group changed
if (!function_exists('themerex_callback_emailer_group_getlist')) {
	function themerex_callback_emailer_group_getlist()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '', 'subscribers' => '');

		$group = $_REQUEST['group'];
		$subscribers = themerex_emailer_group_getlist($group);
		$list = array();
		if (isset($subscribers[$group]) && count($subscribers[$group]) > 0) {
			foreach ($subscribers[$group] as $k => $v) {
				if (empty($v))
					$list[] = $k;
			}
		}
		$response['subscribers'] = join("\n", $list);

		echo json_encode($response);
		die();
	}
}

// Get Subscribers list
if (!function_exists('themerex_emailer_group_getlist')) {
	function themerex_emailer_group_getlist($group = '')
	{
		$subscribers = get_option('emailer_subscribers', array());
		if (!is_array($subscribers))
			$subscribers = array();
		if (!empty($group) && (!isset($subscribers[$group]) || !is_array($subscribers[$group])))
			$subscribers[$group] = array();
		if (count($subscribers) > 0) {
			$need_save = false;
			foreach ($subscribers as $grp => $list) {
				if (isset($list[0])) {	// Plain array - old format - convert it
					$rez = array();
					foreach ($list as $v) {
						$rez[$v] = '';
					}
					$subscribers[$grp] = $rez;
					$need_save = true;
				}
			}
			if ($need_save)
				update_option('emailer_subscribers', $subscribers);
		}
		return $subscribers;
	}
}

// Set post likes/views count
if (!function_exists('themerex_callback_post_counter')) {
	function themerex_callback_post_counter()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '');

		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$counter = max(0, (int) $_REQUEST['likes']);
			setPostLikes($id, $counter);
		} else if (isset($_REQUEST['views'])) {
			$counter = max(0, (int) $_REQUEST['views']);
			setPostViews($id, $counter);
		}
		echo json_encode($response);
		die();
	}
}

// Calendar change month
if (!function_exists('themerex_callback_calendar_change_month')) {
	function themerex_callback_calendar_change_month()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$m = (int) $_REQUEST['month'];
		$y = (int) $_REQUEST['year'];
		$pt = $_REQUEST['post_type'];

		$response = array('error' => '', 'data' => getThemeRexCalendar(true, $m, $y, array('post_type' => $pt)));

		echo json_encode($response);
		die();
	}
}

// Get attachment url
if (!function_exists('themerex_callback_get_attachment_url')) {
	function themerex_callback_get_attachment_url()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '');

		$id = (int) $_REQUEST['attachment_id'];

		$response['data'] = wp_get_attachment_url($id);

		echo json_encode($response);
		die();
	}
}

// Send contact form data
if (!function_exists('themerex_callback_send_contact_form')) {
	function themerex_callback_send_contact_form()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '');
		if (!($contact_email = get_theme_option('contact_email')) && !($contact_email = get_theme_option('admin_email')))
			$response['error'] = __('Unknown admin email!', 'themerex');
		else {
			$type = themerex_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if ($type == 'okorder') {
				$user_name = themerex_substr($post_data['q1'], 0, 30);
				$user_company = themerex_substr($post_data['q2'], 0, 60);
				$user_email = themerex_substr($post_data['q3'], 0, 60);
				$user_project = getShortString($post_data['q4'], 100);
				$user_budget = getShortString($post_data['q5'], 60);
				$user_msg = getShortString($post_data['q6'], get_theme_option('message_maxlength_contacts'));

				$subj = sprintf(__('Site %s - Order form message from %s', 'themerex'), get_bloginfo('site_name'), $user_name);
				$msg = "\n" . __('Name:', 'themerex')   . ' ' . $user_name
					.  "\n" . __('Company:', 'themerex') . ' ' . $user_company
					.  "\n" . __('E-mail:', 'themerex') . ' ' . $user_email
					.  "\n" . __('Project:', 'themerex') . ' ' . $user_project
					.  "\n" . __('Budget:', 'themerex') . ' ' . $user_budget
					.  "\n" . __('Message:', 'themerex') . ' ' . $user_msg;
			} else if ($type == 'contact') {
				$user_name = themerex_substr($post_data['username'], 0, 20);
				$user_email = themerex_substr($post_data['email'], 0, 60);
				$user_msg = getShortString($post_data['message'], get_theme_option('message_maxlength_contacts'));

				$subj = sprintf(__('Site %s - Contact form message from %s', 'themerex'), get_bloginfo('site_name'), $user_name);
				$msg = "\n" . __('Name:', 'themerex')   . ' ' . $user_name
					.  "\n" . __('E-mail:', 'themerex') . ' ' . $user_email
					.  "\n" . __('Message:', 'themerex') . ' ' . $user_msg;
			} else {

				$subj = sprintf(__('Site %s - Contact form message', 'themerex'), get_bloginfo('site_name'));
				$msg = '';
				foreach ($post_data as $k => $v)
					$msg .= "\n{$k}: $v";
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . home_url() . ") ............";

			$mail = get_theme_option('mail_function');
			if (!@$mail($contact_email, $subj, $msg)) {
				$response['error'] = __('Error send message!', 'themerex');
			}

			echo json_encode($response);
			die();
		}
	}
}

// Get next page on blog streampage
if (!function_exists('themerex_callback_view_more_posts')) {
	function themerex_callback_view_more_posts()
	{
		global $_REQUEST, $post, $wp_query;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '', 'data' => '', 'no_more_data' => 0);

		$page = $_REQUEST['page'];
		$args = unserialize(stripslashes($_REQUEST['data']));
		$vars = unserialize(stripslashes($_REQUEST['vars']));

		if ($page > 0 && is_array($args) && is_array($vars)) {
			extract($vars);
			$args['page'] = $page;
			$args['paged'] = $page;
			$args['ignore_sticky_posts'] = 1;
			if (!isset($wp_query))
				$wp_query = new WP_Query($args);
			else
				query_posts($args);
			$per_page = count($wp_query->posts);
			$response['no_more_data'] = $page >= $wp_query->max_num_pages;	//$per_page < $ppp;
			$post_number = 0;
			$accent_color = '';
			$response['data'] = '';
			$flt_ids = array();
			while (have_posts()) {
				the_post();
				$post_number++;

				$post_args = array(
					'layout' => in_array(themerex_substr($vars['blog_style'], 0, 7), array('classic', 'masonry', 'portfol')) ? themerex_substr($vars['blog_style'], 0, 7) : themerex_substr($vars['blog_style'], 0, 7),
					'number' => $post_number,
					'add_view_more' => false,
					'posts_on_page' => $per_page,
					// Get post data
					'thumb_size' => $vars['blog_style'],
					'thumb_crop' => themerex_strpos($vars['blog_style'], 'masonry') === false,
					'strip_teaser' => false,
					'parent_cat_id' => $vars['parent_cat_id'],
					'sidebar' => !in_array($vars['show_sidebar_main'], array('none', 'fullwidth')),
					'filters' => $vars['filters'],
					'hover' => $vars['hover'],
					'show' => false
				);
				$post_data = getPostData($post_args);
				$response['data'] .= showPostLayout($post_args, $post_data);
				if ($vars['filters'] == 'tags') {
					if (count($post_data['post_tags_list']) > 0) {
						foreach ($post_data['post_tags_list'] as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
			$response['filters'] = $flt_ids;
		} else {
			$response['error'] = __('Wrong query arguments', 'themerex');
		}

		echo json_encode($response);
		die();
	}
}

// Incremental search
if (!function_exists('themerex_callback_ajax_search')) {
	function themerex_callback_ajax_search()
	{
		global $_REQUEST, $post, $wp_query;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce'))
			die();

		$response = array('error' => '', 'data' => '');

		$s = $_REQUEST['text'];

		if (!empty($s)) {

			$show_types = get_theme_option('ajax_search_types');
			$show_date = get_theme_option('ajax_search_posts_date') == 'yes' ? 1 : 0;
			$show_image = get_theme_option('ajax_search_posts_image') == 'yes' ? 1 : 0;
			$show_author = get_theme_option('ajax_search_posts_author') == 'yes' ? 1 : 0;
			$show_counters = get_theme_option('ajax_search_posts_counters') == 'yes' ? get_theme_option('blog_counters') : '';
			$args = array(
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'desc',
				'posts_per_page' => max(1, min(10, get_theme_option('ajax_search_posts_count'))),
				's' => esc_html($s),
			);
			// Filter post types
			if (!empty($show_types)) $args['post_type'] = explode(',', $show_types);
			// Exclude categories
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex))
				$args['category__not_in'] = explode(',', $ex);

			$args = apply_filters('ajax_search_query', $args);

			$post_number = 0;
			$output = '';

			if (!isset($wp_query))
				$wp_query = new WP_Query($args);
			else
				query_posts($args);
			while (have_posts()) {
				the_post();
				$post_number++;
				require(themerex_get_file_dir('/templates/page-part-widgets-posts.php'));
			}
			if (empty($output)) {
				$output .= '<article class="post_item">' . __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themerex') . '</article>';
			} else {
				$output .= '<article class="post_item"><a href="#" class="post_more">' . __('More results &hellip;', 'themerex') . '</a></article>';
			}
			$response['data'] = $output;
		} else {
			$response['error'] = __('The query string is empty!', 'themerex');
		}

		echo json_encode($response);
		die();
	}
}




/* ========================= Taxonomies cache ============================== */
// Clear all cache (when save or delete post)
if (!function_exists('themerex_clear_all_terms_filters')) {
	function themerex_clear_all_terms_filters($post_id = 0, $post_obj = null)
	{
		delete_transient("themerex_terms_filter_category");
		delete_transient("themerex_terms_filter_post_tag");
		delete_transient("themerex_terms_filter_post_format");
	}
}

// Clear categories cache (when create, edit or delete category)
if (!function_exists('themerex_clear_categories_filters')) {
	function themerex_clear_categories_filters($term_id = 0)
	{
		delete_transient("themerex_terms_filter_category");
	}
}


// Add parameters to URL
if (!function_exists('themerex_add_to_url')) {
	function themerex_add_to_url($url, $prm)
	{
		if (is_array($prm) && count($prm) > 0) {
			$separator = themerex_strpos($url, '?') === false ? '?' : '&';
			foreach ($prm as $k => $v) {
				$url .= $separator . urlencode($k) . '=' . urlencode($v);
				$separator = '&';
			}
		}
		return $url;
	}
}


/* ========================= Frontend Editor ============================== */

// Save post data from frontend editor
if (!function_exists('themerex_callback_frontend_editor_save')) {
	function themerex_callback_frontend_editor_save()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'themerex_editor_nonce'))
			die();

		$response = array('error' => '');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if (get_theme_option("allow_editor") == 'yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id))) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0)
					$response['error'] = __('Post update error!', 'themerex');
			} else {
				$response['error'] = __('Post update error!', 'themerex');
			}
		} else
			$response['error'] = __('Post update denied!', 'themerex');

		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if (!function_exists('themerex_callback_frontend_editor_delete')) {
	function themerex_callback_frontend_editor_delete()
	{
		global $_REQUEST;

		if (!wp_verify_nonce($_REQUEST['nonce'], 'themerex_editor_nonce'))
			die();

		$response = array('error' => '');

		$post_id = $_REQUEST['post_id'];

		if (get_theme_option("allow_editor") == 'yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id))) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false)
					$response['error'] = __('Post delete error!', 'themerex');
			} else {
				$response['error'] = __('Post delete error!', 'themerex');
			}
		} else
			$response['error'] = __('Post delete denied!', 'themerex');

		echo json_encode($response);
		die();
	}
}

//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if (!function_exists('themerex_importer_set_options')) {
	add_filter('trx_utils_filter_importer_options', 'themerex_importer_set_options', 9);
	function themerex_importer_set_options($options = array())
	{
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			if (is_dir(THEMEREX_THEME_PATH . 'demo/')) {
				$options['demo_url'] = THEMEREX_THEME_PATH . 'demo/';
			} else {
				$options['demo_url'] = esc_url(themerex_get_protocol() . '://demofiles.themerex.net/classter/'); // Demo-site domain
			}

			// Required plugins
			$options['required_plugins'] =  array(
				'woocommerce',
				'js_composer',
				'wp-instagram-widget',
				'revslider',
				'wp-booking-calendar'
			);

			$options['theme_slug'] = 'themerex';

			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 3;
			// Default demo
			$options['files']['default']['title'] = esc_html__('Classter Demo', 'themerex');
			$options['files']['default']['domain_dev'] = esc_url(themerex_get_protocol() . '://classter.themerex.net'); // Developers domain
			$options['files']['default']['domain_demo'] = esc_url(themerex_get_protocol() . '://classter.themerex.net'); // Demo-site domain

		}
		return $options;
	}
}



function wpb_move_comment_field_to_bottom($fields)
{
	$comment_field = $fields['comment'];
	unset($fields['comment']);
	$fields['comment'] = $comment_field;
	return $fields;
}

add_filter('comment_form_fields', 'wpb_move_comment_field_to_bottom');

/* ========================= Include required files ============================== */


require_once(ABSPATH . 'wp-admin/includes/plugin.php');

require_once((file_exists(get_stylesheet_directory() . '/includes/_utils.php')    ? get_stylesheet_directory() : get_template_directory()) . '/includes/_utils.php');
require_once((file_exists(get_stylesheet_directory() . '/includes/_wp_utils.php') ? get_stylesheet_directory() : get_template_directory()) . '/includes/_wp_utils.php');
require_once(themerex_get_file_dir('/includes/_debug.php'));

require_once(themerex_get_file_dir('/includes/theme-lists.php'));
require_once(themerex_get_file_dir('/admin/theme-settings.php'));
if (is_themerex_options_used()) {
	require_once(themerex_get_file_dir('/admin/theme-options.php'));
}

require_once(themerex_get_file_dir('/includes/theme-customizer.php'));

require_once(themerex_get_file_dir('/includes/aq_resizer.php'));

require_once(themerex_get_file_dir('/admin/includes/type-attachment.php'));
require_once(themerex_get_file_dir('/admin/includes/type-category.php'));
require_once(themerex_get_file_dir('/admin/includes/type-post.php'));
require_once(themerex_get_file_dir('/admin/includes/type-page.php'));
require_once(themerex_get_file_dir('/admin/includes/type-woocommerce.php'));
require_once(themerex_get_file_dir('/admin/includes/type-tribe-events.php'));

if (get_theme_option('custom_menu') == 'yes') {
	require_once(themerex_get_file_dir('/admin/tools/custom_menu/custom_menu.php'));
}

require_once(themerex_get_file_dir('/shortcodes/shortcodes.php'));

require_once(themerex_get_file_dir('/widgets/widget-popular-posts.php'));
require_once(themerex_get_file_dir('/widgets/widget-recent-posts.php'));
require_once(themerex_get_file_dir('/widgets/widget-flickr.php'));
require_once(themerex_get_file_dir('/widgets/widget-twitter2.php'));
require_once(themerex_get_file_dir('/widgets/widget-advert.php'));
require_once(themerex_get_file_dir('/widgets/widget-socials.php'));
require_once(themerex_get_file_dir('/widgets/widget-categories.php'));
//require_once( themerex_get_file_dir('/widgets/widget-calendar.php') );
require_once(themerex_get_file_dir('/widgets/qrcode/widget-qrcode.php'));

if (is_admin()) {
	if (get_theme_option('admin_update_notifier') == 'yes') {
		require_once(themerex_get_file_dir('/admin/tools/update-notifier.php'));
	}
	if (get_theme_option('admin_emailer') == 'yes') {
		require_once(themerex_get_file_dir('/admin/tools/emailer/emailer.php'));
	}
	if (get_theme_option('admin_po_composer') == 'yes') {
		require_once(themerex_get_file_dir('/admin/tools/po_composer/po_composer.php'));
	}
	if (get_theme_option('admin_dummy_data') == 'yes' && file_exists(themerex_get_file_dir('/admin/tools/importer/importer.php'))) {
		require_once(themerex_get_file_dir('/admin/tools/importer/importer.php'));
	}
	require_once(themerex_get_file_dir('/admin/tools/tgm/class-tgm-plugin-activation.php'));
}
function single_post_arabic_date($postdate_d, $postdate_d2, $postdate_m, $postdate_y)
{
	$months = array("January" => "يناير", "Feburary" => "فبراير", "March" => "مارس", "April" => "أبريل", "May" => "مايو", "June" => "يونيو", "July" => "يوليو", "August" => "أغسطس", "September" => "سبتمبر", "October" => "أكتوبر", "November" => "نوفمبر", "December" => "ديسمبر");
	$en_month = $postdate_m;
	foreach ($months as $en => $ar) {
		if ($en == $en_month) {
			$ar_month = $ar;
		}
	}

	$find = array("Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri");
	$replace = array("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
	$ar_day_format = $postdate_d2;
	$ar_day = str_replace($find, $replace, $ar_day_format);

	$standard = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	$eastern_arabic_symbols = array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩");
	$post_date = $postdate_m . ' ' . $postdate_d . ' ,  ' . $postdate_y;
	$arabic_date = str_replace($standard, $eastern_arabic_symbols, $post_date);

	return $arabic_date;
}
add_action('rest_api_init', function () {
	register_rest_field(
		// if you need it to work with other (even custom post) types,
		// then you have to use an array:
		// array( 'page', 'post', 'custom_post_type', 'etc' )
		// this example only does the trick for 'page'
		// look at the link in the first EDIT section of this answer
		'page',
		'content',
		array(
			'get_callback'    => 'compasshb_do_shortcodes',
			'update_callback' => null,
			'schema'          => null,
		)
	);
});

function compasshb_do_shortcodes($object, $field_name, $request)
{
	WPBMap::addAllMappedShortcodes(); // This does all the work

	global $post;
	$post = get_post($object['id']);
	$output['rendered'] = apply_filters('the_content', $post->post_content);

	return $output;
}


// API Magic Start.

// Fetch Gallery of Post Start.

function ids_get_post_gallery_images($post_id)
{
	$content = get_post_field('post_content', $post_id);
	$gallery = array();

	foreach (parse_blocks($content) as $block) {
		if ($block['blockName'] = 'core/gallery') {
			preg_match_all('#src=([\'"])(.+?)\1#is', $block['innerHTML'], $images, PREG_SET_ORDER);
			if (!empty($images)) {
				foreach ($images as $image) {
					$gallery[] = $image[2];
				}
			}
		}
	}

	return $gallery;
}

// Fetch Gallery of Post End.

// General Function to Prepare Objects for API Start. 

function ids_get_posts($args)
{
	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;
		$single_news_rest_object->url = get_permalink($post->ID);
		$single_news_rest_object->featured_image = get_the_post_thumbnail_url($post->ID);
		$single_news_rest_object->gallery = ids_get_post_gallery_images($post->ID);

		$post_category = get_the_category($post->ID)[0];
		$single_news_rest_object->category = [
			"id" => $post_category->cat_ID,
			"name" => $post_category->cat_name,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

// General Function to Prepare Objects for API End. 


// API for News Categories Start.

function ids_news_categories(WP_REST_Request $request)
{

	$args = array(
		'number' =>       $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'count',
		'lang' => $request['lang']
	);

	$categories = get_categories($args);

	$response = array();

	foreach ($categories as $category) {
		$single_category_object = new stdClass();

		$single_category_object->id = $category->cat_ID;
		$single_category_object->name = $category->cat_name;
		$single_category_object->description = $category->category_description;
		$single_category_object->count = $category->count;
		$single_category_object->parent = $category->parent;

		$response[] = $single_category_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/news-categories', array(
		'methods' => 'GET',
		'callback' => 'ids_news_categories',
	));
});

// API for News Categories End.


// API for News Start.

function ids_news(WP_REST_Request $request)
{
	// $postController = new WP_REST_Posts_Controller('post');
	// $response = $postController->prepare_item_for_response(get_post($id), $request);
	// $data[] = $postController->prepare_response_for_collection($response);

	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang']
	);

	if ($request["cat_id"]) {
		$args['category'] = $request["cat_id"];
	}

	return ids_get_posts($args);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/news', array(
		'methods' => 'GET',
		'callback' => 'ids_news',
	));
});

// API for News End.

// API for News Start.

function ids_single_news_post(WP_REST_Request $request)
{
	// pll_get_post() returns the id of the translated post.

	if ($request["lang"]) {
		$post_id = pll_get_post($request["id"], $request["lang"]);
	}
	$args = array(
		'post__in' =>  array($post_id),
		'posts_per_page' => 1,
		'lang' => $request['lang']
	);

	return ids_get_posts($args)[0];
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/news/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'ids_single_news_post',
	));
});

// API for News End.


// API for Laws Start.

function ids_laws(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'laws'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/laws', array(
		'methods' => 'GET',
		'callback' => 'ids_laws',
	));
});

// API for Laws End.


// API for Laws Start.

function ids_templates(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'templates'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/templates', array(
		'methods' => 'GET',
		'callback' => 'ids_templates',
	));
});

// API for Laws End.

// API for Diary Start.

function ids_diary(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'diary'
	);

	if ($request["diary_cats"]) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category1',
				'field'    => 'slug',
				'terms'    => array($request["diary_cats"])
			)
		);
	}

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/diary', array(
		'methods' => 'GET',
		'callback' => 'ids_diary',
	));
});

// API for Diary End.

// API for Bids Start.

function ids_bids(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'bids'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$single_news_rest_object->status = get_field("status", $posts[0]->ID);

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/bids', array(
		'methods' => 'GET',
		'callback' => 'ids_bids',
	));
});

// API for Bids End.

// API for About Us Start.

function ids_about_us(WP_REST_Request $request)
{

	//$about_us_page_id = 2512;

	//$page = get_post($about_us_page_id);

	$response = array();

	//$pattern = "/<a href=\"tel?.*>(.*)<\/a>/";
	//preg_match($pattern, $page->post_content, $matches);

	$response["phone"] = "07123456";

	//$pattern = "/<a href=\"mailto?.*>(.*)<\/a>/";
	//preg_match($pattern, $page->post_content, $matches);

	$response["email"] = "info@makhateer.com";

	$response["location"] = array(
		"lat" => 33.87718,
		"long" => 35.522073,
		"address" => $request["lang"] == "ar" ? "شارع إلياس الهراوي, الأشرفية, بيروت, 10650 الأشرفية بيروت لبنان" : "Elias Hrawi Street, Achrafieh, Beirut, 10650 Achrafieh Beirut Lebanon"
	);

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/about-us', array(
		'methods' => 'GET',
		'callback' => 'ids_about_us',
	));
});

// API for About Us End.

// API for FAQ Start.

function ids_faq(WP_REST_Request $request)
{

	$args = array(
		'posts_per_page' => -1, //$request['per_page'],
		//'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'faq'
	);

	$response = array();

	$terms = get_terms(array("taxonomy" => "faq_cat"));

	foreach ($terms as $term) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'faq_cat',
				'field'    => 'id',
				'terms'    => array($term->term_taxonomy_id)
			)
		);

		$posts = get_posts($args);


		foreach ($posts as $post) {
			$single_news_rest_object = new stdClass();

			$single_news_rest_object->id = $post->ID;
			$single_news_rest_object->title = $post->post_title;
			$single_news_rest_object->content = $post->post_content;
			$single_news_rest_object->date = $post->post_date;
			$single_news_rest_object->date_updated = $post->post_modified;

			$response[$term->name][] = $single_news_rest_object;
		}
	}

	return $response;
}
add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/faq', array(
		'methods' => 'GET',
		'callback' => 'ids_faq',
	));
});

// API for FAQ End.

// API for Mokhtar Start.

function ids_mokhtar(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'mokhtar'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Mokhtar" is a custom post type that have 2 custom field: phone and photo.
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$single_news_rest_object->phone = get_field('phone', $post->ID);
		$single_news_rest_object->photo = get_field('photo', $post->ID);

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/phone-book', array(
		'methods' => 'GET',
		'callback' => 'ids_mokhtar',
	));
});

// API for Mokhtar End.

// API for Decrees Start.

function ids_decrees(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'decrees'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/decrees', array(
		'methods' => 'GET',
		'callback' => 'ids_decrees',
	));
});

// API for Decrees End.

// API for Decrees Start.

function ids_decisions(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'decisions'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/decisions', array(
		'methods' => 'GET',
		'callback' => 'ids_decisions',
	));
});

// API for Decrees End.

// API for Decrees Start.

function ids_requests(WP_REST_Request $request)
{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'requests'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Laws" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/requests', array(
		'methods' => 'GET',
		'callback' => 'ids_requests',
	));
});

// API for Decrees End.

// API Governates/Districts Start.

function ids_regions(WP_REST_Request $request)
{
	if ($request["lang"] == "en") {
		$regions = [
			'Beirut' => [
				'Beirut',
			],
			'Mount Lebanon' => [
				'Metn',
				'Shouf',
				'Baabda',
				'Aalay',
				'Jbeil',
				'Keserwein',
			],
			'South' => [
				'Tyr',
				'Saida',
				'Jizzin',
			],
			'Nabatiyeh' => [
				'Nabatieh',
				'Marjiyoun',
				'Binet Jbeil',
				'Hasbaya',
			],
			'Bekaa' => [
				'West Bekaa',
				'Zahle',
				'Rashaya',
			],
			'North' => [
				'Tripoli',
				'Batroun',
				'Koura',
				'Hermel',
				'Zgharta',
				'Bshari',
				'Diniye',
			],
			'Baalbek - Hermel' => [
				'Baalbek',
			],
			'Akkar' => [
				'Akkar',
			],
		];
	} else {
		$regions = array(
			"بيروت" => array(
				"بيروت"
			),
			"جبل لبنان" => array(
				"المتن",
				"الشوف",
				"بعبدا",
				"عاليه",
				"جبيل",
				"كسروان",
			),
			"الجنوب" => array(
				"صور",
				"صيدا",
				"جزين",
			),
			"النبطية" => array(
				"النبطية",
				"مرجعيون",
				"بنت جبيل",
				"حاصبيا",
			),
			"البقاع" => array(
				"البقاع الغربي",
				"زحلة",
				"راشيا",
			),
			"الشمال" => array(
				"طرابلس",
				"البترون",
				"الكورة",
				"الهرمل",
				"زغرتا",
				"بشري",
				"المنيه الضنيه",
			),
			"بعلبك - الهرمل" => array(
				"بعلبك",
			),
			"عكار" => array(
				"عكار",
			),
		);
	}

	return $regions;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/regions', array(
		'methods' => 'GET',
		'callback' => 'ids_regions',
	));
});

// API Governates/Districts End.

// API Contact Us Start.

function ids_contact_us(WP_REST_Request $request)
{
	$request->set_url_params(array("id" => 5981));
	$result = wpcf7_rest_create_feedback($request);

	$response = array();

	if ($result->data["status"] == "mail_sent") {
		$response["into"] = "";
		$response["status"] = "mail_sent";
		$response["message"] =  "";
		$response["posted_data_hash"] = "";
	} else {
	    $response["into"] = "";
		$response["status"] = "mail_not_sent";
		$response["message"] = "";
		$response["posted_data_hash"] =  "";
	}
	$response["message"] = $result->data["message"];

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/message-us', array(
		'methods' => 'POST',
		'callback' => 'ids_contact_us',
	));
});

// API Contact Us End.

// API Search Start.

function ids_search(WP_REST_Request $request)
{
	$args = array(
		"s" => $request["key"],
		"lang" => $request["lang"]
	);

	$query_results = new WP_Query($args);

	$posts = array();

	foreach ($query_results->posts as $post) {
		$posts[] = (object)[
			"id" => $post->ID,
			"date" => $post->post_date,
			"date_updated" => $post->post_modified,
			"title" => $post->post_title,
			"content" => $post->post_content,
		];
	}

	return $posts;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/search', array(
		'methods' => 'GET',
		'callback' => 'ids_search',
	));
});

// API Search End.

// API Team Start.

function ids_team(WP_REST_Request $request)
{

	$response = array();

	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'gs_team',
	);

	$posts = get_posts($args);

	foreach ($posts as $post) {
		$single_page_rest_object = new stdClass();

		$single_page_rest_object->id = $post->ID;
		$single_page_rest_object->title = $post->post_title;
		$single_page_rest_object->content =  $post->post_content;
		$single_page_rest_object->date = $post->post_date;
		$single_page_rest_object->date_updated = $post->post_modified;
		$single_page_rest_object->image = wp_get_attachment_image_src(get_post_meta($post->ID, "_thumbnail_id", true), "large")[0];
		$single_page_rest_object->position = get_post_meta($post->ID, "_gs_des", true);

		$response[] = $single_page_rest_object;
	}


	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/team', array(
		'methods' => 'GET',
		'callback' => 'ids_team',
	));
});


// API Team End.

// API Pages Start.

function ids_pages(WP_REST_Request $request)
{

	$response = array();

	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'page',
	);

	$menu_locations = get_nav_menu_locations();

	if ($menu_locations["mainmenu"]) {
		$menu_items = wp_get_nav_menu_items($menu_locations["mainmenu"]);

		$menu_items = array_values(
			array_filter($menu_items, function ($value) {
				return $value->object == "page";
			})
		);

		$pages_ids = array();

		foreach ($menu_items as $menu_item) {
			$pages_ids[] = get_post_meta($menu_item->ID, '_menu_item_object_id', true);
		}

		$args['post__in'] = $pages_ids;

		$posts = get_posts($args);
		WPBMap::addAllMappedShortcodes(); // This does all the work

		$language = $request["lang"];

		foreach ($posts as $post) {
			$single_page_rest_object = new stdClass();

			$single_page_rest_object->id = $post->ID;
			$single_page_rest_object->title = $post->post_title;
			$single_page_rest_object->content = ids_rest_api_page_content($post->ID, $language);
			$single_page_rest_object->date = $post->post_date;
			$single_page_rest_object->date_updated = $post->post_modified;

			$response[] = $single_page_rest_object;
		}
	}


	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/pages', array(
		'methods' => 'GET',
		'callback' => 'ids_pages',
	));
});


// API Pages End.

// API Pages Start.

function ids_related_sites(WP_REST_Request $request)
{

	if ($request["lang"] == "ar") {
		$sites = [    
			['text' => 'رئاسة مجلس الوزراء', 'value' => 'http://www.interior.gov.lb/'],
			['text' => 'المديرية العامة للاحوال الشخصية', 'value' => 'https://www.dgcs.gov.lb/'],
		    ['text' => 'وزارة المالية', 'value' => '	http://www.finance.gov.lb/ar-lb'],
		    ['text' => 'وزارة الصحة', 'value' => 'https://www.moph.gov.lb/'],
		    ['text' => 'مجلس الخدمة المدنية', 'value' => 'http://www.csb.gov.lb/'], 
		    ['text' => 'وزارة الشؤون الاجتماعية', 'value' => 'http://www.socialaffairs.gov.lb/MSADefault.aspx'],
		    ['text' => 'وزارة التنمية الادارية', 'value' => 'https://www.omsar.gov.lb/'],
		    ['text' => 'الجيش اللبناني', 'value' => 'https://www.lebarmy.gov.lb/'],
		    ['text' => 'المديرية العامة للامن العام', 'value' => 'https://www.general-security.gov.lb/ar'],
		    ['text' => 'المديرية العامة لامن الدولة', 'value' => 'http://www.state-security.gov.lb/'],
		    ['text' => 'المديرية العامة لقوى الامن الداخلي', 'value' => 'https://www.isf.gov.lb/']
		];
	} else {
		$sites = [
		    ['text' => 'Presidency of the Council of Ministers', 'value' => 'http://www.interior.gov.lb/'],
		    ['text' => 'General Directorate of Personal Status', 'value' => 'https://www.dgcs.gov.lb/'],
		    ['text' => 'Ministry of Finance', 'value' => '	http://www.finance.gov.lb/ar-lb'],
		    ['text' => 'Ministry of Health', 'value' => 'https://www.moph.gov.lb/'],
		    ['text' => 'Civil Service Council', 'value' => 'http://www.csb.gov.lb/'],
		    ['text' => 'Ministry of Social Affairs', 'value' => 'http://www.socialaffairs.gov.lb/MSADefault.aspx'],
		    ['text' => 'Ministry of Administrative Development', 'value' => 'https://www.omsar.gov.lb/'],
		    ['text' => 'Lebanese army', 'value' => 'https://www.lebarmy.gov.lb/'],
		    ['text' => 'General Directorate of Public Security', 'value' => 'https://www.general-security.gov.lb/ar'],
		    ['text' => 'General Directorate of State Security', 'value' => 'http://www.state-security.gov.lb/'],
		    ['text' => 'General Directorate of Internal Security Forces', 'value' => 'https://www.isf.gov.lb/']
		];
	}

	return $sites;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/sites', array(
		'methods' => 'GET',
		'callback' => 'ids_related_sites',
	));
});


// API Pages End.

// API Dev Start.

function ids_dev()
{
	return "Stay OUT!";


	$users_args =  array(
		'role' => 'Mokhtar',
		'number' =>       -1,
		'meta_query' => array(
			array(
				"key" => "MemberOfBoardDirector",
				"value" => 1,
				"compare" => "=",
			)
		)
	);

	return get_users($users_args);
	/*return get_user_meta(15829);
	$user_args = array("role" => "Mokhtar");

	$users = get_users($user_args);

	$updated = 0;

	$haceb_mohafaza = $_GET["mohafaza"];
	$ids_mohafaza_id = $_GET["id"];

	if (!$haceb_mohafaza || !$ids_mohafaza_id) {
		return false;
	}

	foreach ($users as $user) {
		if (get_user_meta($user->ID, "Address", true) == $haceb_mohafaza) {
			update_user_meta($user->ID, "KazaID", $ids_mohafaza_id) ? $updated++ : null;
		}
	}

	return $updated;*/
	/*$user_args = array("role" => "Mokhtar");

	$users = get_users($user_args);

	foreach ($users as $user) {
		if ($user->user_login != "mokhtar_ids") {
			wp_delete_user($user->ID);
		}
	}
	return count($users);*/

	//return update_user_meta(7991,"MemberOfBoardDirector", true);

	/*return "STAY OUT";*/

	$headers = array("ID", "CID", "Password", "ActiveElection", "Address", "Name", "IsApproved", "MemberOfBoardDirector", "phone", "region", "RegionCode");


	$users_rows_csv = array();

	$user_args = array("role" => "Mokhtar", "exclude"  => array(7991));

	$users = get_users($user_args);

	// Create file and make it writable

	$file = fopen('file.csv', 'w');

	// Add BOM to fix UTF-8 in Excel

	fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

	// Headers
	// Set ";" as delimiter

	fputcsv($file, $headers, ",");

	// Rows
	// Set ";" as delimiter

	foreach ($users as $user) {
		$random_password = wp_generate_password(6, false, false);

		wp_set_password($random_password, $user->ID);

		$users_rows_csv[] = array(
			$user->ID,
			$user->user_login,
			$random_password,
			get_user_meta($user->ID, "ActiveElection", true),
			get_user_meta($user->ID, "Address", true),
			get_user_meta($user->ID, "first_name", true),
			get_user_meta($user->ID, "IsApproved", true),
			get_user_meta($user->ID, "MemberOfBoardDirector", true),
			get_user_meta($user->ID, "phone", true),
			get_user_meta($user->ID, "region", true),
			get_user_meta($user->ID, "RegionCode", true)
		);
	}

	foreach ($users_rows_csv as $user_row_csv) {
		fputcsv($file, $user_row_csv, ",");
	}

	// Close file

	fclose($file);

	// Send file to browser for download

	$dest_file =  'file.csv';
	$file_size = filesize($dest_file);

	header("Content-Type: text/csv; charset=utf-8");
	header("Content-disposition: attachment; filename=\"file.csv\"");
	header("Content-Length: " . $file_size);
	readfile($dest_file);
	exit;



	$haceb_username = 'IDSadmin';
	$haceb_password = 'IDSpassword';
	$haceb_api_url = "http://77.42.157.190:7048/BC160/OData/Company('الصندوق%20التعاوني%20للمختارين')/Customer";


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $haceb_api_url);

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers[] = "Accept: application/json";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERPWD,  "$haceb_username:$haceb_password");
	$response_data = curl_exec($ch);
	$info = curl_getinfo($ch);

	if ($info['http_code'] != 200) {
		return curl_error($ch);
	} else {
		$makhateer = json_decode($response_data);

		return count($makhateer->value);
		$with_region = 0;

		$without_region = 0;

		foreach ($makhateer->value as $mokhtar) {
			if ($mokhtar->region) {
				$with_region++;
			} else {
				$without_region++;
			}
		}

		return array(
			"with" => $with_region,
			"without" => $without_region
		);
	}
	$user = get_user_by("login", "ahmed");

	return get_user_meta($user->ID);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/dev', array(
		'methods' => 'GET',
		'callback' => 'ids_dev',
	));
});

// API Dev End.

// API Single Page Start.

function ids_single_page(WP_REST_Request $request)
{
	$response = array();

	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'page',
	);


	if ($request["lang"]) {
		$post_id = pll_get_post($request["id"], $request["lang"]);
	}


	$args["post__in"] = array($post_id);



	$posts = get_posts($args);
	WPBMap::addAllMappedShortcodes(); // This does all the work

	$language = $request["lang"];

	foreach ($posts as $single_post) {

		$single_page_rest_object = new stdClass();

		$single_page_rest_object->id = $single_post->ID;
		$single_page_rest_object->title = $single_post->post_title;
		$single_page_rest_object->content = ids_rest_api_page_content($single_post->ID, $language);
		$single_page_rest_object->date = $single_post->post_date;
		$single_page_rest_object->date_updated = $single_post->post_modified;

		$response = $single_page_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/pages/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'ids_single_page',
	));
});

// API Single Page End.

// API Fetch Notifications Start.

function ids_get_notifications($notification_filter = "", $filter_value = "", $exclude = array(), $per_page, $offset)
{
	$args = array(
		'posts_per_page' => $per_page,
		'offset'         => $offset,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'post_type' => 'notification',
		'exclude' =>  $exclude
	);

	switch ($notification_filter) {
		case "mokhtar": {
				$args["meta_query"] = array(
					array(
						"key" => "filter_user_role",
						"compare" => "EXISTS"
					),
					array(
						"key" => "filter_governate_id",
						"compare" => "NOT EXISTS"
					),
					array(
						"key" => "filter_district_id",
						"compare" => "NOT EXISTS"
					)
				);
				break;
			}
		case "mohafaza": {
				$args["meta_query"] = array(
					array(
						"key" => "filter_governate_id",
						"value" => $filter_value,
						"compare" => "=",
						'type' => 'NUMERIC'
					),
					array(
						"key" => "filter_district_id",
						"compare" => "NOT EXISTS"
					)
				);
				break;
			}
		case "kaza": {
				$args["meta_query"] = array(
					array(
						"key" => "filter_district_id",
						"value" => $filter_value,
						"compare" => "=",
						'type' => 'NUMERIC'
					)
				);
				break;
			}
		default: {
				$args["meta_query"] = array(
					array(
						"key" => "filter_user_role",
						"compare" => "NOT EXISTS"
					),
					array(
						"key" => "filter_governate_id",
						"compare" => "NOT EXISTS"
					),
					array(
						"key" => "filter_district_id",
						"compare" => "NOT EXISTS"
					)
				);
				break;
			}
	}

	return get_posts($args);
}

// function ids_notifications(WP_REST_Request $request)
// {

// 	$only_mokhtar = $request["mokhtar_id"];
// 	$mohafaza_id = $request["mohafaza_id"];
// 	$kaza_id = $request["kaza_id"];
// 	$device_id = $request["device_id"];

// 	$per_page = $request['per_page'];
// 	$offset = $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0;

// 	$notifications = array();

// 	$notifications = array_merge($notifications, ids_get_notifications("", "", array(), $per_page, $offset));

// 	if (!count($notifications)) {
// 		$offset -= count(ids_get_notifications("", "", array(), $per_page, 0));
// 	} else if ($per_page > count(ids_get_notifications("", "", array(), $per_page, 0)) && $offset > 0) {
// 		$offset -= count($notifications);
// 	}

// 	if ($only_mokhtar && $per_page > count($notifications)) {
// 		$notifications = array_merge($notifications, ids_get_notifications("mokhtar", "",  array_column($notifications, 'ID'), $per_page - count($notifications), $offset));
// 	}

// 	if (!count($notifications)) {
// 		$offset -= count(ids_get_notifications("mokhtar", "", array_column($notifications, 'ID'), $per_page, 0));
// 	} else if ($per_page > count(ids_get_notifications("mokhtar", "", array_column($notifications, 'ID'), $per_page, 0)) && $offset > 0) {
// 		$offset -= count($notifications);
// 	}

// 	if ($mohafaza_id &&  $per_page > count($notifications)) {
// 		$notifications = array_merge($notifications, ids_get_notifications("mohafaza", $mohafaza_id,  array_column($notifications, 'ID'), $per_page - count($notifications), $offset));
// 	}

// 	if (!count($notifications)) {
// 		$offset -= count(ids_get_notifications("mohafaza", $mohafaza_id,  array_column($notifications, 'ID'), $per_page, 0));
// 	} else if ($per_page > count(ids_get_notifications("mohafaza", $mohafaza_id,  array_column($notifications, 'ID'), $per_page, 0)) && $offset > 0) {
// 		$offset -= count($notifications);
// 	}

// 	if ($kaza_id &&  $per_page > count($notifications)) {
// 		$notifications = array_merge($notifications, ids_get_notifications("kaza", $kaza_id,  array_column($notifications, 'ID'), $per_page - count($notifications), $offset));
// 	}


// 	$response = array();

// 	foreach ($notifications as $post) {
// 		$single_notification_rest_object = new stdClass();

// 		$single_notification_rest_object->id = $post->ID;
// 		$single_notification_rest_object->sub_title = get_post_meta($post->ID, "filter_" . $request['lang'] . "_subtitle", true);
// 		$single_notification_rest_object->message = get_post_meta($post->ID, "filter_" . $request['lang'] . "_message", true);
// 		$single_notification_rest_object->date = $post->post_date;

// 		if ($device_id) {
// 			global $wpdb;

// 			$query = "SELECT COUNT(*) FROM {$wpdb->prefix}ids_devices WHERE device_id = $device_id AND FIND_IN_SET('$single_notification_rest_object->id', read_notifications)";
// 			$single_notification_rest_object->is_read = $wpdb->get_var($query) ? true : false;
// 		}

// 		$response[] = $single_notification_rest_object;
// 	}

// 	return $response;
// }

// add_action('rest_api_init', function () {
// 	register_rest_route('api/v1', '/notifications', array(
// 		'methods' => 'GET',
// 		'callback' => 'ids_notifications',
// 	));
// });

// // API Fetch Notifications End.

function ids_notifications(WP_REST_Request $request)
{

	global $wpdb;
	$device_id = $request["device_id"];
	$lang = $request["lang"];
    $notification_id = $request["notification_id"];
	$per_page = $request['per_page'];
	$page = $request['page'];
// 	$notifications = array();
	$from=$per_page * ($page - 1);
	$to = $per_page * $page;
    if(!empty($notification_id)){
        $query="SELECT * FROM wp_mobile_notifications where device_id=$device_id AND notification_id=$notification_id  ORDER BY date DESC  limit 1";
    }
    else{
        $query="SELECT *
                FROM wp_mobile_notifications WHERE device_id=$device_id 
                GROUP BY notification_id
                ORDER BY DATE DESC LIMIT $from,$to
               ";
    }
//     echo $query;
	$results=$wpdb->get_results($query);

	$response = array();

	foreach ($results as $post) {
		$single_notification_rest_object = new stdClass();
		$single_notification_rest_object->id = $post->notification_id;
		$single_notification_rest_object->sub_title = get_post_meta($post->notification_id, "filter_" . $lang . "_subtitle", true);
		$single_notification_rest_object->message = get_post_meta($post->notification_id, "filter_" . $lang . "_message", true);
		$single_notification_rest_object->date = $post->date;
		$single_notification_rest_object->is_read = $post->is_viewed ? true : false;
		$response[] = $single_notification_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/notifications', array(
		'methods' => 'GET',
		'callback' => 'ids_notifications',
	));
});


// API Fetch Notifications End.


// API Mark Notification as Read Start.

function ids_device(WP_REST_Request $request)
{

	$request_body = json_decode($request->get_body(), true);

	$device_id = $request_body["device_id"];
	$device_model = $request_body["device_model"];
	$os_version = $request_body["os_version"];
	$app_version = $request_body["app_version"];
	$user_id = $request_body["user_id"] ? $request_body["user_id"] : 0;
	$lang = $request_body["lang"];
	$general_notifications_enabled = $request_body["general_notifications_enabled"];

	global $wpdb;

	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}ids_devices WHERE device_id = '$device_id'";


	if ($wpdb->get_var($query)) {
		$query = "UPDATE {$wpdb->prefix}ids_devices SET device_model = '$device_model', os_version = '$os_version', app_version = '$app_version', user_id = $user_id, lang = '$lang', general_notifications_enabled = $general_notifications_enabled WHERE device_id = '$device_id'";
	} else {
		$query = "INSERT INTO {$wpdb->prefix}ids_devices values('$device_id', '$device_model', '$os_version', '$app_version', $user_id, NOW(), '$lang', '', $general_notifications_enabled)";
	}

	$affected_rows = $wpdb->query($query);

	return array("result" => $affected_rows);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/update-device', array(
		'methods' => 'POST',
		'callback' => 'ids_device',
	));
});
// update_device version 1.2

function ids_device_v1_2(WP_REST_Request $request)
{

    $date=wp_date('Y-m-d H:i:s');
	
	// POST: use $request->get_body() to get the request body.
	// GET: use $request["key"] to get query string.
	$request_body = json_decode($request->get_body(), true);
	$device_id = $request_body["device_id"];
	$device_model = $request_body["device_model"];
	$os_version = $request_body["os_version"];
	$app_version = $request_body["app_version"];
	$user_id = $request_body["user_id"] ? $request_body["user_id"] : 0;
	$lang = $request_body["lang"];
	$general_notifications_enabled = $request_body["general_notifications_enabled"];

	$device_token=$request_body['device_token'];
	$device_type_id=$request_body['device_type_id'];
	$IMEI=$request_body['IMEI'];
	$badge=$request_body['badge'];


	global $wpdb;

	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}ids_devices WHERE device_id = '$device_id'";
//echo $query;

	if ($wpdb->get_var($query)) {
	   // echo "hello update";
		$query = "UPDATE {$wpdb->prefix}ids_devices
		SET device_model = '$device_model',
		 os_version = '$os_version',
		  app_version = '$app_version',
		   user_id = $user_id,
		    lang = '$lang',
			 general_notifications_enabled = $general_notifications_enabled,
			 device_token='$device_token',
			 device_type_id=$device_type_id,
			 IMEI='$IMEI',
			 badge=$badge
			  WHERE device_id = '$device_id'";
	} else {
	   // echo "hello insert";
		$query = "INSERT INTO {$wpdb->prefix}ids_devices  
		(device_id,
		device_model,
		os_version,
	    app_version,
		user_id,
		registration_date,
		lang,
		general_notifications_enabled,
		device_token,
		device_type_id,
		IMEI,
		badge				
		) 
		values('$device_id',
		 '$device_model',
		  '$os_version',
		   '$app_version',
		    $user_id,
			'$date',
			'$lang',
			$general_notifications_enabled,
			'$device_token',
			$device_type_id,
			'$IMEI',
			$badge				
			)";
// 			echo $query;
	}

	$affected_rows = $wpdb->query($query);

	return array("result" => $affected_rows, "device_id" => $device_id);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/update-device-v1-2', array(
		'methods' => 'POST',
		'callback' => 'ids_device_v1_2',
	));
});
// update_device version 1.3

function ids_device_v1_3(WP_REST_Request $request)
{

    $date=wp_date('Y-m-d H:i:s');
	
	// POST: use $request->get_body() to get the request body.
	// GET: use $request["key"] to get query string.
	$request_body = json_decode($request->get_body(), true);
	$device_model = $request_body["device_model"];
	$os_version = $request_body["os_version"];
	$app_version = $request_body["app_version"];
	$user_id = $request_body["user_id"] ? $request_body["user_id"] : 0;
	$lang = $request_body["lang"];
	$general_notifications_enabled = $request_body["general_notifications_enabled"];

	$device_token=$request_body['device_token'];
	$device_type_id=$request_body['device_type_id'];
	$IMEI=$request_body['IMEI'];
	$badge=$request_body['badge'];
    $kaza_id=$request_body['kaza_id'];
	$mohafaza_id=$request_body['mohafaza_id'];
	$currently_mokhtar=$request_body['currently_mokhtar'];

	global $wpdb;

	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}ids_devices WHERE IMEI = '$IMEI'";
//echo $query;

	if ($wpdb->get_var($query)) {
	   // echo "hello update";
		$query = "UPDATE {$wpdb->prefix}ids_devices
		SET device_model = '$device_model',
		 os_version = '$os_version',
		  app_version = '$app_version',
		   user_id = $user_id,
		    lang = '$lang',
			 general_notifications_enabled = $general_notifications_enabled,
			 device_token='$device_token',
			 device_type_id=$device_type_id,
			 badge=$badge,kaza_id=$kaza_id,
	mohafaza_id=$mohafaza_id,
	currently_mokhtar=$currently_mokhtar
			  WHERE IMEI = '$IMEI'";
	} else {
	   // echo "hello insert";
		$query = "INSERT INTO {$wpdb->prefix}ids_devices  
		(
		device_model,
		os_version,
	    app_version,
		user_id,
		registration_date,
		lang,
		general_notifications_enabled,
		device_token,
		device_type_id,
		IMEI,
		badge,
		kaza_id,
	mohafaza_id,
	currently_mokhtar				
		) 
		values(
		 '$device_model',
		  '$os_version',
		   '$app_version',
		    $user_id,
			'$date',
			'$lang',
			$general_notifications_enabled,
			'$device_token',
			$device_type_id,
			'$IMEI',
			$badge,
			$kaza_id,
	$mohafaza_id,
	$currently_mokhtar
			)";
// 			echo $query;
	}

	$affected_rows = $wpdb->query($query);
	$query1 = "SELECT id FROM {$wpdb->prefix}ids_devices WHERE IMEI = '$IMEI'";
	return array("result" => $affected_rows, "device_id" => $wpdb->get_var($query1));
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/update-device-v1-3', array(
		'methods' => 'POST',
		'callback' => 'ids_device_v1_3',
	));
});

// API Mark Notification as Read End.

// API Mark Notification as Read Start.

// function ids_mark_notification_as_read(WP_REST_Request $request)
// {

// 	$request_body = json_decode($request->get_body(), true);

// 	$device_id = $request_body["device_id"];
// 	$user_id = $request_body["user_id"] ? $request_body["user_id"] : 0;
// 	$notification_id = $request_body["notification_id"];

// 	global $wpdb;

// 	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}ids_devices WHERE device_id = '$device_id' AND read_notifications IS NOT NULL";


// 	if ($wpdb->get_var($query)) {
// 		$query = "UPDATE {$wpdb->prefix}ids_devices SET read_notifications = CONCAT_WS(',', read_notifications, '$notification_id') WHERE device_id = '$device_id' AND !FIND_IN_SET('$notification_id', read_notifications)";
// 	} else {
// 		$query = "UPDATE {$wpdb->prefix}ids_devices SET read_notifications = '$notification_id' WHERE device_id = '$device_id'";
// 	}

// 	$affected_rows = $wpdb->query($query);

// 	return array("result" => $affected_rows);
// }

// add_action('rest_api_init', function () {
// 	register_rest_route('api/v1', '/mark-notification', array(
// 		'methods' => 'POST',
// 		'callback' => 'ids_mark_notification_as_read',
// 	));
// });

// API Mark Notification as Read End.

// API Mark Notification as Read Start.

function ids_mark_notification_as_read(WP_REST_Request $request)
{
  
		$request_body = json_decode($request->get_body(), true);
	$device_id = $request_body[device_id];
	$user_id = $request_body[user_id] ? $request_body[user_id] : 0;
	$notification_id = $request_body[notification_id];

	global $wpdb;

	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}mobile_notifications WHERE device_id = $device_id AND notification_id=$notification_id ";


	if ($wpdb->get_var($query)) {
		$query = "UPDATE {$wpdb->prefix}mobile_notifications SET is_viewed=1 WHERE device_id = $device_id AND notification_id=$notification_id ";
	} 
	$affected_rows = $wpdb->query($query);

	return array("result" => $affected_rows);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/mark-notification', array(
		'methods' => 'POST',
		'callback' => 'ids_mark_notification_as_read',
	));
});

// API Mark Notification as Read End.


// API Subscribe Newsletter Start.

function ids_subscribe_email_newsletter(WP_REST_Request $request)
{
	$request_body = json_decode($request->get_body(), true);

	$_POST["lang"] = $request_body["lang"];

	$request_body["esfpx_form_id"] = "2";
	$request_body["esfpx_es_email_page"] = "1746";
	$request_body["esfpx_es_email_page_url"] = "https://mokhtar-fund.gov.lb";
	$request_body["esfpx_status"] = "Unconfirmed";
	$request_body["esfpx_es-subscribe"] = "82aaf47bdc";
	$request_body["esfpx_es_hp_email"] = "";
	$request_body["esfpx_lists"] = [
		"2"
	];
	$request_body["es"] = "subscribe";
	$request_body["action"] = "es_add_subscriber";
	$request_body["esfpx_email"] = $request_body["email"];
	unset($request_body["email"]);
	$handle_email_subscription = new ES_Handle_Subscription();
	$result =  $handle_email_subscription->process_request($request_body);
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/newsletter', array(
		'methods' => 'POST',
		'callback' => 'ids_subscribe_email_newsletter',
	));
});

// API Subscribe Newsletter End.


// API Control REST API Page Content Start.

function ids_rest_api_page_content($page_id, $language)
{
	global $post;
	$post = get_post($page_id, OBJECT);
	setup_postdata($post);

	wp_reset_postdata();

	$ids_wp_head_heredoc = 'ids_wp_head_heredoc';
	$ids_body_class_heredoc = 'ids_rest_api_page_body_class';

	$html_structure = <<<EOF
	<html lang="{$language}">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			{$ids_wp_head_heredoc($page_id)}
		</head>
		<body {$ids_body_class_heredoc($page_id)}>
			<div class="main_content">
				<div class="boxedWrap">
					<div class="mainWrap without_sidebar">	
						<div class="main" role="main">
							<div class="content">
	EOF;

	$page_style = get_custom_option('single_style');


	$content = ids_showPostLayout(
		array(
			'layout' => esc_attr($page_style),
			'thumb_size' => esc_attr($page_style),
			'thumb_crop' => false,
			'sidebar' => !in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth')),
			'content' => true
		)
	);


	$content = $html_structure . $content;
	$content = $content . "</div></div></div></div></div></body></html>";
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	$content =  stripslashes($content);

	return $content;
}


function ids_rest_api_page_body_class($page_id)
{
	ob_start();

	global $THEMEREX_sidemenu, $THEMEREX_mainmenu, $THEMEREX_usermenu_show, $logo_text, $logo_icon, $logo_image, $logo_side, $logo_fixed;

	$class = $style = '';
	$body_style     = get_custom_option('body_style');
	$single_style   = get_custom_option('single_style');
	$slider_show	= get_custom_option('slider_show') == 'yes';
	$slider_fullscreen = $slider_show && get_custom_option('slider_display') == 'fullscreen';
	$show_top_panel = get_custom_option('show_top_panel');
	$video_bg_show  = get_custom_option('show_video_bg') == 'yes' && (get_custom_option('video_bg_youtube_code') != '' || get_custom_option('video_bg_url') != '');
	if ((!$slider_show || $body_style != 'boxed') && $show_top_panel == 'over') $show_top_panel = 'above';

	if ($body_style == 'boxed' || get_custom_option('load_bg_image') == 'always') {
		$customizer = get_theme_option('show_theme_customizer') == 'yes';
		if ($customizer && ($img = (int) getValueGPC('bg_image', 0)) > 0)
			$class = 'bg_image_' . $img;
		else if ($customizer && ($img = (int) getValueGPC('bg_pattern', 0)) > 0)
			$class = 'bg_pattern_' . $img;
		else if ($customizer && ($img = getValueGPC('bg_color', '')) != '')
			$style = 'background-color: ' . $img . ';';
		else {
			if (($img = get_custom_option('bg_custom_image')) != '')
				$style = 'background: url(' . $img . ') ' . str_replace('_', ' ', get_custom_option('bg_custom_image_position')) . ' no-repeat fixed;';
			else if (($img = get_custom_option('bg_custom_pattern')) != '')
				$style = 'background: url(' . $img . ') 0 0 repeat fixed;';
			else if (($img = get_custom_option('bg_image')) > 0)
				$class = 'bg_image_' . $img;
			else if (($img = get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_' . $img;
			if (($img = get_custom_option('bg_color')) != '')
				$style .= 'background-color: ' . $img . ';';
		}
		if (get_custom_option('transparent_bg_image') == 'yes' && (get_custom_option('bg_custom_image') != '' || get_custom_option('bg_image') > 0 || get_custom_option('bg_custom_pattern') != '' || get_custom_option('bg_pattern') > 0)) {
			$class .= ' transparent_bg_content';
		}
	}
	if (get_custom_option('show_top_page_below') == 'yes' && get_custom_option('menu_style') == 'fon') {
		$class .= ' top_page_below_menu';
	}

	$class .= "page-template-default page page-id-$page_id";

	body_class(
		'themerex_body ' . $body_style . ' top_panel_' . $show_top_panel . ' theme_skin_' . get_custom_option('theme_skin')
			. ($THEMEREX_sidemenu ? ' with_sidemenu sidemenu_left' : '')
			. ($video_bg_show ? ' video_bg' : '')
			. ($class != '' ? ' ' . $class : '')
	);
	if ($style != '') echo ' style="' . $style . '"';

	return ob_get_clean();
}

function ids_showPostLayout($opt = array(), $post_data = null, $post_obj = null)
{
	$opt = getPostDataOptions($opt);
	if ($post_data === null)
		$post_data = getPostData($opt, $post_obj);
	// Collect standard output
	$layout = '';
	ob_start();
	require(themerex_get_file_dir('/templates/post-layout-' . $opt['layout'] . '.php'));
	//if (!$opt['show'])  {
	$layout = ob_get_contents();
	ob_end_clean();
	//}
	clear_dedicated_content();
	return $layout;
}

function ids_wp_head_heredoc($post_id = null)
{
	ob_start();
	if ($post_id) {
		$shortcodes_custom_css = get_post_meta($post_id, '_wpb_shortcodes_custom_css', true);
		if (!empty($shortcodes_custom_css)) {
			$shortcodes_custom_css = strip_tags($shortcodes_custom_css);
			echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			echo $shortcodes_custom_css;
			echo '</style>';
		}
	}

	wp_head();

	echo "<style>" . wp_get_custom_css() . "</style>";

	echo '<link rel="stylesheet" id="js_composer_front-css" href="https://mokhtar-fund.gov.lb/wp-content/plugins/js_composer/assets/css/js_composer.min.css?ver=5.4.7" type="text/css" media="all">';


	if ($post_id == -1) {
		echo '<script type="text/javascript" src="https://mokhtar-fund.gov.lb/wp-content/plugins/js_composer/assets/lib/bower/chartjs/Chart.min.js" id="ChartJS-js"></script>';
		echo '<script type="text/javascript" src="https://mokhtar-fund.gov.lb/wp-content/plugins/js_composer/assets/lib/vc_round_chart/vc_round_chart.min.js" id="vc_round_chart-js"></script>';


		echo '<style type="text/css">
    .btn-primary {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
    
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
	</style>';
	}
	return ob_get_clean();
}

// API Control REST API Page Content End.

// API Statistics Page Start.

function ids_statistics_page_head()
{
	$template_path = get_template_directory();
	$page_templates_dir = "$template_path/page-templates";

	$content = file_get_contents("$page_templates_dir/ids_head.html");
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	$content =  stripslashes($content);

	return $content;
}

function ids_get_polls_css()
{
	$content = file_get_contents(ABSPATH . "/wp-content/plugins/ids_poll_ml/css/client.css");
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	$content =  stripslashes($content);

	//return $content;
}

function ids_statistics_page(WP_REST_Request $request)
{
	$language =	$request["lang"];

	$page_id = 3410;

	switch_to_locale($language);

	$ids_wp_head_heredoc = 'ids_wp_head_heredoc';
	$ids_polls_css = 'ids_get_polls_css';

	$ids_body_class_heredoc = 'ids_rest_api_page_body_class';

	$html_structure = <<<EOF
	<html lang="{$language}">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			{$ids_wp_head_heredoc(-1)}
		</head>
		<body {$ids_body_class_heredoc($page_id)}>
			<div class="main_content">
				<div class="boxedWrap">
					<div class="mainWrap without_sidebar">	
						<div class="main" role="main">
							<div class="content">
	EOF;

	// Custom Post Type Statistics Start.

	$posts_args = array(
		"post_type" => "statistic",
		"lang" => $language
	);

	$posts = get_posts($posts_args);

	ob_start();

	if (count($posts)) {

?>

		<div id="ids_statistics_dropdown__div">
			<select id='titles_dropdown'>

				<?php
				foreach ($posts as $post) {
				?>
					<option value='<?php echo $post->ID ?>'><?php echo $post->post_title ?></option>
				<?php
				}
				?>

			</select>
		</div>


	<?php
		wp_enqueue_script('statistics_script', get_site_url() . '/wp-content/plugins/js/statistics.js');

		WPBMap::addAllMappedShortcodes(); // This does all the work

		global $post;
		$post = get_post($posts[0]->ID, OBJECT);
		setup_postdata($post);

		wp_reset_postdata();
		$page_style = get_custom_option('single_style');

		$content = $html_structure;
		$content .= ob_get_clean();

		$content .= ids_showPostLayout(
			array(
				'layout' => esc_attr($page_style),
				'thumb_size' => esc_attr($page_style),
				'thumb_crop' => false,
				'sidebar' => !in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth')),
				'content' => true
			)
		);

		//ob_start();

		//do_shortcode("[ids_poll id=1]");

		//$content .= ob_get_clean();

		$content = $content . "</div></div></div></div></div></body></html>";
		$content = preg_replace("/[\n\r\t]/", "", $content);
		$content =  preg_replace('/"/', "'", $content);
		$content =  preg_replace('/ >/', ">", $content);
		//$content =  preg_replace('/\//', "ids_slash_html", $content);
		$content =	str_replace("\/", "", $content);
		//$content =  str_replace('ids_slash_html', '/', $content);

		$content =  stripslashes($content);
	}

	$resposne = array(
		"id" => $page_id,
		"date" => get_post($page_id)->post_date,
		"date_updated" => get_post($page_id)->post_modified,
		"title" => get_post($page_id)->post_title,
		"content" => $content
	);

	return $resposne;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/statistics', array(
		'methods' => 'GET',
		'callback' => 'ids_statistics_page',
	));
});

// API Statistics Page End.


// API Polls Page Start.

function ids_polls_page(WP_REST_Request $request)
{
	$language =	$request["lang"];

	$page_id = 3410;

	switch_to_locale($language);

	$ids_wp_head_heredoc = 'ids_wp_head_heredoc';
	$ids_polls_css = 'ids_get_polls_css';

	$ids_body_class_heredoc = 'ids_rest_api_page_body_class';

	$html_structure = <<<EOF
	<html lang="{$language}">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			{$ids_wp_head_heredoc(-1)}
		</head>
		<body {$ids_body_class_heredoc($page_id)}>
			<div class="main_content">
				<div class="boxedWrap">
					<div class="mainWrap without_sidebar">	
						<div class="main" role="main">
							<div class="content">
	EOF;



	$content = $html_structure;
	$content .= ob_get_clean();

	ob_start();

	do_shortcode("[ids_poll id=1]");

	$content .= ob_get_clean();

	$content = $content . "</div></div></div></div></div></body></html>";
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	//$content =  preg_replace('/\//', "ids_slash_html", $content);
	$content =	str_replace("\/", "", $content);
	//$content =  str_replace('ids_slash_html', '/', $content);

	$content =  stripslashes($content);

	$resposne = array(
		"id" => $page_id,
		"date" => get_post($page_id)->post_date,
		"date_updated" => get_post($page_id)->post_modified,
		"title" => get_post($page_id)->post_title,
		"content" => $content
	);

	return $resposne;
}


add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/polls', array(
		'methods' => 'GET',
		'callback' => 'ids_polls_page',
	));
});

// API Polls Page End.


// API Map Page Start.

function ids_map_page_head()
{
	$template_path = get_template_directory();
	$page_templates_dir = "$template_path/page-templates";

	$content = file_get_contents("$page_templates_dir/map-files/head.html");
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	$content =  stripslashes($content);

	return $content;
}

function ids_map_html(WP_REST_Request $request)
{
	$language_code = $request["lang"] == "ar" ? "ar" : "en";

	$page_id = 2505;

	switch_to_locale($language_code);

	$ids_wp_head_heredoc = 'ids_map_page_head';
	$ids_polls_css = 'ids_get_polls_css';

	$ids_body_class_heredoc = 'ids_rest_api_page_body_class';

	$html_structure = <<<EOF
	<html lang="{$language_code}">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			{$ids_wp_head_heredoc()}
		</head>
		<body {$ids_body_class_heredoc($page_id)}>
			<div class="main_content">
				<div class="boxedWrap">
					<div class="mainWrap without_sidebar">	
						<div class="main" role="main">
							<div class="content">
	EOF;



	$content = $html_structure;
	$content .= ob_get_clean();

	ob_start();


	// Map Code Start.

	$template_path = get_template_directory();
	$page_templates_dir = "$template_path/page-templates";

	echo file_get_contents("$page_templates_dir/map-files/html.php");


	// Map Code End.

	$content .= ob_get_clean();

	$content = $content . "</div></div></div></div></div></body></html>";
	$content = preg_replace("/[\n\r\t]/", "", $content);
	$content =  preg_replace('/"/', "'", $content);
	$content =  preg_replace('/ >/', ">", $content);
	//$content =  preg_replace('/\//', "ids_slash_html", $content);
	$content =	str_replace("\/", "", $content);
	//$content =  str_replace('ids_slash_html', '/', $content);

	$content =  stripslashes($content);

	$resposne = array(
		"id" => $page_id,
		"date" => get_post($page_id)->post_date,
		"date_updated" => get_post($page_id)->post_modified,
		"title" => get_post($page_id)->post_title,
		"content" => $content
	);

	return $resposne;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/makhateer-map', array(
		'methods' => 'GET',
		'callback' => 'ids_map_html',
	));
});

// API Map Page End.

// Send Reset Password Link API Start.

function ids_send_reset_password_link(WP_REST_Request $request)
{

	$parameters = json_decode($request->get_body(), true);

	if (!$parameters) {
		$parameters = $_POST;
	}

	$lang = $parameters["lang"];

	$user_login = $parameters["user_login"];

	$response = array();

	global $wpdb, $wp_hasher;

	$user_login = trim(sanitize_text_field($user_login));

	if (!$user_login) {
		$response["result"] = "0";
		$response["message"] = $lang == "ar" ? "اسم المستخدم أوالايميل فارغ!" : "Username or email is empty!";
	}

	$user = get_user_by("email", $user_login);

	if ($user) {
		$user_data = get_user_by("email", $user_login);
	} else if (get_user_by("login", $user_login)) {
		$user = get_user_by("login", $user_login);
		$user_data = get_user_by("login", $user_login);
	} else {
		$response["result"] = "0";
		$response["message"] = $lang == "ar" ? "لا يوجد حساب يحمل هذا الاسم أو الايميل" : "There is no account with that username or password!";
		return $response;
	}

	do_action('lostpassword_post');

	// Redefining user_login ensures that we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retrieve_password', $user_login);

	$allow = apply_filters('allow_password_reset', true, $user_data->ID);

	if (!$allow) {
		return false;
	} else if (is_wp_error($allow)) {
		return false;
	}

	$key = get_password_reset_key($user);
	/*do_action('retrieve_password_key', $user_login, $key);

	if (empty($wp_hasher)) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash(8, true);
	}
	$hashed = $wp_hasher->HashPassword($key);
	$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));*/

	$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
	$message .= network_home_url('/') . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";


	$page = get_page_by_path("reset-password");
	$page_id_current_language = pll_get_post($page->ID, get_locale());

	$message .= get_permalink($page_id_current_language) . "?action=rp&key=$key&login=" . rawurlencode($user_login) . "\r\n";

	//$message .=  network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

	if (is_multisite()) {
		$blogname = $GLOBALS['current_site']->site_name;
	} else {
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	}

	$title = sprintf(__('[%s] Password Reset'), $blogname);

	$title = apply_filters('retrieve_password_title', $title);
	$message = apply_filters('retrieve_password_message', $message, $key);

	if ($message && !wp_mail($user_email, $title, $message)) {
		$response["result"] = "0";
		$response["message"] = "The email could not be sent! Please try again.";
		return $response;
	}

	$response["result"] = "1";
	$response["message"] = $lang == "ar" ? "الرجاء التحقق من بريدك الالكتروني. لقد تم إرسال رابط لتغيير كلمة السر." : "Please check your email. A reset password link was delivered to you!";
	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/lost-password', array(
		'methods' => 'POST',
		'callback' => 'ids_send_reset_password_link',
	));
});

// Send Reset Password Link API End.

// Reset Password Link API Start.

function ids_reset_password_link(WP_REST_Request $request)
{

	$parameters = json_decode($request->get_body(), true);

	if (!$parameters) {
		$parameters = $_POST;
	}

	$lang = $parameters["lang"];

	$user_login = $parameters["login"];
	$key = $parameters["key"];
	$password = $parameters["password"];
	$confirm_password = $parameters["confirm_password"];

	$response = array();

	if ($password != $confirm_password) {
		$response["result"] = "0";
		$response["message"] = $lang == "ar" ? "كلمتي السر غير متطابقتين!" : "Passwords don't match!";
	}


	$user_login = trim(sanitize_text_field($user_login));

	if (empty($user_login)) {
		$response["result"] = "0";
		$response["message"] = $lang == "ar" ? "اسم المستخدم أواليميل فارغ!" : "Username or email is empty!";
	}

	$user = check_password_reset_key($key, $user_login);

	if (!is_wp_error($user) && $user->ID) {
		wp_set_password($password, $user->ID);

		$response["result"] = "1";
		$response["message"] = $lang == "ar" ? "تم تغيير كلمة المرور!" : "Password had been reset!";
		return $response;
	} else {
		$response["result"] = "0";
		$response["message"] = $lang == "ar" ? "انتهت صلاحية الرابط!" : "Link had been expired!";
		return $response;
	}
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/reset-password', array(
		'methods' => 'POST',
		'callback' => 'ids_reset_password_link',
	));
});

// Reset Password Link API End.


// WP API Mokhtareen Integration Start.

// API Save Profile Start.

function ids_handle_upload_user_avatar()
{

	$wordpress_upload_dir = wp_upload_dir();

	$image_file = $_FILES['avatar'];
	$new_file_path = $wordpress_upload_dir['basedir'] . '/' . $image_file['name'];

	$new_file_mime = mime_content_type($image_file['tmp_name']);

	if (empty($image_file))
		die('File is not selected.');

	if ($image_file['error'])
		die($image_file['error']);

	if ($image_file['size'] > wp_max_upload_size())
		die('It is too large than expected.');

	if (!in_array($new_file_mime, get_allowed_mime_types()))
		die('WordPress doesn\'t allow this type of uploads.');

	// looks like everything is OK
	if (move_uploaded_file($image_file['tmp_name'], $new_file_path)) {


		$upload_id = wp_insert_attachment(array(
			'guid'           => $new_file_path,
			'post_mime_type' => $new_file_mime,
			'post_title'     => preg_replace('/\.[^.]+$/', '', $image_file['name']),
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $new_file_path);

		// wp_generate_attachment_metadata() won't work if you do not include this file

		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate and save the attachment metas into the database
		wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

		return $upload_id;
	}
}

function ids_save_mokhtar_profile(WP_REST_Request $request)
{
	//file_put_contents(ABSPATH . "/logs.txt", print_r($_POST, true));

	// Converting the $_POST associative array into variables.
	$body = json_decode($request->get_body());

	$wp_mokhtar_id  = $body->id;

	//$attachment_id = ids_handle_upload_user_avatar();
	//update_user_meta($wp_mokhtar_id, 'user_avatar', $attachment_id);

	/*$update_name_result = wp_update_user(
		array(
			"ID" => $wp_mokhtar_id,
			"first_name" => $body->full_name
		)
	);*/

	//$update_phone_result = update_user_meta($wp_mokhtar_id, "phone", $body->phone);
	//$update_dob_result = update_user_meta($wp_mokhtar_id, "DOB", $body->dob);

	if ($body->password) {
		$update_password_result = wp_set_password($body->password, $wp_mokhtar_id);
	}

	return array(
		"result" => 1,
		"message" => "Updated successfully!"
	);

	if ($update_name_result && $update_phone_result && $update_dob_result) {

		//wp_send_json_success("Updated successfully!");
	} else {
		return array(
			"result" => 1,
			"message" => "Updated successfully!"
		);
		//wp_send_json_error("Error Updating!");
	}
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/profile', array(
		'methods' => 'POST',
		'callback' => 'ids_save_mokhtar_profile',
	));
});


function ids_ajax_save_mokhtar_profile()
{
	file_put_contents(ABSPATH . "/logs.txt", print_r($_POST, true));

	// Converting the $_POST associative array into variables.
	extract($_POST);

	if ($full_name) {

		/*$update_name_result = wp_update_user(
			array(
				"ID" => $wp_mokhtar_id,
				"first_name" => $full_name
			)
		);*/
	}

	/*if ($phone) {
		$update_phone_result = update_user_meta($wp_mokhtar_id, "phone", $phone);
	}

	if ($dob) {
		$update_dob_result = update_user_meta($wp_mokhtar_id, "DOB", $dob);
	}*/

	if ($password) {
		$update_password_result = wp_set_password($password, $wp_mokhtar_id);
	}

	wp_redirect(wp_get_referer());

	wp_die();
}


add_action("wp_ajax_ids_save_mokhtar_profile", "ids_ajax_save_mokhtar_profile");
add_action("wp_ajax_nopriv_ids_save_mokhtar_profile", "ids_ajax_save_mokhtar_profile");

// API Save Profile End.


// API Calculate End of Service Start.


function is_mokhtar_currently_in_period($period)
{
	$current_year = date("Y");

	return $current_year >= (int)$period["starting_year"] && $current_year <= (int)$period["ending_year"];
}

function ids_calculate_eos(WP_REST_Request $request)
{
	$parameters = json_decode($request->get_body(), true);

	$request_origin = "";

	if (!$parameters) {
		$parameters = $_POST;
	}

	$is_from_web = filter_var($parameters["from_web"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	$request_origin = 	$is_from_web ? "Web" : "Mobile";


	$language_code = $parameters["lang"];

	// Logging Request Start.


	$periods =  $parameters["periods"];
	$is_currently_mokhtar = filter_var($parameters["is_currently_mokhtar"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

	// The official lowest salary.
	$amount_per_month = 675000;

	$end_of_service_total = 0;

	// If user is currently Mokhtar, then we have to remove the current period from
	// the array if it exists, since Mokhtar may enter the current period where he
	// can't request an indemnity for the current period!

	if ($is_currently_mokhtar) {
		$periods = array_filter($periods, function($period)
{
	$current_year = date("Y");

	return $current_year >= (int)$period["starting_year"] && $current_year <= (int)$period["ending_year"];
}, ARRAY_FILTER_USE_BOTH);
	}

	usort($periods, function ($period_previous, $period_next) {
		return (int)$period_previous["ending_year"] > (int)$period_next["starting_year"];
	});

	$periods_number = count($periods);

	// Check if Mokhtar has completed 5 consecutive periods.
	$completed_5_consecutive_periods = true;

	if (count($periods) >= 5) {
		foreach (array_keys($periods) as $period_key) {
			if ($periods[$period_key + 1] && $periods[$period_key]["ending_year"] != $periods[$period_key + 1]["starting_year"]) {
				$completed_5_consecutive_periods = false;
			}
		}
	} else {
		$completed_5_consecutive_periods = false;
	}


	// This variable will be used to track iterations while looping on periods.
	$current_period_iteration = 0;

	foreach ($periods as $period_key => &$period_value) {

		$period_value["starting_year"] = (int)$period_value["starting_year"];
		$period_value["ending_year"] = (int)$period_value["ending_year"];

		// Periods before 2010 are not included in the calculation.
		if ($period_value["starting_year"] < 2010) {
			continue;
		}

		$current_period_iteration++;

		$working_years = 0;
		$period_value["is_complete"] = filter_var($period_value["is_complete"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

		if ($period_value["is_complete"]) {
			$working_years = 6;
		} else {
			// We have to calculate based on the worked months.
			$starting_date = DateTime::createFromFormat("Y-m-d", $period_value["starting_date"]);

			$ending_date = DateTime::createFromFormat("Y-m-d", $period_value["ending_date"]);

			// Calculate the total number of worked months.
			$difference_between_start_end = $starting_date->diff($ending_date);


			$working_years = (($difference_between_start_end->y * 12) + ($difference_between_start_end->m) + ($difference_between_start_end->d ? 1 : 1)) / 12;


			/*$starting_date->setDate($starting_date->format("Y"), $starting_date->format("m"), 1);

			$ending_date->setDate($ending_date->format("Y"), $ending_date->format("m"), 1);

			$starting_date_timestamp = $starting_date->getTimestamp();
			$ending_date_timestamp = $ending_date->getTimestamp();

			$dates_difference = abs($ending_date_timestamp - $starting_date_timestamp);

			$working_years = floor($dates_difference / (365 * 60 * 60 * 24));*/
		}

		if ($period_value["ending_year"] == 2016) {
			$period_value["total"] = $amount_per_month * $working_years * 2;
		} else {
			$period_value["total"] = $amount_per_month * $working_years * 3;
		}

		if ($completed_5_consecutive_periods && $current_period_iteration >= 5) {
			$period_value["total"] += ($period_value["total"] * 0.5);
		}

		if ($is_currently_mokhtar) {
			if ($periods_number == 1) {
				$period_value["total"] = $period_value["total"] * 0.25;
			} else if ($periods_number == 2) {
				$period_value["total"] = $period_value["total"] * 0.5;
			} else if ($periods_number == 3) {
				$period_value["total"] = $period_value["total"] * 0.75;
			}
		}

		$end_of_service_total += $period_value["total"];
	}

	$message = $language_code == "ar" ? "القيمة الكلية لتعويض نهاية الخدمة" : "Total amount of your severance pay is";

	$currency = $language_code == "ar" ? "ل.ل" : "L.L";

	$end_of_service_total = number_format($end_of_service_total, 0);

	if ($language_code == "ar") {
		$end_of_service_total = numeric_en_to_hindi($end_of_service_total);
	}

	$result = array("message" => "$message\n$end_of_service_total $currency");

	// This log will be used for future debugging...

	$request_log = array(
		"request_date" => date("d/m/Y") . " " . strtoupper(date("h:i:sa")) . " - From: $request_origin",
		"request_details" => array(
			"readable_form" => $parameters,
			"json_form" => json_encode($parameters)
		),
		"result_details" => array(
			"periods" => $periods,
			"date_difference" => $difference_between_start_end,
			"result" => $result
		)
	);

	file_put_contents(ABSPATH . "/ids-eos.txt", print_r($request_log, true), FILE_APPEND);

	// Logging Request End.


	return $result;
}

function numeric_en_to_hindi($number)
{
	return strtr($number, array('0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩'));
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/eos', array(
		'methods' => 'POST',
		'callback' => 'ids_calculate_eos',
	));
});

//add_action("wp_ajax_ids_calculate_eos", "ids_calculate_eos");
//add_action("wp_ajax_nopriv_ids_calculate_eos", "ids_calculate_eos");

// API Calculate End of Service End.


// API AJAX Services System Start.

function ids_handle_upload_mokhtar_service_file()
{

	file_put_contents(ABSPATH . "/logs.txt", print_r($_FILES, true));

	$wordpress_upload_dir = wp_upload_dir();

	$service_file = $_FILES['file'];
	$new_file_path = $wordpress_upload_dir['basedir'] . '/services_system' . '/' . $service_file['name'];

	$new_file_mime = mime_content_type($service_file['tmp_name']);

	if (empty($service_file))
		die('File is not selected.');

	if ($service_file['error'])
		die($service_file['error']);

	if ($service_file['size'] > wp_max_upload_size())
		die('It is too large than expected.');

	if (!in_array($new_file_mime, get_allowed_mime_types()))
		die('WordPress doesn\'t allow this type of uploads.');

	// looks like everything is OK
	if (move_uploaded_file($service_file['tmp_name'], $new_file_path)) {


		$upload_id = wp_insert_attachment(array(
			'guid'           => $new_file_path,
			'post_mime_type' => $new_file_mime,
			'post_title'     => preg_replace('/\.[^.]+$/', '', $service_file['name']),
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $new_file_path);

		// wp_generate_attachment_metadata() won't work if you do not include this file
		//require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate and save the attachment metas into the database
		//wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

		// Insert post of type: "Services"

		$post_args = array(
			"post_type" => "services",
			"post_title" => $_POST["title_ar"],
			"post_content" => $_POST["description_ar"],
			"post_status" => "publish"
		);

		$post_id = wp_insert_post($post_args);
		update_field("field_605319ce8b0db", $upload_id, $post_id);


		$post_args = array(
			"post_type" => "services",
			"post_title" => $_POST["title_en"],
			"post_content" => $_POST["description_en"],
			"post_status" => "publish"
		);

		$post_id_en = wp_insert_post($post_args);
		update_field("field_605319ce8b0db", $upload_id, $post_id_en);

		global $polylang;
		$polylang->model->set_post_language($post_id, 'ar');
		$polylang->model->set_post_language($post_id_en, 'en');

		$polylang->model->save_translations('post', $post_id, array('en' => $post_id_en));

		// Show the uploaded file in browser
		wp_redirect(wp_get_referer());
	}
	//$upload = wp_upload_bits($_FILES["ids_services_file_upload"]["name"], null, file_get_contents($_FILES["ids_services_file_upload"]["tmp_name"]));
	wp_die();
}

add_action("wp_ajax_ids_handle_upload_mokhtar_service_file", "ids_handle_upload_mokhtar_service_file");
add_action("wp_ajax_nopriv_ids_handle_upload_mokhtar_service_file", "ids_handle_upload_mokhtar_service_file");

// API AJAX Services System End.

// API Services System Start.

function ids_api_handle_upload_mokhtar_service_file()
{

	//file_put_contents(ABSPATH . "/logs.txt", print_r($_FILES, true));

	$wordpress_upload_dir = wp_upload_dir();

	$service_file = $_FILES['file'];
	$new_file_path = $wordpress_upload_dir['basedir'] . '/services_system' . '/' . $service_file['name'];

	$new_file_mime = mime_content_type($service_file['tmp_name']);

	if (empty($service_file))
		die('File is not selected.');

	if ($service_file['error'])
		die($service_file['error']);

	if ($service_file['size'] > wp_max_upload_size())
		die('It is too large than expected.');

	if (!in_array($new_file_mime, get_allowed_mime_types()))
		die('WordPress doesn\'t allow this type of uploads.');

	// looks like everything is OK
	if (move_uploaded_file($service_file['tmp_name'], $new_file_path)) {


		$upload_id = wp_insert_attachment(array(
			'guid'           => $new_file_path,
			'post_mime_type' => $new_file_mime,
			'post_title'     => preg_replace('/\.[^.]+$/', '', $service_file['name']),
			'post_content'   => '',
			'post_status'    => 'inherit'
		), $new_file_path);

		// wp_generate_attachment_metadata() won't work if you do not include this file
		//require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate and save the attachment metas into the database
		//wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

		// Insert post of type: "Services"

		$post_args = array(
			"post_type" => "services",
			"post_title" => $_POST["title_ar"],
			"post_content" => $_POST["description_ar"],
			"post_status" => "publish"
		);

		$post_id = wp_insert_post($post_args);
		update_field("field_605319ce8b0db", $upload_id, $post_id);


		$post_args = array(
			"post_type" => "services",
			"post_title" => $_POST["title_en"],
			"post_content" => $_POST["description_en"],
			"post_status" => "publish"
		);

		$post_id_en = wp_insert_post($post_args);
		update_field("field_605319ce8b0db", $upload_id, $post_id_en);

		global $polylang;
		$polylang->model->set_post_language($post_id, 'ar');
		$polylang->model->set_post_language($post_id_en, 'en');

		$polylang->model->save_translations('post', $post_id, array('en' => $post_id_en));

		return array("result" => 1);
	}
	//$upload = wp_upload_bits($_FILES["ids_services_file_upload"]["name"], null, file_get_contents($_FILES["ids_services_file_upload"]["tmp_name"]));
	return array("result" => 0);
}

function ids_services_system(WP_REST_Request $request)

{
	$args = array(
		'posts_per_page' => $request['per_page'],
		'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'lang' => $request['lang'],
		'post_type' => 'services'
	);

	$posts = get_posts($args);

	$response = array();

	foreach ($posts as $post) {
		$single_news_rest_object = new stdClass();

		$single_news_rest_object->id = $post->ID;
		$single_news_rest_object->title = $post->post_title;
		$single_news_rest_object->content = $post->post_content;
		$single_news_rest_object->date = $post->post_date;
		$single_news_rest_object->date_updated = $post->post_modified;

		// "Services" is a custom post type that have a custom field called "file".
		// get_field() is used to get a custom field value. This function is given
		// by the ACF plugin
		$file = (object)get_field('file', $post->ID);

		$single_news_rest_object->file = [
			"id" => $file->ID,
			"url" => $file->url,
			"icon" => $file->icon,
		];

		$response[] = $single_news_rest_object;
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/services', array(
		array(
			'methods' => 'GET',
			'callback' => 'ids_services_system'
		),
		array(
			'methods' => 'POST',
			'callback' => 'ids_api_handle_upload_mokhtar_service_file'
		)
	));
});

// API Services System End.

// API Authenticate User Start.

function ids_user_details(WP_User $user, $language)
{
	$user_details = array();

	$user_details["id"] = $user->ID;
	$user_details["username"] = $user->user_login;
	$user_details["name"] = $user->first_name;
	$user_details["phone"] = get_user_meta($user->ID, "phone", true);
	$user_details["dob"] = get_user_meta($user->ID, "DOB", true);
	$user_details["region"] = get_user_meta($user->ID, "region", true);

	$active_election = get_user_meta($user->ID, "ActiveElection", true);

	$active_election_years = array_map("intval", explode("-", $active_election));

	$is_current_mokhtar = 0;

	if (max($active_election_years) >= date("Y")) {
		$is_current_mokhtar = 1;
	}

	$user_details["currently_mokhtar"] = $is_current_mokhtar;

	global $wpdb;

	$mohafaza_id = get_user_meta($user->ID, "MohafazaID", true);
	$query = "SELECT id, name_$language from {$wpdb->prefix}ids_mohafazat WHERE id=$mohafaza_id";
	$mohafazat_ids_query_results = $wpdb->get_results($query, ARRAY_N)[0];

	$user_details["mohafaza_id"] = (int)$mohafazat_ids_query_results[0];
	$user_details["mohafaza"] = $mohafazat_ids_query_results[1];

	$kaza_id = get_user_meta($user->ID, "KazaID", true);

	$query = "SELECT id, name_$language from {$wpdb->prefix}ids_kazat WHERE id=$kaza_id";
	$kazat_ids_query_results = $wpdb->get_results($query, ARRAY_N)[0];

	$user_details["kaza_id"] = (int)$kazat_ids_query_results[0];
	$user_details["kaza"] = $kazat_ids_query_results[1];
	$user_details["is_member"] = (int)get_user_meta($user->ID, "MemberOfBoardDirector", true);

	return $user_details;
}

function ids_authenticate_user(WP_REST_Request $request)
{
	$request_body = json_decode($request->get_body(), true);

	$username = $request_body["username"];
	$password = $request_body["password"];
	$language = $request_body["lang"];

	$response = array();

	if ($username && $password) {

		$user = wp_authenticate($username, $password);

		if (is_wp_error($user)) {
			$response["result"] = 0;
			$response["user"] = null;
		} else {
			$response["result"] = 1;
			$response["user"] = ids_user_details($user, $language);
		}
	}

	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/auth', array(
		'methods' => 'POST',
		'callback' => 'ids_authenticate_user',
	));
});

// API Authenticate User End.


// API Track Applications Start.

function ids_track_applications()
{
	// Fetch Mokhtar Applications From "Haceb" Start.

	$template_path = get_template_directory();
	$page_templates_dir = "$template_path/page-templates";

	require_once("$page_templates_dir/models/MokhtarResponse.php");
	require_once("$page_templates_dir/config/config.php");

	$config = new config();

	$username = $_GET["username"];

	if ($username != "") {
		$url = $config->APIUrl . "Applications?\$filter=Customer_No_%20eq%20'$username'";
	} else {
		$url = $config->APIUrl . "Applications";
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers[] = "Accept: application/json";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERPWD,  $config->UserName . ":" . $config->Password);

	$response = json_decode(curl_exec($ch));
	$info = curl_getinfo($ch);

	if ($info['http_code'] != 200) {
		/*print_r($info);
    echo "Return code is {$info} \n"
        . curl_error($ch);*/
	} else {
		$applications = $response->value;
		return $applications;
	}
	curl_close($ch);

	// Fetch Mokhtar Applications From "Haceb" End.

	return array();
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/apps', array(
		'methods' => 'GET',
		'callback' => 'ids_track_applications',
	));
});

// API Track Applications End.


// API Map Start.

function ids_map(WP_REST_Request $request)
{

	$language = $request["lang"];

	$response = array();

	global $wpdb;


	$query = "SELECT * from {$wpdb->prefix}ids_mohafazat";
	$mohafazat_ids_query_results = $wpdb->get_results($query);

	foreach ($mohafazat_ids_query_results as $mohafaza) {

		$query = "SELECT * from {$wpdb->prefix}ids_kazat WHERE mohafaza_id=" . $mohafaza->id;
		$kazat_ids_query_results = $wpdb->get_results($query);

		$kazat = array();

		foreach ($kazat_ids_query_results as $kaza) {
			$makhateer_count_query_results = $wpdb->get_row("SELECT COUNT(*) AS makhateer_count FROM $wpdb->usermeta WHERE (meta_key = 'KazaID' AND meta_value = " . $kaza->id . ")");


			$kazat[] = (object)[
				"id" => (int)$kaza->id,
				"name" => $kaza->{"name_" . $language},
				"count" => (int)$makhateer_count_query_results->makhateer_count
			];
		}

		$response["mohafazat"][] = (object)[
			"id" => (int)$mohafaza->id,
			"name" => $mohafaza->{"name_" . $language},
			"kazat" => $kazat
		];
	}

	// Filling Mohafazat Info End.



	return $response;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/map', array(
		'methods' => 'GET',
		'callback' => 'ids_map',
	));
});

// API Map End.


// API Makhateer Start.

function ids_filter_makhateer($mohafaza_id = "", $kaza_id = "", $search = "")
{
	$users_args =  array(
		'role' => 'Mokhtar',
		'number' =>       -1,
		'meta_query' => array(
			array(
				'key'     => 'ActiveElection',
				'value'   => "",
				'compare' => '!='
			)
		)
	);

	if ($mohafaza_id) {
		$users_args["meta_query"][] = array(
			'key' => 'MohafazaID',
			'value' => $mohafaza_id,
			'compare' => '='
		);
	}

	if ($kaza_id) {
		$users_args["meta_query"][] = array(
			'key' => 'KazaID',
			'value' => $kaza_id,
			'compare' => '='
		);
	}

	if ($search) {
		$users_args["meta_query"][] = array(
			'key'     => 'first_name',
			'value'   => $search,
			'compare' => 'LIKE'
		);
	}

	$users =  get_users($users_args);

	return $users;
}

function ids_makhateer_filtered(WP_REST_Request $request)
{
	$mohafaza_id = $request["mohafaza_id"];
	$kaza_id = $request["kaza_id"];
	$search = $request["search"];
	$language = $request["lang"];

	$users = array();

	if ($mohafaza_id || $kaza_id || $search) {

		$users_args =  array(
			'role' => 'Mokhtar',
			'number' =>       $request['per_page'],
			'offset'         => $request['page'] ? $request['per_page'] * ($request['page'] - 1) : 0,
			'meta_query' => array()
		);

		if ($mohafaza_id) {
			$users_args["meta_query"][] = array(
				'key' => 'MohafazaID',
				'value' => $mohafaza_id,
				'compare' => '='
			);
		}

		if ($kaza_id) {
			$users_args["meta_query"][] = array(
				'key' => 'KazaID',
				'value' => $kaza_id,
				'compare' => '='
			);
		}

		if ($search) {
			$users_args["meta_query"][] = array(
				'key'     => 'first_name',
				'value'   => $search,
				'compare' => 'LIKE'
			);
		}

		$users =  get_users($users_args);

		foreach ($users as &$user) {
			$user = ids_user_details($user, $language);
		}
	}

	return $users;
}

add_action('rest_api_init', function () {
	register_rest_route('api/v1', '/makhateer', array(
		'methods' => 'GET',
		'callback' => 'ids_makhateer_filtered',
	));
});

// API Makhateer End.


// WP API Mokhtareen Integration End.

// API Magic End.

// Mohafazat/Kazat Helper Functions Start.

function ids_get_mohafazat()
{
	global $wpdb;

	$query = "SELECT * from {$wpdb->prefix}ids_mohafazat";
	return $wpdb->get_results($query);
}

function ids_get_kazat()
{
	global $wpdb;

	$query = "SELECT * from {$wpdb->prefix}ids_kazat";
	return $wpdb->get_results($query);
}

// Mohafazat/Kazat Helper Functions End.


// Short Codes for Regions Start.

function ids_regions_governates_shortcode()
{
	?>
	<option value="بيروت">بيروت</option>
	<option value="جبل لبنان">جبل لبنان</option>
	<option value="الجنوب">الجنوب</option>
	<option value="النبطية">النبطية</option>
	<option value="البقاع">البقاع</option>
	<option value="الشمال">الشمال</option>
	<option value="بعلبك - الهرمل">بعلبك - الهرمل</option>
	<option value="عكار">عكار</option>
<?php
}

add_shortcode('ids_regions_governates', 'ids_regions_governates_shortcode');

// Short Codes for Regions End.

// Registering Statistics: Custom Post Type Start.

function ids_create_statistics_post_type()
{

	// Set UI labels for Custom Post Type
	$labels = array(
		'name' => _x('Statistics', 'Post Type General Name', 'classter'),
		'singular_name' => _x('Statistic', 'Post Type Singular Name', 'classter'),
		'menu_name' => __('Statistics', 'classter'),
		'parent_item_colon' => __('Parent Statistic', 'classter'),
		'all_items' => __('All Statistics', 'classter'),
		'view_item' => __('View Statistic', 'classter'),
		'add_new_item' => __('Add Statistic', 'classter'),
		'add_new' => __('Add Statistic', 'classter'),
		'edit_item' => __('Edit Statistic', 'classter'),
		'update_item' => __('Update Statistic', 'classter'),
		'search_items' => __('Search Statistic', 'classter'),
		'not_found' => __('Not Found', 'classter'),
		'not_found_in_trash' => __('Not found in Trash', 'classter'),
	);

	// Set other options for Custom Post Type

	$args = array(
		'label' => __('ids_statistics', 'classter'),
		'description' => __('Statistics', 'classter'),
		'labels' => $labels,
		// Features this CPT supports in Post Editor
		'supports' => array('title', 'editor', 'thumbnail', 'featured', 'page-attributes'),
		// You can associate this CPT with a taxonomy or custom taxonomy.
		//'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 15,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'menu_icon' => 'dashicons-chart-pie',
	);

	//remove_meta_box('shfs_all_post_meta', 'statistic', 'normal');

	// Registering your Custom Post Type
	register_post_type('statistic', $args);
}

add_action('init', 'ids_create_statistics_post_type');

function ids_remove_statisitics_metaboxes()
{
	remove_meta_box('shfs_all_post_meta', 'statistic', 'normal');
	remove_meta_box('pageparentdiv', 'statistic', 'side');
}

add_filter('add_meta_boxes', 'ids_remove_statisitics_metaboxes', 0);


function ids_remove_statisitics_revolution_slider_meta_boxes()
{
	remove_meta_box('mymetabox_revslider_0', 'statistic', 'normal');
}

add_action('do_meta_boxes', 'ids_remove_statisitics_revolution_slider_meta_boxes');

// Registering Statistics: Custom Post Type End.

// Adding "Mokhtar" Role Start.

add_role('Mokhtar', 'Mokhtareen');

// Adding "Mokhtar" Role End.


// Disable "Mokhtar" Menu For Non-Mokhtar Start.

function ids_exclude_menu_items($items, $menu, $args)
{
	// Iterate over the items to search and destroy
	$current_user =  wp_get_current_user();

	if (!$current_user || !in_array('Mokhtar', (array) $current_user->roles)) {
		foreach ($items as $key => $item) {
			if ($item->ID == 7473 || $item->ID == 7442) {
				unset($items[$key]);
			}
		}
	}

	if ($current_user && in_array('Mokhtar', (array) $current_user->roles)) {
		if (!(int)get_user_meta($current_user->ID, "MemberOfBoardDirector", true)) {
			foreach ($items as $key => $item) {
				if ($item->ID == 8096 || $item->ID == 8101) {
					unset($items[$key]);
				}
			}
		}
	}

	return $items;
}

if (!is_admin()) {
	add_filter('wp_get_nav_menu_items', 'ids_exclude_menu_items', 10, 3);
}

// Disable "Mokhtar" Menu For Non-Mokhtar End.


// Disable Source File Caching Start. 

function ids_remove_ver_css_js($src, $handle)
{
	$handles_with_version = ['style']; // <-- Adjust to your needs!

	if (strpos($src, 'ver=') && !in_array($handle, $handles_with_version, true))
		$src = remove_query_arg('ver', $src);

	return $src;
}

add_filter('style_loader_src',  'ids_remove_ver_css_js', 9999, 2);
add_filter('script_loader_src', 'ids_remove_ver_css_js', 9999, 2);

// Disable Source File Caching End. 

// Injecting the AJAX URL in the Front-End Start.

function ids_inject_ajax_url_front_end()
{

	echo '<script type="text/javascript">
           var ids_ajaxurl = "' . admin_url('admin-ajax.php') . '";
           var ids_apiURL = "' . get_site_url() . "/wp-json/api/v1" . '";
         </script>';
}

add_action('wp_head', 'ids_inject_ajax_url_front_end');

// Injecting the AJAX URL in the Front-End End.


// Handling Statisitics Cusotm Post Type AJAX Requests Start.

function ids_get_statistic_by_id()
{
	global $post;

	$post_id = $_POST["id"];

	if ($post_id) {
		$post_statistic = get_post($post_id);

		if ($post_statistic) {
			$post = $post_statistic;
			setup_postdata($post);

			// Enable shortcodes
			WPBMap::addAllMappedShortcodes();
			$rendered_content =  apply_filters('the_content', $post->post_content);
			wp_send_json_success($rendered_content);
		} else {
			wp_send_json_error();
		}
	} else {
		wp_send_json_error();
	}
}

// "wp_ajax_nopriv" is to allow unauthenticated requests (so that client can complete this AJAX 
// request even if he is not logged in).
add_action("wp_ajax_nopriv_ids_get_statistic_by_id", "ids_get_statistic_by_id");
add_action("wp_ajax_ids_get_statistic_by_id", "ids_get_statistic_by_id");

// Handling Statisitics Cusotm Post Type AJAX Requests End.

// Handling User Authentication Start.

function ids_authenticate_user_frontend()
{
	extract($_POST);

	$user = wp_authenticate($log, $pwd);

	if (is_wp_error($user)) {
		wp_redirect(remove_query_arg("authenticated", wp_get_referer()) . "?authenticated=false");
		wp_die();
	} else {
		wp_set_current_user($user->ID, $user->user_login);
		wp_set_auth_cookie($user->ID, $rememberme);

		session_start();

		if ($_SESSION['redirect_to']) {
			wp_redirect($_SESSION['redirect_to']);
			wp_die();
		} else {
			wp_redirect(get_site_url());
			wp_die();
		}
	}
}

add_action("wp_ajax_nopriv_ids_authenticate_user_frontend", "ids_authenticate_user_frontend");
add_action("wp_ajax_ids_authenticate_user_frontend", "ids_authenticate_user_frontend");

// Handling User Authentication End.

// Handling User Logout Start.

function ids_user_logout_frontend()
{
	wp_logout();

	wp_redirect(get_site_url());

	wp_die();
}

add_action("wp_ajax_nopriv_ids_user_logout_frontend", "ids_user_logout_frontend");
add_action("wp_ajax_ids_user_logout_frontend", "ids_user_logout_frontend");

// Handling User Logout End.


// Enable Dash Icons Front-End Start.

function ids_load_dashicons_front_end()
{
	wp_enqueue_style('dashicons');

	// Control Page View for Mobile Start.

	$template_path = get_template_directory_uri();
	$page_templates_dir = "$template_path/page-templates";


	if (isset($_GET["is_mobile"])) {

		/*$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$url_parts = explode("/", $current_url);

		$page_slug = $url_parts[count($url_parts) - 2];

		$page = get_page_by_path($page_slug);*/

		//$page_id = pll_get_post($page->ID, $_GET["lang"]);

		//if (!$_GET["is_redirected"]) {
		//	wp_redirect(site_url() . "?p=6092&is_mobile=true&is_redirected=true");
		//}

		wp_enqueue_style('ids-mobile-view', $page_templates_dir . '/assets/ids_mobile_view.css', array(), '');
	}
	// Control Page View for Mobile End.

	$current_page_id = get_the_ID();

	if ($current_page_id == 2512 || $current_page_id == 7087) {
		//wp_enqueue_style('ids-contact-form7', 'https://mokhtar-fund.gov.lb/wp-content/plugins/contact-form-7/includes/css/styles.css', array(), '');

		//wp_enqueue_script('ids-contact-form7', 'https://mokhtar-fund.gov.lb/wp-content/plugins/js/ids-contact-from7.js');
	}
}

add_action('wp_enqueue_scripts', 'ids_load_dashicons_front_end');

// Enable Dash Icons Front-End End.


// Prevent Makhateer From Accessing the WP Admin Start.


function ids_prevent_makhateer_from_accessign_wp_admin()
{

	/* AJAX check  */
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return;
	}


	// The following method is supposed to be working, but the "$wp->request" is empty!

	// global $wp;
	// $current_url = home_url(add_query_arg(array(), $wp->request));

	$current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// is_admin() is NOT safe. DON'T use it.
	// Instead check the user's role(s) manually.

	$user = wp_get_current_user();
	if (!in_array('administrator', (array) $user->roles) && in_array('Mokhtar', (array) $user->roles) && strpos($current_url, "wp-admin") && !strpos($current_url, "download_")) {
		wp_redirect(get_site_url());
	}
}

add_action('init', 'ids_prevent_makhateer_from_accessign_wp_admin');

// Prevent Makhateer From Accessing the WP Admin End.


// Hide Admin Bar From Makhateer Start.

function ids_is_mokhtar()
{
	$current_user = wp_get_current_user();

	return $current_user  && in_array('Mokhtar', (array) $current_user->roles);
}

function ids_is_only_mokhtar()
{
	$current_user = wp_get_current_user();

	return $current_user && !in_array('administrator', (array) $current_user->roles) && in_array('Mokhtar', (array) $current_user->roles);
}

if (ids_is_only_mokhtar()) {
	add_filter('show_admin_bar', '__return_false');
}

// Hide Admin Bar From Makhateer End.


function prefix_gettext_modifications($translation, $text, $domain)
{

	if (strpos(wp_get_referer(), "/en") !== false) {
		return $translation;
	}

	if ('wpcf7-recaptcha' === $domain && strpos($text, 'you are not a robot') !== false) {
		$translation = 'أثبت أنك لست روبوت';
	}

	return $translation;
}

add_filter('gettext', 'prefix_gettext_modifications', 20, 3);


// Redirect Mokhtar to Custom Lost Password Page Start.

function ids_mokhtar_login_failed_redirect($username)
{

	$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// This hook will fire if the wp_authenticate() fails to authenticate a user, so we have
	// to check if the request is issued from front-end or it is an API request.

	// If it is from front-end, we will redirect the user to the reset password page.
	// If it is an API request, we will do nothing!

	// The redirection to the "Lost Password" page will be only for Mokhtar.
	// This redirection will not apply for admins logging in through the wp-admin.

	if (strpos($current_url, "wp-admin") === false && strpos($current_url, "/wp-json/api") === false) {
		$page = get_page_by_path("login");
		$page_id_current_language = pll_get_post($page->ID, get_locale());

		wp_redirect(get_permalink($page_id_current_language) . "?authenticated=false");
	}
}

// This hook will fire if the wp_authenticate() fails to authenticate a user.
add_action('wp_login_failed', 'ids_mokhtar_login_failed_redirect');

// Redirect Mokhtar to Custom Lost Password Page End.


function ids_redirect_404_to_homepage()
{
	if (is_404()) :
		wp_safe_redirect(home_url('/'));
		exit;
	endif;
}

add_action('template_redirect', 'ids_redirect_404_to_homepage');


function ids_page_template_redirect()
{
	if (!wp_get_current_user()->ID && (is_page(6084) || is_page(2509))) {
	session_start();
		if(is_page(2509)){
	$_SESSION['redirect_to']='https://mokhtar-fund.gov.lb/electronic-services-ar/';
	}else if(is_page(6084)){
	    $_SESSION['redirect_to']='https://mokhtar-fund.gov.lb/en/electronic-services/';
	}
		$page = get_page_by_path("login");
		$page_id_current_language = pll_get_post($page->ID, get_locale());

		wp_redirect(get_permalink($page_id_current_language));
		exit();
	}
}

add_action('template_redirect', 'ids_page_template_redirect');


function ids_body_class($classes)
{
	if (ids_is_mokhtar() && ((int)get_user_meta(wp_get_current_user()->ID, "MemberOfBoardDirector", true))) {
		return array_merge($classes, array("ids-mokhtar-member"));
	}

	return $classes;
}

add_filter("body_class", "ids_body_class");
// add_filter("the_content", "break_text");
// function break_text($text){
// if(isset($_GET['post_type']) && $_GET['post_type']=='bids'){
//     $length = 190;
//     if(strlen($text)<$length+10) return $text;//don't cut if too short

//     $break_pos = strpos($text, ' ', $length);//find next space after desired length
//     $visible = substr($text, 0, $break_pos);
//     return balanceTags($visible) . "…";
// }
// } 



function save_notification($post_id)

{
    global $wpdb;


    $en = array(

        "subtitle" => $_POST['filter_en_subtitle'],

        "message" => $_POST['filter_en_message'],

    );



    $ar = array(

        "subtitle" => $_POST['filter_ar_subtitle'],

        "message" => $_POST['filter_ar_message'],

    );



    $filters = array(

        "mokhtar_id" => $_POST['filter_user_role'],

        "mohafaza_id" => $_POST['mohafaza_id'],

        "kaza_id" => $_POST['kaza_id'],

        "currently_mokhtar" => $_POST['only_current_makhateer']

    );


    if ($en["subtitle"]) {
        update_post_meta($post_id, 'filter_en_subtitle', $en["subtitle"]);
    }

    if ($en["message"]) {
        update_post_meta($post_id, 'filter_en_message', $en["message"]);


        global $wpdb;

        $wpdb->update($wpdb->posts, array("post_title" => $en["message"]), array("ID" => $post_id), array("%s"), array("%d"));
    }

    if ($ar["subtitle"]) {
        update_post_meta($post_id, 'filter_ar_subtitle', $ar["subtitle"]);
    }

    if ($ar["message"]) {
        update_post_meta($post_id, 'filter_ar_message', $ar["message"]);
    }

    if ($filters["mokhtar_id"]) {
        update_post_meta($post_id, 'filter_user_role', $filters["mokhtar_id"]);
    }

    global  $mohafazat;

    if ($filters["mohafaza_id"]) {

        $mohafaza_name = "";
        $mohafaza_id = "";

        foreach ($mohafazat as $mohafaza) {
            if ((int)$mohafaza->id == (int)$filters["mohafaza_id"]) {
                $mohafaza_name = $mohafaza->name_en;
                $mohafaza_id = $mohafaza->id;
            }
        }

        update_post_meta($post_id, 'filter_governate_id', $mohafaza_id);
        update_post_meta($post_id, 'filter_governate', $mohafaza_name);
    }


    global  $kazat;

    if ($filters["kaza_id"]) {
        $kaza_name = "";
        $kaza_id = "";

        foreach ($kazat as $kaza) {
            if ((int)$kaza->id == (int)$filters["kaza_id"]) {
                $kaza_name = $kaza->name_en;
                $kaza_id = $kaza->id;
            }
        }


        update_post_meta($post_id, 'filter_district_id', $kaza_id);
        update_post_meta($post_id, 'filter_district', $kaza_name);
    }

    if ($filters["currently_mokhtar"]) {
        update_post_meta($post_id, 'filter_only_current_makhateer', $filters["currently_mokhtar"]);
    }


    $date=wp_date('Y-m-d H:i:s');
    $filter_en_subtitle=$en["subtitle"];
    $filter_en_message= $en["message"];


    $filter_ar_subtitle=$ar["subtitle"];
    $filter_ar_message= $ar["message"];

    
    $filter_user_role=$filters["mokhtar_id"];
    $mohafaza_id=$filters["mohafaza_id"];
    $kaza_id=$filters["kaza_id"];
    $currently_mokhtar=$filters["currently_mokhtar"];

	if($filter_en_subtitle!='' && $filter_en_message!='' && $filter_ar_subtitle!='' && $filter_ar_message!=''){
    $query="
    INSERT INTO wp_mobile_notifications (device_id, notification_id, title, body,date, token, device_type )
    SELECT     d.id,     $post_id,    '$filter_en_subtitle',    '$filter_en_message','$date' , d.device_token, d.device_type_id
    FROM wp_ids_devices AS d ";
    if(!empty($filter_user_role))
         $query.=" INNER JOIN wp_usermeta AS wpum  ON wpum.user_id=d.user_id ";
    
    $query .= " WHERE d.device_token IS NOT NULL
    AND d.device_token != ''
    AND d.lang = 'en'
    ";
    
    if(!empty($mohafaza_id)){
    $query.=" AND d.mohafaza_id = ".$mohafaza_id;
    }
    if(!empty($kaza_id)){
    $query.=" AND d.kaza_id = ".$_POST['kaza_id'];
    }
    if(!empty($currently_mokhtar) && $currently_mokhtar=="current"){
        $query1.=" AND d.currently_mokhtar = 1 ";
        }
        if(!empty($currently_mokhtar) && $currently_mokhtar=="previous"){
            $query1.=" AND d.currently_mokhtar = 0 ";
            }
    if(!empty($filter_user_role)){
    $query.=" AND wpum.meta_key='wp_capabilities'
    AND wpum.meta_value LIKE '%".$filter_user_role."%'";
    }
	$query.=" limit 1000";
    $query1="
    INSERT INTO wp_mobile_notifications (device_id, notification_id, title, body,date, token, device_type )
    SELECT     d.id,    $post_id,    '$filter_ar_subtitle',    '$filter_ar_message',    '$date' , d.device_token, d.device_type_id
    FROM wp_ids_devices AS d ";

    if(!empty($filter_user_role))
         $query1.=" INNER JOIN wp_usermeta AS wpum  ON wpum.user_id=d.user_id ";

    $query1.=" WHERE d.device_token IS NOT NULL
    AND d.device_token != ''
    AND d.lang = 'ar'
    ";
    
    if(!empty($mohafaza_id)){
    $query1.=" AND d.mohafaza_id = ".$mohafaza_id;
    }
    if(!empty($kaza_id)){
    $query1.=" AND d.kaza_id = ".$_POST['kaza_id'];
    }
    if(!empty($currently_mokhtar) && $currently_mokhtar=="current"){
    $query1.=" AND d.currently_mokhtar = 1 ";
    }
    if(!empty($currently_mokhtar) && $currently_mokhtar=="previous"){
        $query1.=" AND d.currently_mokhtar = 0 ";
        }
    if(!empty($filter_user_role)){
    $query1.=" AND wpum.meta_key='wp_capabilities'
    AND wpum.meta_value LIKE '%".$filter_user_role."%'";
    }
	$query1.=" limit 1000";

    $wpdb->query($query);
    $wpdb->query($query1);
    ids_send_notification_android();
    ids_send_notification_ios();
}
}


add_action('save_post_notification', 'save_notification');



function ids_send_notification_android(){
    global $wpdb;
    $sql="SELECT ID,notification_id, token, title, body FROM wp_mobile_notifications AS mn
            WHERE is_sent = 0 AND (title != '' AND body != '') AND token != ''
             AND device_type = 2
             ORDER BY notification_id, title, body";

    $array_device_tokens=[];
    $results=$wpdb->get_results($sql);
    $array_mobilenotification_ids = [];
    $i=0;
    foreach($results as $result)
    {
        $array_device_tokens[$i] = $result->token;
        array_push($array_mobilenotification_ids, $result->ID);
        $title=$result->title;
        $body=$result->body;
        $notification_id=$result->notification_id;

        // if($title=='')
        // {
        //     $title=$result->title;
        // }

        // if($body=='')
        // {
        //     $body=$result->body;
        // }

        // if($notification_id=='')
        // {
        //     $notification_id=$result->notification_id;
        // }
        // if($title==$result->title && $body==$result->body && $notification_id==$result->notification_id)
        // {
           
        // }
        // else
        // {
        //     break;
        // }
    }

    $arrNotification["body"] =$body;
    $arrNotification["title"] = $title;
    $arrNotification["sound"] = "default";
    $arrNotification["type"] = 1;
    $arrNotification["id"]=$notification_id;


//   push_notification($array_device_tokens,$arrNotification, $array_mobilenotification_ids, 2);
  push_notification_v1($array_device_tokens,$arrNotification, $array_mobilenotification_ids, 2);

             

}

function ids_send_notification_ios(){
    global $wpdb;
    $sql="SELECT ID,notification_id, token, title, body FROM wp_mobile_notifications AS mn
    WHERE is_sent = 0 AND (title != '' AND body != '') AND token != ''
    AND device_type = 1
    ORDER BY notification_id, title, body";
    
    $array_device_tokens=[];
    $array_mobilenotification_ids = [];
    $results=$wpdb->get_results($sql);
    $i = 0;
    foreach($results as $result)
    {
        $array_device_tokens[$i] = $result->token;
        array_push($array_mobilenotification_ids, $result->ID);
        $title=$result->title;
        $body=$result->body;
        $notification_id=$result->notification_id;
        $i++;
        // if($title==''){
        // $title=$result->title;
        // }
        // if($body==''){
        // $body=$result->body;
        // }
        // if($notification_id==''){
        // $notification_id=$result->notification_id;
        // }
        // if($title==$result->title && $body==$result->body && $notification_id==$result->notification_id){
        //     $array_device_tokens[$i] = $result->token;
        //     array_push($array_mobilenotification_ids, $result->ID);
        //     $i++;
        // }
        // else{
        //     break;
        // }
    }
    $arrNotification["body"] =$body;
    $arrNotification["title"] = $title;
    $arrNotification["sound"] = "default";
    $arrNotification["type"] = 1;
    $arrNotification["id"]=$notification_id;

//  push_notification($array_device_tokens,$arrNotification, $array_mobilenotification_ids, 1);             
 push_notification_v1($array_device_tokens,$arrNotification, $array_mobilenotification_ids, 1);             

}
function push_notification($registation_ids, $notification, $mobile_notification_ids, $device_type){
    global $wpdb;
    define( 'API_ACCESS_KEY', 'AAAA8c7ZfEA:APA91bHOcBoLfz5vxOg8pqmxutGcn7qXqnUrBxnYxGUvdSrJRDj10VW5DmIVQI-fHEfIBCxSr4BYmuDATPyO0vordefgwBOJ0H-idUsPPnOVkDDiI9LIfAocSDqcBlc4OdSJVM6Cjw1Y' ); // get API access key from Google/Firebase API's Console
      $url = 'https://fcm.googleapis.com/fcm/send';
      if($device_type == 2){
          //android
            $fields = array(
                'registration_ids' => $registation_ids,
                'data' => $notification
            );
      } elseif($device_type == 1) {
            $fields = array(
                'registration_ids' => $registation_ids,
                'notification' => $notification
            );
      }
      // Firebase API Key
      $headers = array('Authorization:key='.API_ACCESS_KEY,'Content-Type:application/json');
     // Open connection
      $ch = curl_init();
      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Disabling SSL Certificate support temporarly
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
      }
      curl_close($ch);

      //exit(print_r($result));
        
   $query=" UPDATE wp_mobile_notifications SET is_sent = 1 
   where notification_id = ".$notification["id"]." AND ID IN ('". implode ("','", $mobile_notification_ids) . "') \n";
    $wpdb->query($query);
  }


  add_action('save_post', 'my_save_post');
  function my_save_post($post_id) {
      global $post;
                      $post_type=get_post_type($post);
                      if($post_type=='notification' && (empty($_POST['filter_en_subtitle']) ||
                      	empty($_POST['filter_ar_subtitle']) ||
                          empty($_POST['filter_en_message']) ||
                          empty($_POST['filter_ar_message'])
                      	)){
                          // unhook this function to prevent indefinite loop
                          remove_action('save_post', 'my_save_post');
                          
                          // update the post to change post status
                          wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
                          
                          // re-hook this function again
                          add_action('save_post', 'my_save_post');
                      }
  }
  function wpb_sender_email( $original_email_address ) {
	return 'noreply@mokhtar-fund.gov.lb';
}

// Function to change sender name
function wpb_sender_name( $original_email_from ) {
	return 'Makhateer';
}

// Hooking up our functions to WordPress filters
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );


// This will suppress empty email errors when submitting the user form
add_action('user_profile_update_errors', 'my_user_profile_update_errors', 10, 3 );
function my_user_profile_update_errors($errors, $update, $user) {
$errors->remove('empty_email');
}

// This will remove javascript required validation for email input
// It will also remove the '(required)' text in the label
// Works for new user, user profile and edit user forms
add_action('user_new_form', 'my_user_new_form', 10, 1);
add_action('show_user_profile', 'my_user_new_form', 10, 1);
add_action('edit_user_profile', 'my_user_new_form', 10, 1);
function my_user_new_form($form_type) {
?>
<script type="text/javascript">
jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
// Uncheck send new user email option by default
<?php if (isset($form_type) && $form_type === 'add-new-user') : ?>
jQuery('#send_user_notification').removeAttr('checked');
<?php endif; ?>
</script>
<?php
}

add_action("wp_enqueue_scripts","ids_site_scripts");
function ids_site_scripts(){
  wp_enqueue_script( 'recaptchav3', 'https://www.recaptcha.net/recaptcha/api.js?render=6LetL-6Le3HfonAAAAABld_Gpj4rnBGvKjj7ODIuxbN-xn');
}

  function hide_menu() {

	$user = wp_get_current_user();
	if ( in_array( 'manage_requests', (array) $user->roles ) ) {

	      remove_menu_page( 'activity_log_page' );
		  remove_menu_page( 'index.php' );
		  remove_menu_page( 'wpcf7' );
		  remove_menu_page( 'edit.php?post_type=video_gallery' );
		  remove_menu_page( 'profile.php' );
		  remove_menu_page( 'revslider' );
		  remove_menu_page( 'rb_resume' );
		  remove_menu_page( 'edit.php?post_type=rb_resume' ); 
		  remove_menu_page( 'edit.php?post_type=diary' );
		  remove_menu_page( 'ids_poll_ml' );
		  remove_menu_page( 'wprps-premium' );
		  remove_menu_page( 'edit.php?post_type=statistic' );
		  remove_menu_page( 'edit.php?post_type=notification' ); 
		  remove_menu_page( 'edit.php' );
		  remove_menu_page( 'edit.php?post_type=rb_resume' ); 
		  remove_menu_page( 'rb_resume' ); 
		  remove_menu_page( 'edit.php?post_type=laws' );
		  remove_menu_page( 'edit.php?post_type=faq' );
		  remove_menu_page( 'edit.php?post_type=gs_team' ); 
		  remove_menu_page( 'edit.php?post_type=decrees' );
		  remove_menu_page( 'edit.php?post_type=decisions' );
		  remove_menu_page( 'edit.php?post_type=bids' );
		  remove_menu_page( 'edit.php?post_type=mokhtar' );
		  remove_menu_page( 'edit.php?post_type=templates' );
		  remove_menu_page( 'edit.php?post_type=services' );
		  remove_menu_page( 'edit.php?post_type=press_release' );
		  remove_menu_page( 'edit-comments.php' );
		  remove_menu_page( 'tools.php' );
		  remove_menu_page( 'wpseo_workouts' );
		  remove_menu_page( 'vc-welcome' );
		  remove_menu_page( 'wprps-about' );
		  remove_menu_page( 'post-new.php' );
		?>

		<?php
		}

		if ( in_array( 'manage_bids', (array) $user->roles ) ) {
			remove_menu_page( 'wprps-premium' );
		    remove_menu_page( 'index.php' );
			remove_menu_page( 'ids_poll_ml' );
			remove_menu_page( 'wprps-premium' );
			remove_menu_page( 'wpcf7' );
			remove_menu_page( 'vc-welcome' );
			remove_menu_page( 'profile.php' );
			remove_menu_page( 'edit.php?post_type=requests' );

			remove_menu_page( 'edit.php?post_type=statistic' );
			remove_menu_page( 'edit.php?post_type=notification' ); 
			remove_menu_page( 'edit.php?post_type=rb_resumes');
			remove_menu_page( 'edit.php?post_type=faq' );
			remove_menu_page( 'edit.php?post_type=gs_team' ); 
			remove_menu_page( 'edit.php?post_type=mokhtar' );
			remove_menu_page( 'edit.php?post_type=templates' );
			remove_menu_page( 'edit.php?post_type=services' );
			remove_menu_page( 'edit.php?post_type=press_release' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'tools.php' );
			remove_menu_page( 'wpseo_workouts' );
			remove_menu_page( 'vc-welcome' );
			remove_menu_page( 'wprps-premium' );

			?>

		   <?php
		  }
	
	}
	add_action('admin_head', 'hide_menu');


	function enqueue_scripts_and_styles_for_user_role() {
		$user = wp_get_current_user();
		if ( in_array( 'manage_requests', (array) $user->roles ) ) {

			wp_enqueue_style('ids-manage-requests-style', get_template_directory_uri() . '/css/manage-requests.css');
		}
		elseif ( in_array( 'manage_bids', (array) $user->roles ) ) {
			wp_enqueue_style('ids-manage-bids-style', get_template_directory_uri() . '/css/manage-bids.css');
		}
	  }

	  add_action('admin_enqueue_scripts', 'enqueue_scripts_and_styles_for_user_role');


      add_filter( 'xmlrpc_enabled', '__return_false' ); 


function allow_editor_full_builder_access() {
    $role = get_role('editor');
    if ($role) {
        $role->add_cap('edit_others_pages');
        $role->add_cap('edit_published_pages');
        $role->add_cap('publish_pages');
        $role->add_cap('edit_pages');
    }
}
add_action('init', 'allow_editor_full_builder_access');



?>