<?php if (!is_user_logged_in() && get_theme_option('show_login') == 'yes') {
	// todo: Login form
	themerex_enqueue_script('form-login', themerex_get_file_url('/js/_form_login.js'), array(), null, true);

	$lost_password_page = get_page_by_path("lost-password");
	$lost_password_page_id_current_language = pll_get_post($lost_password_page->ID, get_locale());
	$lost_password_page_link = get_permalink($lost_password_page_id_current_language);

?>
	<div id="user-popUp" class="user-popUp mfp-with-anim mfp-hide">
		<div class="sc_tabs">
			<ul class="loginHeadTab">
				<?php
				$lang = get_locale();
				if ($lang == 'ar') {
				?>
					<li><a href="#loginForm" class="loginFormTab icon"><?php _e('تسجيل الدخول', 'themerex'); ?></a></li>
					<!--<li><a href="#registerForm" class="registerFormTab icon">
				<?php //_e('Create an Account', 'themerex'); 
				?>
				</a></li>-->
			</ul>

			<div id="loginForm" class="formItems loginFormBody">
				<div class="itemformLeft">
					<form action="<?php echo esc_url(wp_login_url()); ?>" method="post" name="login_form" class="formValid">
						<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
						<ul class="formList">
							<li class="icon formLogin"><input type="text" id="login" name="log" value="" placeholder="<?php _e('اسم المستخدم', 'themerex'); ?>"></li>
							<li class="icon formPass"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('كلمة السر', 'themerex'); ?>"><i class="fa fa-eye ids_login_toggle_password"></i></li>
							<li class="remember">
								<a href="<?php echo $lost_password_page_link; ?>" class="forgotPwd"><?php _e('هل نسيت كلمة السر ؟', 'themerex'); ?></a>
								<input type="checkbox" value="forever" id="rememberme" name="rememberme">
								<label for="rememberme"><?php _e('تذكرني', 'themerex'); ?></label>
							</li>
							<li><a href="#" class="sendEnter enter"><?php _e('تسجيل الدخول', 'themerex'); ?></a></li>
						</ul>
					</form>
				</div>
			<?php } else if ($lang == 'en_US') { ?>
				<li><a href="#loginForm" class="loginFormTab icon"><?php _e('Login', 'themerex'); ?></a></li>
				<!--<li><a href="#registerForm" class="registerFormTab icon">
				<?php //_e('Create an Account', 'themerex'); 
				?>
				</a></li>-->
				</ul>

				<div id="loginForm" class="formItems loginFormBody">
					<div class="itemformLeft">
						<form action="<?php echo esc_url(wp_login_url()); ?>" method="post" name="login_form" class="formValid">
							<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
							<ul class="formList">
								<li class="icon formLogin"><input type="text" id="login" name="log" value="" placeholder="<?php _e('Username', 'themerex'); ?>"></li>
								<li class="icon formPass"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('Password', 'themerex'); ?>"><i class="fa fa-eye ids_login_toggle_password"></i></li>
								<li class="remember">
									<a href="<?php echo $lost_password_page_link; ?>" class="forgotPwd"><?php _e('Forget password ?', 'themerex'); ?></a>
									<input type="checkbox" value="forever" id="rememberme" name="rememberme">
									<label for="rememberme"><?php _e('Remember me', 'themerex'); ?></label>
								</li>
								<li><a href="#" class="sendEnter enter"><?php _e('Login', 'themerex'); ?></a></li>
							</ul>
						</form>
					</div>
				<?php } ?>


				</div>



			</div> <!-- /.sc_tabs -->
		</div> <!-- /.user-popUp -->
	<?php } ?>