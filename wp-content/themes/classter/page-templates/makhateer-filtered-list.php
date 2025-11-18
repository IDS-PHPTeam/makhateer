<?php  /* Template Name: Mkhateer Filtered List Page */


$template_path = get_template_directory_uri();

$page_templates_dir = "$template_path/page-templates";

wp_enqueue_style('ids-makhateer-list', $page_templates_dir . '/assets/select2.min.css', array(), '');
wp_enqueue_script("ids-makahateer-list", "$page_templates_dir/assets/select2.min.js", array("jquery"), null,  true);


get_header();


$kaza_id = $_GET["kaza"];

/*$users_args =  array(
    'role' => 'Mokhtar',
    'number' =>       -1,
    'meta_query' => array(
        array(
            'key' => 'KazaID',
            'value' => $kaza_id,
            'compare' => '='
        )
    )
);*/

extract($_GET);

$users = ids_filter_makhateer($mohafaza_id, $kaza_id, $search);

$language_code = get_locale();

if ($language_code != "ar") {
    $language_code = "en";
}

$labels = array();

if ($language_code == "ar") {
    $labels = array(
        "name" => "الاسم",
        "phone" => "رقم الهاتف",
        "region" => "المحلة",
        "choose_mohafaza" => "اختر محافظة",
        "choose_kaza" => "اختر قضاء",
        "makhateer_count" => "عدد المختارين",
        "search" => "ابحث",
        "search_placeholder" => "ابحث عن مختار...",
    );
} else {
    $labels = array(
        "name" => "Name",
        "phone" => "Phone",
        "region" => "Region",
        "choose_mohafaza" => "Choose Mohafaza",
        "choose_kaza" => "Choose Kaza",
        "makhateer_count" => "Mokhtareen Count",
        "search" => "Search",
        "search_placeholder" => "Search for Mokhtar...",
    );
}


$mohafazat = ids_get_mohafazat();

$kazat = ids_get_kazat();

?>

<style>
    table {
        width: max-content;
        margin-right: auto;
        margin-left: auto;
    }

    th {
        text-align: start;
    }

    table td,
    table th {
        border: 0px !important;
    }

    input[type="text"] {
        text-align: start;
        border-radius: 4px !important;
    }

    .form-row {
        margin-top: 15px;
    }

    html:lang(ar) input[type="submit"] {
        border: 1px solid #c5262e;
        color: #000000;
        background-color: transparent;
        border-radius: 30px;
        width: 110px;
        font-weight: normal;
        margin-right: 20px;
        display: inline-block;
    }

    html:lang(en) input[type="submit"] {
        border: 1px solid #c5262e;
        color: #000000;
        background-color: transparent;
        border-radius: 30px;
        width: 110px;
        font-weight: normal;
        margin-left: 20px;
        display: inline-block;
    }

    input[type="submit"]:hover {
        color: #000000;
    }

    #ids-mokhateer-search-form {
        width: max-content;
    }

    .select2-selection,
    .select2-selection__rendered,
    .select2-selection__arrow {
        height: 40px !important;
        line-height: 40px !important;
    }

    thead {
        background-color: #c42632;
        color: #ffffff;
        font-weight: bold;
    }

    .masonry article h4 img {
        float: right;
        width: 60px;
        margin-left: 10px;
        width: 60px;
        height: 60px;
    }

    #ids_search_mokhtar {
        width: 415px;
        display: inline-block;
    }

    @media(min-width:690px) {
        #ids_search_mokhtar {
            width: 465px;
        }
    }

    @media(max-width:689px) {
        #ids_search_mokhtar {
            width: 355px;
        }
    }

    html:lang(ar) .select-label {
        display: inline-block;
        margin-left: 10px;
    }

    html:lang(en) .select-label {
        display: inline-block;
        margin-right: 10px;
    }

    html:lang(ar) .select2-container {
        text-align: right;
        margin-left: 20px;
        width: 135px;
    }

    html:lang(en) .select2-container {
        text-align: right;
        margin-right: 20px;
        width: 135px;
    }

    html:lang(en) input[type="submit"],
    html:lang(en) input[type="button"] {
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .select2-dropdown {}

    h4 a {
        font-size: 19px;
        font-weight: bold;
        color: #808080 !important;
    }

    @media (min-width: 1199px) {
        .form-row {
            display: flex;
            flex-direction: row;
        }
    }

    @media (max-width: 1198px) {
        html:lang(ar) .select-label {
            width: 85px;
        }

        html:lang(en) .select-label {
            width: 125px;
        }
    }

    @media (min-width: 1199px) {
        .ids-mohafazat-kazat-controls .form-group:last-child {
            text-align: end;
        }
    }

    @media (max-width: 560px) {
        #ids_search_mokhtar {
            width: max-content !important;
        }

        form {
            width: 100% !important;
        }
    }

    @media (max-width: 413px) {
        input[type="submit"] {
            margin-right: 0px !important;
            display: block !important;
            margin-top: 15px !important;
        }
    }

    html:lang(ar) .select2.select2-container {
        width: 110px !important;
        direction: rtl;
    }

    html:lang(en) .select2.select2-container {
        width: 145px !important;
        direction: ltr;
    }

    html:lang(ar) .select2-results__options {
        direction: rtl;
    }

    .select2-results__option,
    .select2-selection__rendered {
        text-align: start;
    }

    .mokhtareen-count-label {
        font-weight: normal;
        display: inline-block;
    }

    .mokhtareen-count-value {
        display: inline-block;
    }

    .mokhtareen-count-div {
        width: 100%;
        text-align: end;
    }

    .mokhtareen-count-label::after {
        content: ":";
    }

    .ids-mohafazat-kazat-controls .form-group {
        width: 50%;
    }

    html:lang(ar) .select2-container:last-child {
        margin-left: 0px !important;
    }

    html:lang(en) .select2-container:last-child {
        margin-right: 0px !important;
    }

    .ids-close-icon {
        margin-top: 9px !important;
        position: absolute;
        background: transparent;
        outline: none;
        border: 0px;
    }

    html:lang(en) .ids-close-icon::after {
        content: "X";
        display: block;
        width: 20px;
        height: 20px;
        background-color: #c42632;
        z-index: 1;
        bottom: 0;
        margin-left: -50px;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        opacity: 0.8;
        margin-bottom: 4px;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #e50f0f;
        cursor: pointer;
    }

    html:lang(ar) .ids-close-icon::after {
        content: "X";
        display: block;
        width: 20px;
        height: 20px;
        background-color: #c42632;
        z-index: 1;
        bottom: 0;
        margin-right: -50px;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        opacity: 0.8;
        margin-bottom: 4px;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #e50f0f;
        cursor: pointer;
    }
</style>

<form id="ids-mokhateer-search-form" method="GET" action="#">

    <div class="form-row clearfix ids-mohafazat-kazat-controls">
        <div class="form-group">
            <label class="select-label" for="select-mohafazat">
                <?php
                echo $labels["choose_mohafaza"];
                ?>
            </label>

            <select id="select-mohafazat" name="mohafaza_id" class="select-filters">
                <option value="">
                    <?php
                    echo $labels["choose_mohafaza"];
                    ?>
                </option>

                <?php
                foreach ($mohafazat as $mohafaza) {
                ?>
                    <option value="<?php echo $mohafaza->id ?>" <?php echo $mohafaza->id == $mohafaza_id ? "selected" : ""; ?> class="<?php echo $mohafaza->id; ?>">
                        <?php
                        echo $mohafaza->{"name_" . $language_code}
                        ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="select-label">
                <?php
                echo $labels["choose_kaza"];
                ?>
            </label>

            <select id="select-kazat" name="kaza_id" class="select-filters">
                <option value="">
                    <?php
                    echo $labels["choose_kaza"];
                    ?>
                </option>

                <?php
                foreach ($kazat as $kaza) {
                ?>
                    <option value="<?php echo $kaza->id ?>" <?php echo $kaza->id == $kaza_id ? "selected" : ""; ?> mohafaza_id="<?php echo $kaza->mohafaza_id; ?>" class="kazat">
                        <?php
                        echo $kaza->{"name_" . $language_code}
                        ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>


    <div class="form-row clearfix">
        <div class="form-group">
            <input type="text" id="ids_search_mokhtar" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $labels["search_placeholder"]; ?>" />
            <button class="ids-close-icon" type="reset"></button>
            <input type="submit" value="<?php echo $labels["search"]; ?>" />
        </div>
    </div>

</form>

<div class="mokhtareen-count-div">
    <label class="mokhtareen-count-label">
        <?php
        echo $labels["makhateer_count"];
        ?>
    </label>

    <label class="mokhtareen-count-value">
        <?php
        echo count($users);
        ?>
    </label>

</div>

<table class="table table-striped table-makhateer">

    <thead>
        <tr>
            <th scope="col">
                <?php
                echo $labels["name"];
                ?>
            </th>
            <th scope="col">
                <?php
                echo $labels["phone"];
                ?>
            </th>
            <th scope="col">
                <?php
                echo $labels["region"];
                ?>
            </th>
        </tr>
    </thead>
    <?php

    foreach ($users as $user) {
    ?>
        <tr>
            <td class="td-mokhtar-name">
                <?php
                echo $user->first_name;
                ?>
            </td>

            <td>
                <?php
                echo get_user_meta($user->ID, "phone", true);
                ?>
            </td>

            <td>
                <?php
                echo get_user_meta($user->ID, "region", true);
                ?>
            </td>
        </tr>

    <?php

    }
    ?>
</table>

<script>
    jQuery(function($) {

        $('#select-mohafazat').select2();
        $('#select-kazat').select2();


        var allOptions = $('#select-kazat option');

        $('#select-mohafazat').change(function() {

            var opts = allOptions;

            if ($(this).val()) {
                $('#select-kazat option.kazat').remove(); //remove all options
                var classN = $('#select-mohafazat option:selected').prop('class');
                opts = allOptions.filter('[mohafaza_id=' + classN + ']');

                console.log(opts)
            }

            $.each(opts, function(i, j) {
                $(j).appendTo('#select-kazat');
            });
        });

        $('#select-kazat').change(function() {
            $("#select-mohafazat option." + $("#select-kazat option:selected").attr("mohafaza_id")).prop("selected", true);
            $('#select-mohafazat').select2();
        });

        // Filter Applications by Number Start.
        $("#ids_search_mokhtar").keydown(function(event) {
            $(this).val() ? $(".ids-close-icon").show() : $(".ids-close-icon").hide();

            /*let searchString = $(this).val();

            console.log(searchString)

            $(".table-makhateer tr").each(function(index, element) {
                $(this).find(".td-mokhtar-name").first().text().indexOf(searchString) > -1 ? $(this).show() : $(this).hide();
            });*/
        });

        $(".ids-close-icon").click(function(event) {
            let url = new URL(location.href);
            let params = new URLSearchParams(url.search);

            // Delete the foo parameter.
            params.delete('search'); //Query string is now: 'bar=2'

            // Now join the query param and host
            let newUrl = url.origin + url.pathname + "?" + params.toString();

            location.href = newUrl;
        });

        document.getElementById("ids_search_mokhtar").addEventListener("keyup", function(event) {
            // Number 13 is the "Enter" key on the keyboard.

            if (event.keyCode === 13) {
                $("#ids_search_mokhtar").click();
            }
        });

        setTimeout(function() {
            console.log($("#ids_search_mokhtar").val())
            $("#ids_search_mokhtar").val() ? $(".ids-close-icon").show() : $(".ids-close-icon").hide();
        });

        $("#ids_search_mokhtar").change(function(event) {
            $(this).val() ? $(".ids-close-icon").show() : $(".ids-close-icon").hide();
        });

        $("#ids_search_mokhtar").keyup(function(event) {
            $(this).val() ? $(".ids-close-icon").show() : $(".ids-close-icon").hide();

            /*let searchString = $(this).val();

             console.log(searchString)

             $(".table-makhateer tr").each(function(index, element) {
                 $(this).find(".td-mokhtar-name").first().text().indexOf(searchString) > -1 ? $(this).show() : $(this).hide();
             });*/
        });
        // Filter Applications by Number End.
    });
</script>
<?php
get_footer();
?>