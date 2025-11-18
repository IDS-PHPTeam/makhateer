<?php  /* Template Name: End of Service Page */

/*$current_user = wp_get_current_user();


if (empty($current_user->ID) || !ids_is_mokhtar()) {
    session_start();

    $redirect_to = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['redirect_to'] = $redirect_to;

    $page = get_page_by_path("login");
    $page_id_current_language = pll_get_post($page->ID, $language_code);

    wp_redirect(get_permalink($page_id_current_language));
}*/

$language_code = get_locale();

$template_path = get_template_directory_uri();
$page_templates_dir = "$template_path/page-templates";

if ($language_code == "ar") {
    require get_template_directory() . "/page-templates/config/ar.php";
    wp_enqueue_script("ids-mokhtar-profile-resources-js", "$page_templates_dir/Utilities/Lang/resources_ar.js", array("jquery"), null,  true);
} else {
    require get_template_directory() . "/page-templates/config/en.php";
    wp_enqueue_script("ids-mokhtar-profile-resources-js", "$page_templates_dir/Utilities/Lang/resources_en.js", array("jquery"), null,  true);
}

wp_enqueue_style('ids-mokhtar-eos', 'https://mokhtar-fund.gov.lb/wp-content/plugins/js_composer/assets/css/js_composer.min.css', array(), '');


if ($language_code == "ar") {
    $labels["is_this_complete_period"] = "هل خدمت الولاية كاملة؟";
    $labels["complete_period_yes"] = "نعم";
    $labels["complete_period_no"] = "كلا";
    $labels["is_currently_mokhtar"] = "أنا مختار حاليّاً";
} else {
    $labels["is_this_complete_period"] = "Did you serve this period completely?";
    $labels["complete_period_yes"] = "Yes";
    $labels["complete_period_no"] = "No";
    $labels["is_currently_mokhtar"] = "I'm currently Mokhtar";
}


get_header();

?>


<style>
    #ids-loader {
        display: none;
    }

    form select {
        margin-top: 0px !important;
    }

    html:lang(en) select {
        direction: ltr;
    }

    form {
        margin-top: 30px;
        margin-bottom: 30px;

        background-color: #f5f7f9;
        padding: 20px;
    }

    #btnCalculate {
        margin-top: 30px;
        background-color: #c5262e;
        border: 1px solid #c5262e;
        border-radius: 30px;
    }

    label.ids-required__label::after {
        content: "*";
        color: red;
    }

    #form-row-submit__div {
        text-align: center;
    }

    @media (max-width: 767px) {
        .clearfix {
            margin-bottom: 30px;
        }

        .clearfix>div {
            margin-bottom: 20px;
        }
    }

    @media (min-width: 768px) {
        .form-row.ids-cancel.clearfix {
            margin-bottom: 20px;
        }

        .remove-term__div {
            margin-top: 5px;
        }

        html:lang(ar) .form-row.clearfix {
            display: flex;
        }
    }


    #period_0 .remove-term {
        display: none;
    }

    html:lang(ar) input {
        text-align: right;
    }

    .ids-date-disabled {
        background: #eeeeee;
        opacity: 0.7;
        pointer-events: none;
    }

    .ids-radio-period-is-complete {
        display: inline-block;
    }

    html:lang(ar) .ids-radio-period-is-complete:last-child {
        margin-right: 15px;
    }

    html:lang(en) .ids-radio-period-is-complete:last-child {
        margin-left: 15px;
    }

    html:lang(ar) #ids-currently-mokhtar label {
        display: inline-block !important;
        margin-right: 15px;
        margin-bottom: 30px;
    }

    html:lang(en) #ids-currently-mokhtar label {
        display: inline-block !important;
        margin-left: 15px;
        margin-bottom: 30px;
    }

    .ids-dates-row {
        margin-top: 15px;
    }

    #btnCalculate:focus {
        outline: none;
    }

    .swal2-popup .swal2-styled.swal2-confirm {
        background-color: #76121a !important;
        border-color: #76121a !important;
    }
</style>

<form>
    <div class="terms-wrap">

        <div id="ids-currently-mokhtar" class="form-row clearfix">
            <div class="col-12 col-md-12 col-sm-12 mb-3">
                <input type="checkbox" id="is_currently_mokhtar" name="is_currently_mokhtar" />

                <label for="is_currently_mokhtar" class="ids-required__label">
                    <?php
                    echo  $labels["is_currently_mokhtar"];
                    ?>
                </label>
            </div>
        </div>
        <div class="term" id="period_0">
            <div class="form-row clearfix">
                <div class="col-12 col-md-4 col-sm-4 mb-3">
                    <label for="periods" class="ids-required__label">
                        <?php
                        echo $labels['Electoralmandate'];
                        ?>
                    </label>

                    <select class="form-control term-dd periods" dir="rtl" required=""></select>
                </div>

                <div class="col-12 col-md-8 col-sm-8 mb-9">
                    <label class="ids-required__label">
                        <?php
                        echo  $labels["is_this_complete_period"];
                        ?>
                    </label>

                    <label class="ids-radio-period-is-complete">
                        <input type="radio" value="1" id="complete_period_yes_0" name="is_complete_0" checked="true" />
                        <?php
                        echo  $labels["complete_period_yes"];
                        ?>
                    </label>

                    <label class="ids-radio-period-is-complete">

                        <input type="radio" value="0" id="complete_period_no_0" name="is_complete_0" />
                        <?php
                        echo  $labels["complete_period_no"];
                        ?>
                    </label>
                </div>

            </div>

            <div class="form-row clearfix ids-dates-row" style="display: none;">
                <div class="col-12 col-md-4 col-sm-4 mb-3">
                    <label for="starting_date" class="ids-required__label">
                        <?php
                        echo $labels['ElectoralStartDate'];
                        ?>
                    </label>
                    <input type="date" id="starting_date" class="form-control starting_dates" name="StartDate">

                </div>
                <div class="col-12 col-md-4 col-sm-4 mb-3">
                    <label for="ending_date" class="ids-required__label">
                        <?php
                        echo $labels['ElectoralEndDate'];
                        ?>
                    </label>
                    <input type="date" id="ending_date" class="form-control ending_dates ids-date-disabled" name="EndDate">
                </div>
            </div>

            <div class="form-row ids-cancel clearfix">
                <div class="col-12 col-md-12 col-sm-12 mb-3 remove-term__div">
                    <button type="button" class="remove-term">
                        <?php
                        echo $labels['Cancel']; ?>
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="form-row">
        <div class="col-12 col-md-12 col-sm-12 mb-3">
            <button type="button" class="btn add-term-btn">
                <?php
                echo $labels['AddElectoral'];
                ?>
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>

    <div id="form-row-submit__div" class="form-row">
        <button type="button" id="btnCalculate" class="btn btn-primary">
            <?php
            echo $labels['Calculate'];
            ?>

            <i id="ids-loader" class="fa fa-spinner fa-spin"></i>
        </button>
    </div>
</form>

<!-- added by karim -->
<script>
    jQuery(function($) {

        periods_number = 1;
        selected_periods = [];

        $(".add-term-btn").click(function(event) {

            // Clone the initial row that contains info of the first period.

            $cloned_period = $("#period_0").clone();

            console.log("Will add...");
            $cloned_period.find(".periods:first option").each(function() {
                if (selected_periods.indexOf($(this).val()) > -1) {
                    $(this).remove();
                }
            });

            $cloned_period.attr("id", `period_${++periods_number}`);

            $cloned_period.find("input[name='is_complete_0']").attr("name", `is_complete_${periods_number}`);

            $cloned_period.find(".ids-dates-row").hide();

            $cloned_period.find(`input[name='is_complete_${periods_number}'][value="1"]`).prop("checked", "true");

            $(".terms-wrap").append($cloned_period);

        });


        $(document).on("click", ".remove-term", function() {

            if ($(this).closest(".term").attr("id") == "period_0") {
                return false;
            }

            value_to_remove = $(this).closest(".term").find(".periods:first").val();

            console.log("Valuen to remove" + value_to_remove)
            selected_periods = selected_periods.filter(function(value) {
                return value != value_to_remove;
            });

            $(this).closest(".term").remove();

        });


        // Control Minimum and Maximum Dates Based on Chosen Period Start.

        $(document).on("change", ".periods", function() {
            $period = $(this).find("option:selected");
            selected_periods.push($period.val());
            console.log($period);
            // Get the year range.
            $starting_year = $period.attr("start");
            $ending_year = $period.attr("end");

            console.log($starting_year);
            console.log($ending_year);


            // Generate minimum and maximum dates.
            $min_date = (new Date($starting_year, 0, 2)).toISOString().split("T")[0];
            $max_date = (new Date($ending_year, 11, 31)).toISOString().split("T")[0];

            console.log($min_date);
            console.log($max_date);

            // Limit date inputs range.
            $(this).closest(".term").find("#starting_date").attr("min", $min_date);
            $(this).closest(".term").find("#starting_date").attr("max", $max_date);

            $(this).closest(".term").find("#ending_date").attr("min", $min_date);
            $(this).closest(".term").find("#ending_date").attr("max", $max_date);
        });

        $language_code = $("html:first").attr("lang");

        var invalid_starting_date = $language_code == "ar" ? "!تاريخ بدء الولاية يجب أن يكون قبل انتهائها" : "Period starting date must be before the ending date!";
        var invalid_ending_date = $language_code == "ar" ? "!تاريخ انتهاء الولاية ينبغي أن يكون بعد بدايتها" : "Period ending date must be after the starting date!";

        $(document).on("change", ".starting_dates", function(event) {
            $starting_date = new Date($(this).val());

            if ($starting_date && String($starting_date.getFullYear()).length == 4) {

                // If the starting date is valid, then reset the minimum date of the ending date.
                $(this).closest(".term").find("#ending_date").attr("min", $starting_date.toISOString().split("T")[0]);

                $(this).closest(".term").find("#ending_date").removeClass("ids-date-disabled");

                $ending_date = new Date($(this).closest(".term").find("#ending_date").val());

                if ($ending_date && String($ending_date.getFullYear()).length == 4) {
                    if ($starting_date.getTime() >= $ending_date.getTime()) {
                        //$(this).val("");
                        alert(invalid_starting_date);
                    }
                }
            }
        });

        $(document).on("change", ".ending_dates", function(event) {
            $ending_date = new Date($(this).val());

            if ($ending_date && String($ending_date.getFullYear()).length == 4) {
                $starting_date = new Date($(this).closest(".term").find("#starting_date").val());

                if ($starting_date && String($starting_date.getFullYear()).length == 4) {
                    if ($ending_date.getTime() <= $starting_date.getTime()) {
                        $(this).val("");
                        alert(invalid_ending_date);
                    }
                }
            }
        });

        $(document).on("change", "input[name^='is_complete_']", function(event) {
            $(this).attr("id").indexOf("no") > -1 ? $(this).closest(".term").find(".ids-dates-row").show() : $(this).closest(".term").find(".ids-dates-row").hide();
        });



        // Control Minimum and Maximum Dates Based on Chosen Period End.


    });
</script>
<!-- /added by karim  -->

<!-- added by karim -->

<!-- /added by karim  -->

<?php

wp_enqueue_script("ids-mokhtar-profile-hrlper-js", "$page_templates_dir/assets/Helpers.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-vars-js", "$page_templates_dir/assets/variables.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetmin-js", "$page_templates_dir/assets/Plugins/sweetalert2/dist/sweetalert2.min.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetinit-js", "$page_templates_dir/assets/Plugins/sweetalert2/dist/sweetalert2.init.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetalert-js", "$page_templates_dir/assets/Plugins/sweetalert2/sweetalert2@7.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-sweetalert-js", "$page_templates_dir/assets/Plugins/sweetalert2/sweetalert2@7.js", array("jquery"), null,  true);
wp_enqueue_script("ids-mokhtar-profile-eos-js", "$page_templates_dir/Utilities/EndOfService.js", array("jquery"), null,  true);

get_footer();

?>