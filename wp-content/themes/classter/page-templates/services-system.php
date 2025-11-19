<?php  /* Template Name: Services System Page */

$language_code = get_locale();

$current_user = wp_get_current_user();

if (empty($current_user->ID) || !ids_is_mokhtar()) {
    session_start();

    $redirect_to = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['redirect_to'] = $redirect_to;

    $page = get_page_by_path("login");
    $page_id_current_language = pll_get_post($page->ID, get_locale());

    wp_redirect(get_permalink($page_id_current_language));
} else if (!(int)get_user_meta($current_user->ID, "MemberOfBoardDirector", true)) {
    $message_for_non_members = $language_code == "ar" ? "فقط أعضاء مجلس الإدارة يمكنهم الدخول إلى هذه الصفحة" : "Only board members can access this page";

    get_header();

?>

    <style>
        .content {
            height: 150px;
            line-height: 150px;
        }
    </style>

<?php

    echo $message_for_non_members;

    get_footer();

    return;
}

$template_path = get_template_directory_uri();

$page_templates_dir = "$template_path/page-templates";

wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');

wp_enqueue_style('ids-mokhtar-profile-profile-css', $page_templates_dir . '/assets/services_system.css', array(), '');
wp_enqueue_script("ids-services-js", "$page_templates_dir/Utilities/FileUploadServicesSystem.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-vars-js", "$page_templates_dir/assets/variables.js", array("jquery"), null,  true);


get_header();


// Labels for upload section.


$labels_ar = array();
$labels_en = array();


$labels_ar["section_title"]  = "شارك ملف جديد";
$labels_ar["upload"]  = "اختر ملف";
$labels_ar["download"]  = "تحميل";
$labels_ar["post_title"]  = "عنوان الملف";
$labels_ar["post_description"]  = "وصف الملف";
$labels_ar["submit"]  = "شارك";
$labels_ar["read_more"]  = "يد";

$labels_en["section_title"]  = "Share New File";
$labels_en["upload"]  = "Choose a file";
$labels_en["download"]  = "Download";
$labels_en["post_title"]  = "File Title";
$labels_en["post_description"]  = "File Description";
$labels_en["submit"]  = "Share";

$labels_en["read_more"]  = "Read More";

// Fetch all posts of type: "services".

?>

<div class="masonryWrap">
    <section class="masonry masonry-colums-4 inited" data-columns="4" style="position: relative">

        <?php
        $posts_args = array(
            "post_type" => "services",
            "posts_per_page" => -1
        );

        $posts  = get_posts($posts_args);

        foreach ($posts as $post) {

            $file = (object)get_field('file', $post->ID);

        ?>

            <!-- Same Layout of the "Requests" Post Type Page Start -->

            <article class="isotopeElement post_format_standard odd isotopeElementShow">
                <div class="isotopePadding bg_post">
                    <div class="post_wrap">
                        <div class="post_details">
                            <h4>
                                <?php
                                echo $post->post_title;
                                ?>
                            </h4>
                            <date>
                                <?php
                                echo $post->post_date;
                                ?>
                            </date>
                            <div class="post_format_wrap postStandard">
                                <?php
                                echo $post->post_content;
                                ?>
                            </div>
                        </div>

                        <div class="post_file">
                            <a href="<?php echo $file->url; ?>" target="_blank">
                                <?php
                                if ($language_code == "ar") {
                                    echo $labels_ar["download"];
                                } else {
                                    echo $labels_en["download"];
                                }
                                ?>
                        </div>
                        </a>
                    </div>
                </div>
            </article>



        <?php

        }

        ?>
    </section>

</div>

<div id="ids_upload_new_service_file">

    <h2>
        <?php
        if ($language_code == "ar") {
            echo $labels_ar["section_title"];
        } else {
            echo $labels_en["section_title"];
        }
        ?>
    </h2>

    <form id="ids_new_service_file_form" action="<?php echo admin_url("admin-ajax.php") ?>" method="POST" enctype="multipart/form-data">


        <div class="container">

            <div class="row">

                <div id="ar_file_details" class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="title_ar">
                            <?php
                            echo $labels_ar["post_title"];
                            ?>
                        </label>
                        <input type="text" required class="form-control" id="title_ar" name="title_ar" placeholder="<?php echo $labels_ar["post_title"];  ?>">
                    </div>

                    <div class="form-group">
                        <label for="description_ar">
                            <?php
                            echo $labels_ar["post_description"];
                            ?>
                        </label>
                        <textarea required class="form-control" id="description_ar" name="description_ar" rows="3" placeholder="<?php echo $labels_ar["post_description"]; ?>"></textarea>
                    </div>
                </div>

                <div id="en_file_details" class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="title_en">
                            <?php
                            echo $labels_en["post_title"];
                            ?>
                        </label>
                        <input required type="text" class="form-control" id="title_en" name="title_en" placeholder="<?php echo $labels_en["post_title"];  ?>">
                    </div>

                    <div class="form-group">
                        <label for="description_en">
                            <?php
                            echo $labels_en["post_description"];
                            ?>
                        </label>
                        <textarea required class="form-control" id="description_en" name="description_en" rows="3" placeholder="<?php echo $labels_en["post_description"]; ?>"></textarea>
                    </div>
                </div>
            </div>

            <div id="ids_row_file" class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="custom-file">
                        <input required hidden type="file" accept="application/pdf" id="file" name="file" />

                        <label class="custom-file-label" for="file"></label>

                        <!--<div class="invalid-feedback">Example invalid custom file feedback</div>-->
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="ids_row_submit" class="col-lg-12 col-sm-12">
                    <button type="submit" class="btn btn-primary mb-2">
                        <?php
                        if ($language_code == "ar") {
                            echo $labels_ar["submit"];
                        } else {
                            echo $labels_en["submit"];
                        }
                        ?>
                    </button>
                </div>
            </div>

        </div>

        <input hidden name="action" id="action" value="ids_handle_upload_mokhtar_service_file" />

    </form>

</div>

<!-- Same Layout of the "Requests" Post Type Page End -->

<script>
    jQuery(function($) {

        $("#ids_img_upload").click(function(event) {
            $("#file").click();
        });


        $('#file').on('change', function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        })

        function uploadFile() {

            input = $("#file")[0];

            console.log(input)
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var formData = new FormData();
                formData.append('file', file);
                formData.append('action', 'ids_handle_upload_mokhtar_service_file');

                // Fire the request.
                jQuery.ajax({
                    url: $AJAXIntegrationAPIUrl,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest') ?>');
                    },
                    data: formData
                }).success(function(response) {
                    jQuery('#file-url').text(response.url);
                    jQuery('#file-type').text(response.type);
                    jQuery('#ajax-response').show();
                    console.log(response);
                }).error(function(response) {
                    console.log(response);
                });

            }
        }

        /*$("#ids_new_service_file_form").submit(function(event) {
            event.preventDefault();

            uploadFile();
        });*/

        /*$("#file").on('change', function() {
            var fileInput = document.getElementById('file');

            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('file', file);


            let body = {
                "form_data": formData,
                "action": "ids_handle_upload_mokhtar_service_file"
            }

            $.post($IntegrationAPIUrl, body, function(response) {
                console.log(response);
            }).fail(function(error) {
                console.log(error);
            });


            $.ajax({
                type: 'POST',
                url: Url,
                data: new FormData,
                success: function(response) {}
            });
        });*/
    });
</script>

<?php

get_footer();
