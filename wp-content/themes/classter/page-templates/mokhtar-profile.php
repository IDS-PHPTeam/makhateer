<?php  /* Template Name: Mokhtar Profile Page */

$mokhtar = wp_get_current_user();

if (empty($mokhtar->ID) || !ids_is_mokhtar()) {
    session_start();

    $redirect_to = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['redirect_to'] = $redirect_to;

    $page = get_page_by_path("login");
    $page_id_current_language = pll_get_post($page->ID, get_locale());

    wp_redirect(get_permalink($page_id_current_language));
}

$template_path = get_template_directory_uri();
$page_templates_dir = "$template_path/page-templates";

if (get_locale() == "ar") {
    require get_template_directory() . "/page-templates/config/ar.php";
    wp_enqueue_script("ids-mokhtar-profile-resources-js", "$page_templates_dir/Utilities/Lang/resources_ar.js", array("jquery"), null,  true);
} else {
    require get_template_directory() . "/page-templates/config/en.php";
    wp_enqueue_script("ids-mokhtar-profile-resources-js", "$page_templates_dir/Utilities/Lang/resources_en.js", array("jquery"), null,  true);
}

wp_enqueue_script("ids-mokhtar-profile-js", "$page_templates_dir/Utilities/KarimMokhtarProfile.js", array("jquery"), null,  true);

wp_enqueue_style('ids-mokhtar-profile-mokhtareen-css', $template_path . '/css/mokhtareen/css/mokhtareen.css', array(), '');
wp_enqueue_style('ids-mokhtar-profile-responsive-css', $template_path . '/css/responsive.css', array(), '');

wp_enqueue_style('ids-mokhtar-profile-profile-css', $page_templates_dir . '/assets/KarimMokhtarProfile.css', array(), '');
wp_enqueue_style('ids-mokhtar-profile-dt-css', $page_templates_dir . '/assets/Plugins/datatables/bs-datatable.css', array(), '');
wp_enqueue_style('ids-mokhtar-profile-bs-dt-css', $page_templates_dir . '/assets/Plugins/datatables/dataTables.bootstrap.css', array(), '');

wp_enqueue_script("ids-mokhtar-profile-dt-js", "$page_templates_dir/assets/Plugins/datatables/bs-datatable.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-hrlper-js", "$page_templates_dir/assets/Helpers.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-vars-js", "$page_templates_dir/assets/variables.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetmin-js", "$page_templates_dir/assets/Plugins/sweetalert2/dist/sweetalert2.min.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetinit-js", "$page_templates_dir/assets/Plugins/sweetalert2/dist/sweetalert2.init.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetalert-js", "$page_templates_dir/assets/Plugins/sweetalert2/sweetalert2@7.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-profile-js", "$page_templates_dir/Utilities/MokhtarProfile.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-bs-dt-js", "$page_templates_dir/assets/Plugins/datatables/bs-datatable.js", array("jquery"), null,  true);

$active_election = get_user_meta($mokhtar->ID, "ActiveElection", true);

$active_election_years = array_map("intval", explode("-", $active_election));

$is_current_mokhtar = false;

if (max($active_election_years) >= date("Y")) {
    $is_current_mokhtar = true;
}

$language = get_locale() == "ar" ? "ar" : "en";

$labels = array();

if ($language == "ar") {
    $labels["full_name"] = "الإسم الثلاثي";
    $labels["dob"] = "تاريخ الولادة";
    $labels["phone"] = "رقم الهاتف";
    $labels["password"] = "كلمة السر";
    $labels["confirm_password"] = "تأكيد كلمة السر";
    $labels["id"] = "رقم التعريف";
    $labels["status"] = "الحالة";
    $labels["status_value"] = $is_current_mokhtar ?  "مختار حالي" : "مختار سايق";
    $labels["region"] = "المحلة";
    $labels["mohafaza"] = "المحافظة";
    $labels["kaza"] = "القضاء";
    $labels["cancel"] = "الغاء";
    $labels["save"] = "حفظ";
} else {
    $labels["full_name"] = "Full Name";
    $labels["dob"] =  "Date of Birth";
    $labels["phone"] = "Phone";
    $labels["password"] = "Password";
    $labels["confirm_password"] =  " Confirm Password";
    $labels["id"] =  "ID";
    $labels["status"] =  "Status";
    $labels["status_value"] = $is_current_mokhtar ?  "Current Mokhtar" : "Prev. Mokhtar";
    $labels["region"] = "Region";
    $labels["mohafaza"] =  "Mohafaza";
    $labels["kaza"] = "Kaza";
    $labels["cancel"] = "Cancel";
    $labels["save"] = "Save";
}

global $wpdb;

$query = "SELECT name_$language FROM " . $wpdb->prefix . "ids_mohafazat WHERE id=" . get_user_meta($mokhtar->ID, "MohafazaID", true);
$mohafaza_name = $wpdb->get_results($query, ARRAY_N)[0][0];


$query = "SELECT name_$language FROM " . $wpdb->prefix . "ids_kazat WHERE id=" . get_user_meta($mokhtar->ID, "KazaID", true);
$kaza_name = $wpdb->get_results($query, ARRAY_N)[0][0];


get_header();


?>

<div class="itemscope" itemscope itemtype="https://schema.org/Article">
    <section class="post post_format_standard postLeft no_margin post-2474 page type-page status-publish hentry">
        <article class="post_content">
            <h1 itemprop="name" class="post_title entry-title">أخبار وأحداث</h1>
            <div id="personal-profile">
                <div class="profile-pic-wrap">
                    <img class="profile-pic" src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/06/user-1.png">
                    <div class="p-image">
                        <i class="mokhtareen-plus upload-button" onclick="document.querySelector('.file-upload').click();"></i>
                        <input style="display: none" class="file-upload" type="file" accept="image/*" onchange="readURL(this);" />
                    </div>
                </div>
                <ul id="personal-profile-tab-links">
                    <li><a style="display: none" href="#personal-info-tab">معلومات شخصية</a></li>
                    <li style="display:none;"><a href="#requests-tab">الطلبات المقدمة</a></li>
                </ul>
                <div id="profile-meta">
                    <li><strong>
                            <?php
                            echo $labels["id"];
                            ?>
                        </strong><label id="txtCID"><?php echo $mokhtar->user_login; ?></label></li>
                    <li><strong>
                            <?php
                            echo $labels["status"];
                            ?>
                        </strong>
                        <?php
                        echo $labels["status_value"];
                        ?>
                    </li>
                    <li><strong>
                            <?php
                            echo $labels["region"];
                            ?>
                        </strong>
                        <?php
                        echo get_user_meta($mokhtar->ID, "region", true);
                        ?>
                    </li>
                    <li><strong>
                            <?php
                            echo $labels["mohafaza"];
                            ?>
                        </strong><?php echo $mohafaza_name; ?></li>
                    <li><strong>
                            <?php
                            echo $labels["kaza"];
                            ?>
                        </strong><?php echo $kaza_name; ?></li>
                </div>

                <form action="<?php echo admin_url('admin-ajax.php') ?>" method="POST">
                    <div class="tab-content" id="personal-info-tab">
                        <div class="form-input-wrap">
                            <label>
                                <?php
                                echo $labels["full_name"];
                                ?>
                            </label>
                            <div class="inner">
                                <input type="text" disabled id="txtFullName" name="full_name" value="<?php echo $mokhtar->user_firstname; ?>">
                            </div>
                        </div>
                        <div class="form-input-wrap">
                            <label>
                                <?php
                                echo $labels["dob"];
                                ?>
                            </label>
                            <div class="inner">
                                <input type="date" id="dtDateOfBirth" name="dob" disabled value="<?php echo get_user_meta($mokhtar->ID, "DOB", true); ?>">
                            </div>
                        </div>

                        <!--<div class="form-input-wrap">
                        <label>عنوان السكن :</label>
                        <div class="inner">
                            <input type="text" disabled id="txtAddress">
                            <i class="mokhtareen-edit" onclick="handleEditClick(this)"></i>
                        </div>
                    </div>-->
                        <div class="form-input-wrap">
                            <label>
                                <?php
                                echo $labels["phone"];
                                ?>
                            </label>
                            <div class="inner">
                                <input type="text" disabled id="txtPhone" name="phone" value="<?php echo get_user_meta($mokhtar->ID, "phone", true); ?>">
                            </div>
                        </div>
                        <div class="form-input-wrap">
                            <label>
                                <?php
                                echo $labels["password"];
                                ?>
                            </label>
                            <div class="inner">
                                <input id="password" type="password" name="password" disabled>

                                <i class="mokhtareen-edit" onclick="handleEditClick(this)"></i>
                            </div>
                        </div>
                        <div class="form-input-wrap">
                            <label>
                                <?php
                                echo $labels["confirm_password"];
                                ?>
                            </label>
                            <div class="inner">
                                <input type="password" id="confirm-password" disabled>
                                <i class="mokhtareen-edit" onclick="handleEditClick(this)"></i>
                            </div>
                        </div>
                        <div class="modal-footer" style="float:left;">
                            <button id="btnCancel" type="button" class="btn btn-secondary" data-dismiss="modal">
                                <?php
                                echo $labels["cancel"];
                                ?>
                            </button>
                            
                            <!-- For AJAX hook -->                         
                            <input hidden name="action" id="action" value="ids_save_mokhtar_profile" />
  
                            <button id="btnSave" name="wp_mokhtar_id" type="submit" value="<?php echo $mokhtar->ID; ?>" class="btn btn-primary">
                                <?php
                                echo $labels["save"];
                                ?>
                            </button>
                        </div>
                    </div>
                    <!--<div class="tab-content" id="requests-tab">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="content-box">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <div class="table-responsive" id="tableContainer">
                                                            <table id="dtApplications" class="table table-bordered table-striped metro-blue wd-100-pr">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="display: none">Id</th>
                                                                        <th style="width: 33%">Name</th>
                                                                        <th style="width: 33%">Wared Status</th>
                                                                        <th style="width: 33%">Application Number </th>
                                                                        <th style="width: 33%">Application Type</th>
                                                                        <th style="width: 1%">Incoming Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
        </article>
    </section>


</div>

<?php

get_footer();
