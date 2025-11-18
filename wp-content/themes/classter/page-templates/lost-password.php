<?php  /* Template Name: Lost Password Page */

$language_code  = get_locale();

$labels  = array();

if ($language_code == "ar") {
	$labels["header"] =  "أدخل بريدك الالكتروني أو اسم المستخدم الخاص بك";
	$labels["placeholder"] = "البريد الالكتروني أو اسم المستخدم الخاص بك";
	$labels["send"] = "أرسل";
} else {
	$labels["header"] = "Enter your email or username";
	$labels["placeholder"] = "Your email or username";
	$labels["send"] = "Send";
}

$template_path = get_template_directory_uri();
$page_templates_dir = "$template_path/page-templates";

wp_enqueue_script("ids-lost-password", "$page_templates_dir/Utilities/lost-password.js", array("jquery"), null,  true);
wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');

get_header();

?>
<style>
	form {
		max-width: 500px;
		margin-top: 15px;
		margin-bottom: 15px;
		margin-right: auto;
		margin-left: auto;
	}

	#send_reset_password_link {
		font-size: 14px;
		height: 50px;
		letter-spacing: 2px;
		line-height: 50px;
		padding: 0 30px;
		padding-bottom: 12px !important;
		text-transform: uppercase;
		display: block;
		text-decoration: none;
		background-color: #c42632;
		width: 65%;
		margin: auto;
		color: #ffffff;
		cursor: pointer;
		border-radius: 46px;
		font-weight: 300;
		text-align: center;
	}
</style>

<form>
	<div class="form-group">
		<label for="user_login">
			<?php
			echo $labels["header"];
			?>
		</label>
		<input type="text" name="user_login" id="user_login" placeholder="<?php echo $labels["placeholder"]; ?>">
	</div>

	<div class="form-group">
		<a id="send_reset_password_link" name="submit" class="lostpassword-button">
			<?php
			echo $labels["send"];
			?>
			<i id="ids-loader" style="display: none;" class="fa fa-spinner fa-spin"></i>

		</a>
	</div>
</form>


<?php

get_footer();

?>