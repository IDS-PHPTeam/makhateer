<?php  /* Template Name: Reset Password Page */

$language_code  = get_locale();

$labels  = array();

if ($language_code == "ar") {
    $labels["password_label"] = "أدخل كلمة سر جديدة";
    $labels["password_placeholder"] = "كلمة سر جديدة";
    $labels["confirm_password_label"] = "تأكيد كلمة السر";
    $labels["confirm_password_value"] = "تأكيد كلمة السر";
    $labels["reset"] = "تعيين";
} else {
    $labels["password_label"] = "Enter a new password";
    $labels["password_placeholder"] = "New password";
    $labels["confirm_password_label"] = "Confirm password";
    $labels["confirm_password_value"] = "Confirm password";
    $labels["reset"] = "Reset";
}

$template_path = get_template_directory_uri();
$page_templates_dir = "$template_path/page-templates";

wp_enqueue_script("ids-reset-password", "$page_templates_dir/Utilities/reset-password.js", array("jquery"), null,  true);
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

   form button[type="button"] {
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
        <label for="password">
            <?php
            echo $labels["password_label"];
            ?>
        </label>
        <input type="password" name="password" id="password" placeholder="<?php echo $labels["password_placeholder"]; ?>">
    </div>

    <div class="form-group">
        <label for="confirm_password">
            <?php
            echo $labels["confirm_password_label"];
            ?>
        </label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="<?php echo $labels["confirm_password_value"]; ?>">
    </div>

    <div class="form-group">
        <button type="button" id="reset_password_link" name="submit">
            <?php
            echo $labels["reset"];
            ?>
            <i id="ids-loader" style="display: none;" class="fa fa-spinner fa-spin"></i>

        </button>
    </div>
</form>


<?php

get_footer();

?>