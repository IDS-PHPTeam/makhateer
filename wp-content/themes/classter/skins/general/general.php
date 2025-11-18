<?php
/**
 * Classter skin file for theme.
 */

//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
add_filter('theme_skin_use_fonts', 'theme_skin_use_fonts_classter');
function theme_skin_use_fonts_classter($theme_fonts) {
	$theme_fonts['Open Sans'] = 1;
	return $theme_fonts;
}

// Add skin fonts in the main fonts list
add_filter('theme_skin_list_fonts', 'theme_skin_list_fonts_classter');
function theme_skin_list_fonts_classter($list) {
	//$list['Advent Pro'] = array('family'=>'sans-serif', 'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic&subset=latin,latin-ext,cyrillic,cyrillic-ext');
	if (!isset($list['Open Sans']))	$list['Open Sans'] = array('family'=>'sans-serif');
	return $list;
}


//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------

// Add skin stylesheets
add_action('theme_skin_add_stylesheets', 'theme_skin_add_stylesheets_classter');
function theme_skin_add_stylesheets_classter() {
	themerex_enqueue_style( 'theme-skin', themerex_get_file_url('/skins/general/general.css'), array('main-style'), null );
}

// Add skin responsive styles
add_action('theme_skin_add_responsive', 'theme_skin_add_responsive_classter');
function theme_skin_add_responsive_classter() {
	if (file_exists(themerex_get_file_dir('/skins/general/general-responsive.css'))) 
		themerex_enqueue_style( 'theme-skin-responsive', themerex_get_file_url('/skins/general/general-responsive.css'), array('theme-skin'), null );
}

// Add skin responsive inline styles
add_filter('theme_skin_add_responsive_inline', 'theme_skin_add_responsive_inline_classter');
function theme_skin_add_responsive_inline_classter($custom_style) {
	return $custom_style;	
}


//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
add_action('theme_skin_add_scripts', 'theme_skin_add_scripts_classter');
function theme_skin_add_scripts_classter() {
	if (file_exists(themerex_get_file_dir('/skins/general/general.js')))
		themerex_enqueue_script( 'theme-skin-script', themerex_get_file_url('/skins/general/general.js'), array('main-style'), null );
}

// Add skin scripts inline
add_action('theme_skin_add_scripts_inline', 'theme_skin_add_scripts_inline_classter');
function theme_skin_add_scripts_inline_classter() {
	?>

	<?php	
}


//------------------------------------------------------------------------------
// Get/Set skin's main (accent) theme color
//------------------------------------------------------------------------------

// Return main theme color (if not set in the theme options)
add_filter('theme_skin_get_theme_color', 'theme_skin_get_theme_color_classter', 10, 1);
function theme_skin_get_theme_color_classter($clr) {
	return empty($clr) ? '#f55858' : $clr;
}

add_filter('theme_skin_get_theme_color_1', 'theme_skin_get_theme_color_classter_1', 10, 1);
function theme_skin_get_theme_color_classter_1($clr) {
	return empty($clr) ? '#ffc455' : $clr;
}

add_filter('theme_skin_get_theme_color_2', 'theme_skin_get_theme_color_classter_2', 10, 1);
function theme_skin_get_theme_color_classter_2($clr) {
	return empty($clr) ? '#392071' : $clr;
}

add_filter('theme_skin_get_theme_color_3', 'theme_skin_get_theme_color_classter_3', 10, 1);
function theme_skin_get_theme_color_classter_3($clr) {
	return empty($clr) ? '#05d1d4' : $clr;
}

// Return main theme bg color
add_filter('theme_skin_get_theme_bgcolor', 'theme_skin_get_theme_bgcolor_classter', 10, 1);
function theme_skin_get_theme_bgcolor_classter($clr) {
	return '#ffffff';
}

// Add skin's specific theme colors in the custom styles
add_filter('theme_skin_set_theme_color', 'theme_skin_set_theme_color_classter', 10, 2);
function theme_skin_set_theme_color_classter($custom_style, $clr) {
$custom_style .= '
.theme_accent_bg
{ background:'.$clr.'; }
';
	return $custom_style;
}

?>