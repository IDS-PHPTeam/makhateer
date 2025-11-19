<?php

/**

 * The Header for our theme.

 */

global $THEMEREX_sidemenu, $THEMEREX_mainmenu, $THEMEREX_usermenu_show, $logo_text, $logo_icon, $logo_image, $logo_side, $logo_fixed;

// Init theme template - prepare global variables

// It will call in this place: in hook 'after_theme_setup' custom options are not yet ready!

themerex_init_template();

$body_style     = get_custom_option('body_style');

$single_style   = get_custom_option('single_style');

$slider_show	= get_custom_option('slider_show') == 'yes';

$slider_fullscreen = $slider_show && get_custom_option('slider_display') == 'fullscreen';

$show_top_panel = get_custom_option('show_top_panel');

$video_bg_show  = get_custom_option('show_video_bg') == 'yes' && (get_custom_option('video_bg_youtube_code') != '' || get_custom_option('video_bg_url') != '');

if ((!$slider_show || $body_style != 'boxed') && $show_top_panel == 'over') $show_top_panel = 'above';

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php if (floatval(get_bloginfo('version')) < "4.1") { ?>

		<title><?php wp_title('|', true, 'right'); ?></title>

	<?php } ?>

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php

	$favicon = get_custom_option('favicon');

	if (!$favicon) {

		$skin = themerex_escape_shell_cmd(get_custom_option('theme_skin'));

		if (file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/favicon.ico')))

			$favicon = themerex_get_file_url('/skins/' . $skin . '/images/favicon.ico');

		if (!$favicon && file_exists(themerex_get_file_dir('favicon.ico')))

			$favicon = themerex_get_file_url('favicon.ico');
	}

	if ($favicon) {	?>

		<link rel="icon" type="image/x-icon" href="<?php echo esc_attr($favicon); ?>" />

	<?php }	?>

	<!--[if lt IE 9]>

	<script src="<?php echo themerex_get_file_url('/js/html5.js'); ?>" type="text/javascript"></script>

	<![endif]-->

	<?php wp_head(); ?>

</head>



<body <?php

		$class = $style = '';

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



		body_class(
			'themerex_body ' . $body_style . ' top_panel_' . $show_top_panel . ' theme_skin_' . get_custom_option('theme_skin')

				. ($THEMEREX_sidemenu ? ' with_sidemenu sidemenu_left' : '')

				. ($video_bg_show ? ' video_bg' : '')

				. ($class != '' ? ' ' . $class : '')

		);

		if ($style != '') echo ' style="' . $style . '"';	?>>

	<?php echo get_custom_option('gtm_code'); ?>



	<?php do_action('before'); ?>



	<?php

	if (get_custom_option('menu_toc_home') == 'yes') echo do_shortcode('[trx_anchor id="toc_home" title="' . __('Home', 'themerex') . '" description="' . __('{Return to Home} - |navigate to home page of the site', 'themerex') . '" icon="icon-home" separator="yes" url="' . home_url() . '"]');

	if (get_custom_option('menu_toc_top') == 'yes') echo do_shortcode('[trx_anchor id="toc_top" title="' . __('To Top', 'themerex') . '" description="' . __('{Back to top} - |scroll to top of the page', 'themerex') . '" icon="icon-up" separator="yes"]');

	?>



	<?php

	if ($THEMEREX_sidemenu) {

	?>

		<div class="sidemenu_wrap swpLeftPos">

			<div class="sidemenu_button"></div>

			<div class="menuTranform">

				<div class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container" id="sidemenu_scroll">

					<div class="sc_scroll_wrapper swiper-wrapper">

						<div class="sc_scroll_slide swiper-slide">

							<div class="usermenu_area">

								<?php

								$THEMEREX_usermenu_show = false;

								get_template_part('/templates/page-part-user-panel');

								?>

							</div>



							<div class="logo<?php echo esc_attr($logo_text ? ' with_text' : ''); ?>">

								<a href="<?php echo home_url(); ?>"><?php echo ($logo_side ? '<img src="' . esc_url($logo_side) . '" class="logo_side" alt="">' : ''); ?><?php echo (esc_attr($logo_text) ? '<span class="logo_text">' . apply_filters('theme_logo_text', $logo_text, 'sidemenu') . '</span>' : ''); ?>

								</a>

							</div>



							<nav role="navigation" class="sidemenu_area">

								<?php echo balanceTags($THEMEREX_sidemenu); ?>

							</nav>

							<?php if (get_custom_option('show_search') == 'yes') { ?>

								<div class="menuSearch">

									<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">

										<?php

										$lang = get_locale();

										if ($lang == 'ar') {

										?>

											<input type="text" class="searchField" placeholder="<?php _e('البحث', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('البحث عن:', 'themerex'); ?>" />



										<?php } else if ($lang == 'en_US') { ?>



											<input type="text" class="searchField" placeholder="<?php _e('Search', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('Search For:', 'themerex'); ?>" />



										<?php } ?>

										<button type="submit" class="searchSubmit"></button>

									</form>

								</div>

							<?php } ?>

							<div class="sidemenu_close">x</div>

						</div>

					</div>

					<div id="sidemenu_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sidemenu_scroll_bar"></div>

				</div>

			</div>

		</div>

	<?php } ?>



	<!--[if lt IE 9]>

	<?php echo do_shortcode("[trx_infobox style='error']<div style=\"text-align:center;\">" . __("It looks like you're using an old version of Internet Explorer. For the best WordPress experience, please <a href=\"http://microsoft.com\" style=\"color:#191919\">update your browser</a> or learn how to <a href=\"http://browsehappy.com\" style=\"color:#222222\">browse happy</a>!", 'themerex') . "</div>[/trx_infobox]"); ?>

	<![endif]-->



	<div class="main_content">

		<?php if ($show_top_panel == 'over') {
			get_template_part('templates/page-part-slider');
		} ?>

		<div class="boxedWrap">

			<?php

			if (($header_custom_image = get_header_image()) != '') {

				$header_style = ' style = "background-image: url(' . $header_custom_image . ');"';
			} else {

				$header_style = '';
			}

			?>



			<?php

			if ($video_bg_show) {

				$youtube = get_custom_option('video_bg_youtube_code');

				$video = get_custom_option('video_bg_url');

				$overlay = get_custom_option('video_bg_overlay') == 'yes';

				if (!empty($youtube)) {

			?>

					<div class="videoBackground<?php echo esc_attr($overlay ? ' overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>

				<?php

				} else if (!empty($video)) {

					$info = pathinfo($video);

					$ext = !empty($info['extension']) ? $info['extension'] : 'src';

				?>

					<div class="videoBackground<?php echo esc_attr($overlay ? ' overlay' : ''); ?> videoBackgroundFullscreen"><video class="videoBackground" width="1280" height="720" data-width="1280" data-height="720" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>">

							<source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>">

							</source>

						</video></div>

				<?php

				}

				?>

				<div class="videoBackgroundOverlay">

				<?php

			}



			if ($slider_fullscreen) {

				?>

					<div class="fullScreenSlider">

					<?php

				}



				if ($show_top_panel == 'below') {
					get_template_part('templates/page-part-slider');
				}



				if ($show_top_panel != 'hide') {

					?>

						<header class="noFixMenu menu_<?php echo get_custom_option('menu_align'); ?> <?php echo get_custom_option('show_user_menu') != 'yes' ? 'without' : 'with'; ?>_user_menu">

							<div class="topWrapFixed"></div>

							<?php

							$lang = get_locale();

							$current_user = wp_get_current_user();

							if ($lang == 'ar') {

								if ($current_user->ID) {

							?>
									<div class="ids-slidingPanel slidingPanel rightSlidingPanel"><span class="usermenu_login_1">
											<form id="ids-logout-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="GET"><input type="hidden" name="action" value="ids_user_logout_frontend" /><a class="user-popup-link"><i class="fa fa-sign-out" aria-hidden="true"></i><label><?php _e('تسجيل الخروج', 'themerex'); ?></label></a></form>
									</div>

								<?php
								} else {

								?>

									<div class="slidingPanel rightSlidingPanel"><span class="usermenu_login_1"><a href="#user-popUp" class="user-popup-link"><i class="fa fa-user-plus" aria-hidden="true"></i><label><?php _e('تسجيل دخول المختارين', 'themerex'); ?></label></a></div>

								<?php }
							} else if ($lang == 'en_US') {

								if ($current_user->ID) {

								?>
									<div class="ids-slidingPanel slidingPanel rightSlidingPanel"><span class="usermenu_login_1">
											<form id="ids-logout-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="GET"><input type="hidden" name="action" value="ids_user_logout_frontend" /><a class="user-popup-link"><i class="fa fa-sign-out" aria-hidden="true"></i><label><?php _e('Logout', 'themerex'); ?></label></a></form>
									</div>

								<?php
								} else {

								?>
									<div class="slidingPanel rightSlidingPanel"><span class="usermenu_login_1"><a href="#user-popUp" class="user-popup-link"><i class="fa fa-user-plus" aria-hidden="true"></i><label><?php _e('Login', 'themerex'); ?></label></a></div>

							<?php }
							} ?>

							<script>
								jQuery(function($) {
									$(".ids-slidingPanel").click(function() {
										$("#ids-logout-form").submit();
									});

									$(".usermenu_logout").click(function() {
										
										window.location.href = "<?php echo wp_logout_url( home_url()); ?>";
									});
								});
							</script>


							<div class="topWrap <?php

												if (get_custom_option('menu_style') == 'line')	echo "styleShaded";

												else if (get_custom_option('menu_style') == 'line_gray') echo "styleShadedGray";

												else echo "styleFon"; ?>" <?php echo esc_attr($header_style); ?>>

								<?php if (get_custom_option('show_user_menu') == 'yes') { ?>

									<div class="usermenu_area">

										<div class="main">

											<div class="menuUsItem menuItemRight">

												<?php



												$THEMEREX_usermenu_show = true;

												get_template_part('templates/page-part-user-panel');

												?>

												<?php

												$lang = get_locale();

												if ($lang == 'ar') {

												?>

												<select name="billingform-country" id="billingform-country">
														<option class="option" selected="selected" value="">مواقع ذات صلة</option>
														<option class="option" value="http://www.interior.gov.lb/">رئاسة مجلس الوزراء</option>
														<option class="option" value="https://www.dgcs.gov.lb/">المديرية العامة للاحوال الشخصية</option>
														<option class="option" value="http://www.finance.gov.lb/ar-lb">وزارة المالية</option>
														<option class="option" value="https://www.moph.gov.lb/">وزارة الصحة</option>
														<option class="option" value="http://www.csb.gov.lb/">مجلس الخدمة المدنية</option>
														<option class="option" value="http://www.socialaffairs.gov.lb/MSADefault.aspx">وزارة الشؤون الاجتماعية</option>
														<option class="option" value="https://www.omsar.gov.lb/">وزارة التنمية الادارية</option>
														<option class="option" value="https://www.lebarmy.gov.lb/">الجيش اللبناني</option>
														<option class="option" value="https://www.general-security.gov.lb/ar">المديرية العامة للامن العام</option>
														<option class="option" value="http://www.state-security.gov.lb/">المديرية العامة لامن الدولة</option>
														<option class="option" value="https://www.isf.gov.lb/">المديرية العامة لقوى الامن الداخلي</option>
													</select>

												<?php } else if ($lang == 'en_US') { ?>

													<select name="billingform-country" id="billingform-country">

														<option class="option" selected="selected" value="">Related sites</option>
														<option class="option" value="http://www.interior.gov.lb/">Presidency of the Council of Ministers</option>
														<option class="option" value="https://www.dgcs.gov.lb/">General Directorate of Personal Status</option>
														<option class="option" value="http://www.finance.gov.lb/ar-lb">Ministry of Finance</option>
														<option class="option" value="https://www.moph.gov.lb/">Ministry of Health</option>
														<option class="option" value="http://www.csb.gov.lb/">Civil Service Council</option>
														<option class="option" value="http://www.socialaffairs.gov.lb/MSADefault.aspx">Ministry of Social Affairs</option>
														<option class="option" value="https://www.omsar.gov.lb/">Ministry of Administrative Development</option>
														<option class="option" value="https://www.lebarmy.gov.lb/">Lebanese army</option>
														<option class="option" value="https://www.general-security.gov.lb/ar">General Directorate of Public Security</option>
														<option class="option" value="http://www.state-security.gov.lb/">General Directorate of State Security</option>
														<option class="option" value="https://www.isf.gov.lb/">General Directorate of Internal Security Forces</option>

													</select>





												<?php } ?>



												<span><a href="https://mokhtar-fund.gov.lb/">AR</a>|<a href="https://mokhtar-fund.gov.lb/en/home">EN</a></span>

											</div>

										<?php } ?>



										</div>

										<?php if (get_custom_option('show_contact_info') == 'yes') { ?>



											<div class="menuUsItem menuItemLeft">

												<?php

												$lang = get_locale();

												if ($lang == 'ar') {

												?>

													<span id="topgov">

														<img id="toppic" src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/02/cedar.png"><span id="govname">الجمهورية اللبنانية </span>

														<i class="fa fa-calendar"></i><span id="demotop"></span>

													</span>

												<?php } else if ($lang == 'en_US') { ?>

													<img id="toppic" src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/02/cedar.png"><span id="govname">Lebanon Republic </span>

													<i class="fa fa-calendar"></i><span id="demotop"></span>



												<?php } ?>

											</div>

									</div>

								<?php } ?>



								<div class="mainmenu_area">

									<div class="main">

										<div class="logo logo_<?php echo get_custom_option('logo_align'); ?><?php echo esc_attr($logo_text ? ' with_text' : ''); ?>">

											<a href="<?php echo esc_url(home_url()); ?>"><?php echo (esc_attr($logo_text) ? '<span class="logo_text">' . apply_filters('theme_logo_text', $logo_text, 'mainmenu') . '</span>' : ''); ?><?php echo (esc_attr($logo_image) ? '<img src="' . esc_url($logo_image) . '" class="logo_main" alt=""><img src="' . esc_url($logo_fixed) . '" class="logo_fixed" alt="">' : ''); ?></a>

										</div>

										<div class="wrap_menu">

											<?php if (get_custom_option('menu_align') == 'center') { ?>

												<nav role="navigation" class="menuTopWrap topMenuStyleLine">

													<?php

													if (get_custom_option('main_menu_show') != 'hide' && $THEMEREX_mainmenu) {

														echo balanceTags($THEMEREX_mainmenu);
													} else if (get_custom_option('main_menu_show') != 'hide' && !$THEMEREX_mainmenu) {

														$THEMEREX_mainmenu = themerex_get_nav_menu();

														echo str_replace('menu_main', 'mainmenu', $THEMEREX_mainmenu);
													}

													?>

												</nav>



												<?php if (get_custom_option('show_search') == 'yes') { ?>

													<div class="search" title="<?php _e('Open/close search form', 'themerex'); ?>">

														<div class="searchForm">

															<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">

																<?php

																$lang = get_locale();

																if ($lang == 'ar') {

																?>

																	<input type="text" class="searchField" placeholder="<?php _e('بحث', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('البحث عن:', 'themerex'); ?>" />

																<?php } else if ($lang == 'en_US') { ?>

																	<input type="text" class="searchField" placeholder="<?php _e('Search', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('Search For:', 'themerex'); ?>" />



																<?php } ?>

																<button type="submit" class="searchSubmit" title="<?php _e('Start search', 'themerex'); ?>"><span class="icoSearch"></span></button>

															</form>

														</div>

														<div class="ajaxSearchResults"></div>

													</div>

												<?php } ?>



											<?php } ?>



											<?php if (function_exists('is_woocommerce') && (is_woocommerce_page() && get_custom_option('show_cart') == 'shop' || get_custom_option('show_cart') == 'always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { ?>

												<div class="cart">

													<a href="#" class="cart_button icon-shopping"></a>

													<ul class="sidebar_cart <?php echo get_custom_option('show_img_cart') == 'yes' ? 'img_yes' : 'img_no' ?>">
														<li>

															<?php

															do_action('before_sidebar');

															global $THEMEREX_CURRENT_SIDEBAR;

															$THEMEREX_CURRENT_SIDEBAR = 'cart';

															if (!dynamic_sidebar('sidebar-cart')) {

																the_widget('WC_Widget_Cart', 'title=&hide_if_empty=0');
															}

															?>

														</li>
													</ul>

												</div>

											<?php } ?>



											<?php if (get_custom_option('show_search') == 'yes' && get_custom_option('menu_align') != 'center') { ?>

												<div class="search" title="<?php _e('Open/close search form', 'themerex'); ?>">

													<div class="searchForm">

														<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">

															<input type="text" class="searchField" placeholder="<?php _e('Search', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('Search for:', 'themerex'); ?>" />

															<button type="submit" class="searchSubmit" title="<?php _e('Start search', 'themerex'); ?>"><span class="icoSearch"></span></button>

														</form>

													</div>

													<div class="ajaxSearchResults"></div>

												</div>

											<?php } ?>


											<a href="#" class="openResponsiveMenu"><?php echo get_locale() == "ar" ? "القائمة" : _e('Menu', 'themerex'); ?></a>

											<?php if (get_custom_option('menu_align') != 'center') { ?>

												<nav role="navigation" class="menuTopWrap topMenuStyleLine">

													<?php

													if (get_custom_option('main_menu_show') != 'hide' && $THEMEREX_mainmenu) {

														echo balanceTags($THEMEREX_mainmenu);
													} else if (get_custom_option('main_menu_show') != 'hide' && !$THEMEREX_mainmenu) {

														$THEMEREX_mainmenu = themerex_get_nav_menu();

														echo str_replace('menu_main', 'mainmenu', $THEMEREX_mainmenu);
													}

													?>

												</nav>

											<?php } ?>



										</div>

									</div>

								</div>

							</div>

						</header>

					<?php

				}

				if (in_array($show_top_panel, array('above', 'hide'))) {

					get_template_part('templates/page-part-slider');
				} else if ($slider_show && $show_top_panel == 'over') { ?>

						<div class="sliderHomeBulletsGap" style="height:<?php echo max(100, round(get_custom_option('slider_height') * 0.8)); ?>px;"></div>

					<?php

				}



				if ($slider_fullscreen) { ?>

					</div>

					<?php

				}



				$show_user_header = get_custom_option('show_user_header');

				if (!empty($show_user_header) && $show_user_header != 'none') {

					$user_header = themerex_strclear(get_custom_option('user_header_content'), 'p');

					if (!empty($user_header)) {

						$user_header = substituteAll($user_header);

					?>

						<div class="userHeaderSection <?php echo esc_attr($show_user_header); ?>">

							<?php

							echo balanceTags($user_header);

							?>

						</div>

				<?php

					}
				}

				?>



				<?php if (get_custom_option('show_top_page') == 'yes' && (function_exists('is_woocommerce') ? !is_product() : true)) { ?>



					<div id="topOfPage" class="topTabsWrap">

						<div class="main">

							<?php if (get_custom_option('show_breadcrumbs') == 'yes' && !isset($_GET["post_type"])) { ?>

								<div class="speedBar">

									<?php if (!is_404()) showBreadcrumbs(); ?>

								</div>

							<?php } else if (isset($_GET["post_type"])) {

								if ($lang == "en_US") {
									$ids_custom_post_type_label = "";

									if ($_GET["post_type"] == "mokhtar") {
										$ids_custom_post_type_label = "Telephone directory - Public sector";
									} else if ($_GET["post_type"] == "laws") {
										$ids_custom_post_type_label = "Laws";
									} else if ($_GET["post_type"] == "decisions") {
										$ids_custom_post_type_label = "Decisions";
									} else if ($_GET["post_type"] == "decrees") {
										$ids_custom_post_type_label = "Decrees";
									} else if ($_GET["post_type"] == "requests") {
										$ids_custom_post_type_label = "Application forms";
									} else if ($_GET["post_type"] == "bids") {
										$ids_custom_post_type_label = "Tenders and announcements";
									} else if ($_GET["post_type"] == "diary") {
										$ids_custom_post_type_label = "Memoirs and Circulars";
									} else if ($_GET["post_type"] == "templates") {
										$ids_custom_post_type_label = "Templates Issued from Mokhtar";
									}
								} else {
									if ($_GET["post_type"] == "mokhtar") {
										$ids_custom_post_type_label = "دليل الهاتف – القطاع العام";
									} else if ($_GET["post_type"] == "laws") {
										$ids_custom_post_type_label = "قوانين";
									} else if ($_GET["post_type"] == "decisions") {
										$ids_custom_post_type_label = "قرارات";
									} else if ($_GET["post_type"] == "decrees") {
										$ids_custom_post_type_label = "مراسيم";
									} else if ($_GET["post_type"] == "requests") {
										$ids_custom_post_type_label = "نماذج الطلبات";
									} else if ($_GET["post_type"] == "bids") {
										$ids_custom_post_type_label = "مناقصات واعلانات";
									} else if ($_GET["post_type"] == "diary") {
										$ids_custom_post_type_label = "مذكرات و تعاميم";
									} else if ($_GET["post_type"] == "templates") {
										$ids_custom_post_type_label = "نماذج الإفادات الصادرة عن المختار";
									}
								}

							?>

								<div class="speedBar">
									<a class="home" href="https://mokhtar-fund.gov.lb/<?php echo $lang == "ar" ? "" : "en"; ?>"><?php echo $lang == "ar" ? "الصفحة الرئيسية" : "Home"; ?></a><span class="breadcrumbs_delimiter"> / </span><span class="current"><?php echo $ids_custom_post_type_label; ?></span>
								</div>

							<?php
							}
							?>

							<?php if (get_custom_option('show_page_title') == 'yes') {

								if (get_custom_option('show_post_title') == 'yes' && is_singular()) {

							?>

									<div class="pageTitle h1"><?php echo esc_html(getBlogTitle()); ?></div>

									<?php } else {

									$lang = get_locale();

									if ($lang == 'en_US') {

										if (isset($_GET["post_type"])) {

											if ($_GET["post_type"] == "mokhtar") {

									?>

												<h1 class="pageTitle h1">

													Telephone directory - Public sector

												</h1>

											<?php

											} else if ($_GET["post_type"] == "laws") {

											?>

												<h1 class="pageTitle h1"> Laws</h1>

											<?php

											} else if ($_GET["post_type"] == "decisions") {

											?>

												<h1 class="pageTitle h1"> Decisions</h1>

											<?php

											} else if ($_GET["post_type"] == "decrees") {

											?>

												<h1 class="pageTitle h1">Decrees</h1>

											<?php

											} else if ($_GET["post_type"] == "requests") {

											?>

												<h1 class="pageTitle h1">Application forms</h1>

											<?php  } else if ($_GET["post_type"] == "bids") {



											?>

												<h1 class="pageTitle h1">Tenders and announcements</h1>



											<?php  } else if ($_GET["post_type"] == "diary") { ?>

												<h1 class="pageTitle h1">Memoirs and Circulars</h1>



											<?php } else if ($_GET["post_type"] == "templates") {

											?>

												<h1 class="pageTitle h1">

													Templates issued from mokhtar

												</h1>

											<?php
											} else {

											?>

												<h1 class="pageTitle h1"><?php echo esc_html(getBlogTitle()); ?></h1>

											<?php

											}
										}
									} else if ($lang == 'ar') {

										if (isset($_GET["post_type"])) {

											if ($_GET["post_type"] == "mokhtar") {

											?>

												<h1 class="pageTitle h1">

													دليل الهاتف – القطاع العام </h1>

											<?php

											} else if ($_GET["post_type"] == "laws") {

											?>

												<h1 class="pageTitle h1"> قوانين</h1>

											<?php

											} else if ($_GET["post_type"] == "decisions") {

											?>

												<h1 class="pageTitle h1"> قرارات</h1>

											<?php

											} else if ($_GET["post_type"] == "decrees") {

											?>

												<h1 class="pageTitle h1">مراسيم</h1>

											<?php

											} else if ($_GET["post_type"] == "requests") {

											?>

												<h1 class="pageTitle h1">نماذج الطلبات</h1>

											<?php  } else if ($_GET["post_type"] == "bids") {



											?>

												<h1 class="pageTitle h1">مناقصات واعلانات</h1>



											<?php  } else if ($_GET["post_type"] == "diary") { ?>

												<h1 class="pageTitle h1">مذكرات و تعاميم</h1>



											<?php } else if ($_GET["post_type"] == "templates") {

											?>

												<h1 class="pageTitle h1">

													نماذج الإفادات الصادرة عن المختار

												</h1>

											<?php
											} else {

											?>

												<h1 class="pageTitle h1"><?php echo esc_html(getBlogTitle()); ?></h1>

							<?php

											}
										}
									}
								}
							}

							?>





							<script>
								function fbs_click() {

									u = location.href;
									t = document.title;
									window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');

									return false;

								}

								function twitters_click() {

									u = location.href;
									t = document.title;
									window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');

									return false;

								}

								function wp_click() {

									u = location.href;
									t = document.title;
									window.open('https://wa.me/?text=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');

									return false;

								}
							</script>

							<?php

							$makhateer_pages_ids = array(7440, 7471, 7534, 7536, 7542, 7545, 7502, 7527, 2512, 7087);

							if (!in_array(get_the_ID(), $makhateer_pages_ids)) {
							?>
								<span id="socialmediabuttons"> <a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank" class="fb_share_link" id="fb"><img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/facebook-1.png" width="40px" /></a>

									<a rel="nofollow" href="https://twitter.com/intent/tweet?url=<;url>" onclick="return twitters_click()" target="_blank" class="fb_share_link" id="twitter"><img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/insta-1.png" width="40px" /></a>

									<a rel="nofollow" href="https://wa.me/?text=<;url>" onclick="return wp_click()" target="_blank" class="fb_share_link" id="whatsapp"><img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/whats-app-1.png" width="40px" /></a>

								</span>

							<?php } ?>

						</div>

					</div>

				<?php } ?>



				<div class="mainWrap <?php

										if (function_exists('is_woocommerce') ? is_product() : false) {

											echo "without_sidebar woocommerce_product";
										} else {

											echo getSidebarClass(get_custom_option('show_sidebar_main'));
										}

										?>">

					<?php

					if ($body_style != 'fullscreen' && (!is_singular() || $single_style != 'single-portfolio-fullscreen')) {

						startWrapper('<div class="main" role="main">');
					}

					startWrapper('<div class="content">');

					?>