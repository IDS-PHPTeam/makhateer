<?php

global $THEMEREX_usermenu, $THEMEREX_usermenu_show;

if (empty($THEMEREX_usermenu) || !$THEMEREX_usermenu_show) {



	// todo: scrollbar

	themerex_enqueue_style('swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null);

	themerex_enqueue_style('swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null);



	themerex_enqueue_script('swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true);

	themerex_enqueue_script('swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true);

?>

	<ul id="usermenu" class="usermenu_list">

	<?php

}

	?>



	<?php if ($THEMEREX_usermenu_show && is_woocommerce_page() && get_custom_option('show_currency') == 'yes') { ?>

		<li class="usermenu_currency">

			<a href="#">$</a>

			<ul>

				<li><a href="#"><b>&#36;</b> <?php _e('Dollar', 'themerex'); ?></a></li>

				<li><a href="#"><b>&euro;</b> <?php _e('Euro', 'themerex'); ?></a></li>

				<li><a href="#"><b>&pound;</b> <?php _e('Pounds', 'themerex'); ?></a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if (get_custom_option('show_login') == 'yes') {



		// todo: Login form

		themerex_enqueue_script('form-login', themerex_get_file_url('/js/_form_login.js'), array(), null, true);



		// todo: magnific & pretty

		// magnific & pretty

		themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.css'), array(), null);

		themerex_enqueue_script('magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true);

		// Load PrettyPhoto if it selected in Theme Options

		if (get_theme_option('popup_engine') == 'pretty') {

			themerex_enqueue_style('prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null);

			themerex_enqueue_script('prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true);
		}


		$lang = get_locale();


		if (!is_user_logged_in()) {

	?>

			<?php
			if ($lang == 'ar') {
			?>
				<li class="usermenu_login"><a href="#user-popUp" class="user-popup-link"><i class="fa fa-user-plus" aria-hidden="true"></i><label><?php _e('تسجيل دخول المختارين', 'themerex'); ?></label></a></li>

			<?php } else if ($lang == 'en_US') { ?>

				<li class="usermenu_login"><a href="#user-popUp" class="user-popup-link"><i class="fa fa-user-plus" aria-hidden="true"></i><label><?php _e('Login', 'themerex'); ?></label></a></li>
			<?php } ?>

		<?php

		} else {
			//$current_user = wp_get_current_user();


		?>
			<!--<li class="usermenu_controlPanel">

				<a href=""><span><?php //echo $current_user->display_name; ?></span></a>
			</li>-->
			<?php
			if ($lang == 'ar') {
			?>
				<li class="usermenu_logout"><a href="<?php echo admin_url("admin-ajax.php"); ?>?action=ids_user_logout_frontend" class="user-popup-link"><i class="fa fa-user-times" aria-hidden="true"></i><label><?php _e('تسجيل الخروج', 'themerex'); ?></label></a></li>

			<?php } else if ($lang == 'en_US') { ?>

				<li class="usermenu_logout"><a href="<?php echo admin_url("admin-ajax.php"); ?>?action=ids_user_logout_frontend" class="user-popup-link"><i class="fa fa-user-plus" aria-hidden="true"></i><label><?php _e('Logout', 'themerex'); ?></label></a></li>
	<?php }
		}
	}

	?>



	<?php if (get_custom_option('show_languages') == 'yes' && function_exists('icl_get_languages') && $THEMEREX_usermenu_show) {

		$languages = icl_get_languages('skip_missing=1');

		if (!empty($languages)) {

			$lang_list = '';

			$lang_active = '';

			foreach ($languages as $lang) {

				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);

				if ($lang['active']) {

					$lang_active = $lang_title;
				}

				$lang_list .= "\n" . '<li><a rel="alternate" hreflang="' . $lang['language_code'] . '" href="' . apply_filters('WPML_filter_link', $lang['url'], $lang) . '">'

					. '<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'

					. balanceTags($lang_title)

					. '</a></li>';
			}

	?>

			<li class="usermenu_language">

				<a href="#"><span><?php echo balanceTags($lang_active); ?></span></a>

				<ul><?php echo balanceTags($lang_list); ?></ul>

			</li>

	<?php

		}
	}

	?>



	<?php

	if (get_custom_option('show_social_info') == 'yes' && $THEMEREX_usermenu_show) {	?>

		<li class="usermenu_socials copy_socials socPage">

			<?php

			$socials = get_theme_option('social_icons');

			foreach ($socials as $s) {

				if (empty($s['url'])) continue;

				$sn = basename($s['icon']);

			?>

				<a class="social_icons <?php echo esc_attr($sn); ?>" target="_blank" href="<?php echo esc_url($s['url']); ?>"></a>

			<?php

			}

			?>

		</li>

	<?php

	}

	?>

	</ul>