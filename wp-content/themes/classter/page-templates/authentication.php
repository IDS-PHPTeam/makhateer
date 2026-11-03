<?php  /* Template Name: Authentication Page */


$template_path = get_template_directory_uri();
$page_templates_dir = "$template_path/page-templates";

wp_enqueue_style("ids-authentication", "$page_templates_dir/assets/authentication.css", array(), '');

$lost_password_page = get_page_by_path("lost-password");
$lost_password_page_link = home_url('/');
if ($lost_password_page && !empty($lost_password_page->ID)) {
	$lost_password_page_id_current_language = function_exists('pll_get_post')
		? pll_get_post($lost_password_page->ID, get_locale())
		: $lost_password_page->ID;
	if ($lost_password_page_id_current_language) {
		$lost_password_page_link = get_permalink($lost_password_page_id_current_language);
	}
}
$authenticated_value = isset($_GET['authenticated']) ? sanitize_text_field(wp_unslash($_GET['authenticated'])) : '';

get_header();

?>

<?php
$lang = get_locale();
if ($lang == 'ar') {
?>

    <div id="loginForm" class="formItems loginFormBody">
        <div>
            <form action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" name="login_form" class="formValid">
                <div class="ids-form-body">
                    <input type="hidden" name="authenticated" value="<?php echo esc_attr($authenticated_value); ?>" />
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
                    <input type="hidden" name="action" value="ids_authenticate_user_frontend" />

                    <ul class="formList">
                        <li class="icon formLogin"><input type="text" id="login" name="log" value="" placeholder="<?php _e('اسم المستخدم', 'themerex'); ?>"></li>
                        <li class="icon formPass"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('كلمة السر', 'themerex'); ?>"><i class="fa fa-eye ids_login_toggle_password"></i></li>
                        <li class="remember">
                            <a href="<?php echo esc_url($lost_password_page_link); ?>" class="forgotPwd"><?php _e('هل نسيت كلمة السر ؟', 'themerex'); ?></a>
                            <input type="checkbox" value="forever" id="rememberme" name="rememberme">
                            <label for="rememberme"><?php _e('تذكرني', 'themerex'); ?></label>
                        </li>
                        <li><a href="#" class="sendEnter enter"><?php _e('تسجيل الدخول', 'themerex'); ?></a></li>
                    </ul>
                </div>
            </form>
        </div>
    <?php } else if ($lang == 'en_US') { ?>

        <div id="loginForm" class="formItems loginFormBody">
            <div>
                <form action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" name="login_form" class="formValid">
                    <div class="ids-form-body">
                        <input type="hidden" name="authenticated" value="<?php echo esc_attr($authenticated_value); ?>" />
                        <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
                        <input type="hidden" name="action" value="ids_authenticate_user_frontend" />

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
                    </div>
                </form>
            </div>
        <?php } ?>


        </div>



    </div> <!-- /.sc_tabs -->

    <?php
    get_footer();

    ?>