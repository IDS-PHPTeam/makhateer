<?php  /* Template Name: Tracking Appllications Page */

$current_user = wp_get_current_user();

if (empty($current_user->ID) || !function_exists('ids_is_mokhtar') || !ids_is_mokhtar()) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $uri  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $_SESSION['redirect_to'] = (is_ssl() ? 'https://' : 'http://') . $host . $uri;

    $login_url = home_url('/');
    $page      = get_page_by_path('login');
    if ($page) {
        $page_id = $page->ID;
        if (function_exists('pll_get_post')) {
            $translated = pll_get_post($page->ID, get_locale());
            if ($translated) {
                $page_id = $translated;
            }
        }
        $permalink = get_permalink($page_id);
        if ($permalink) {
            $login_url = $permalink;
        }
    }

    wp_safe_redirect($login_url);
    exit;
}

get_header();

$language_code = get_locale();

$labels = array();

if ($language_code == "ar") {
    $labels["enter_app_number"]  = "أدخل رقم الطلب";
    $labels["check"]  = "تحقق";
} else {
    $labels["enter_app_number"]  = "Enter application number";
    $labels["check"]  = "Check";
}

$message_for_non_members = $language_code == "ar" ? "لا يوجد طلبات مقدّمة!" : "No applications found!";


// Fetch Mokhtar Applications From "Haceb" Start.

$template_path = get_template_directory();
$page_templates_dir = "$template_path/page-templates";

require_once("$page_templates_dir/models/MokhtarResponse.php");
require_once("$page_templates_dir/config/config.php");

$config = new config();

$id = $current_user->ID;

if ($id != "") {
    $url = $config->APIUrl . "Applications?\$filter=Customer_No_%20eq%20'$current_user->user_login'";
} else {
    $url = $config->APIUrl . "Applications";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers[] = "Accept: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERPWD,  $config->UserName . ":" . $config->Password);

$applications = array();
$response = json_decode(curl_exec($ch));
$info = curl_getinfo($ch);

if ($info['http_code'] != 200) {
    /*print_r($info);
    echo "Return code is {$info} \n"
        . curl_error($ch);*/
} elseif (is_object($response) && isset($response->value) && is_array($response->value)) {
    $applications = $response->value;
    //echo  "<pre>" . print_r($applications, true) . "</pre>";
}
curl_close($ch);

// Fetch Mokhtar Applications From "Haceb" End.


// Remove empty applications as they don't include basic info!

$applications = array_filter($applications, function ($application) {
    return is_object($application) && !empty($application->Incoming_Doc_No_);
});


// If Mokhtar has no applications, inform him!


if (!count($applications)) {

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

?>

<style>
h5.ward_status {
    font-size: 18px;
    letter-spacing: 0px;
}
    #application-state-wrap {
        padding: 20px !important;
    }

    #application-details .details {
        color: #949494;
    }

    /* application-search */

    #application-search {
        display: grid;
        gap: 30px;
        grid-template: 35px/300px 1fr;
        margin: 30px 0;
    }

    #application-search .input-wrap {
        width: 100%;
        background-color: #EDEDED;
        border-radius: 50px;
        overflow: hidden;
        display: flex;
        height: 100%;
    }

    #app-search-in {
        width: inherit;
        border: none;
        background: none;
        padding: 5px 15px;
    }

    html:lang(ar) #app-search-btn {
        background-color: #808080;
        color: #fff;
        border-radius: 50px;
        width: 103px;
        border: none;
        transition: background-color .3s ease;
        margin-right: auto;
    }

    html:lang(en) #app-search-btn {
        background-color: #808080;
        color: #fff;
        border-radius: 50px;
        width: 103px;
        border: none;
        transition: background-color .3s ease;
        margin-left: auto;
    }


    #app-search-btn:hover {
        background-color: #999;
    }

    #application-search input,
    #application-search button {
        font-family: "Cairo", sans-serif !important;
    }

    /* progress bar */
    #app-status-progress {
        display: flex;
        width: 100%;
        background-color: #EDEDED;
        border-radius: 50px;
        overflow: hidden;
        position: relative;
    }

    #app-status-progress::before {
        content: "";
        display: block;
        height: 35px;
        width: 100%;
        background: linear-gradient(to left, #00000000 0%, #00000000 var(--value), #EDEDED var(--value)), linear-gradient(242deg, transparent 33%, rgba(255, 255, 255, .15) 33%, rgba(255, 255, 255, .15) 66%, transparent 66%), linear-gradient(to left, #14d086, #c4ff77);
        border-radius: 2px;
        background-size: 100% 100%, 35px 100%, 100% 100%, 100% 100%;
    }

    #app-status-progress::after {
        content: attr(data-value);
        position: absolute;
        right: var(--value);
        top: 50%;
        transform: translate(-15px, -50%);
        font-weight: 500;
    }

    .progress {
        height: 30px !important;
    }

    /* progress steps */
    .progress-step {
        background-color: #F4F7F9;
        padding: 15px;
        margin-bottom: 30px;
        border-radius: 7px;
        display: grid;
        grid-template: auto/1fr auto;
        grid-template-areas: "name icon"
            "date icon"
            "desc icon"
            "progress icon";
        gap: 0 30px;
    }

    .progress-step .fa-check {
        color: #70E791;
    }

    .progress-step .fa-clock {
        color: #E7B400;
    }

    #application-progress-steps .chief-name {
        font-size: 25px;
        padding: 0;
        grid-area: name;
        font-weight: bold;
    }

    #application-progress-steps .desc {
        text-align: right;
        color: #949494;
        grid-area: desc;
    }

    #application-progress-steps i {
        display: flex;
        width: 2em;
        height: 2em;
        border: 1px solid #CDCDCD;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        grid-area: icon;
        align-self: center;
        box-sizing: border-box;
        font-size: 20px;
    }

    h3 {
        font-size: 17px !important;
    }

    .fa {
        font-family: "FontAwesome"
    }

    html[lang="ar"] .terms-form [class^="vc_col"] {
        float: right;
    }

    @media (max-width: 767px) {
        #application-search {
            gap: 30px;
            grid-template: 35px 35px/1fr;
        }
    }
</style>




<div class="itemscope" itemscope itemtype="https://schema.org/Article">
    <section class="post post_format_standard postLeft no_margin post-2474 page type-page status-publish hentry">
        <article class="post_content">
            <h1 itemprop="name" class="post_title entry-title">حالة الطلب</h1>

            <div class="post_text_area" itemprop="articleBody">
                <div class="vc_row wpb_row vc_row-fluid vc_custom_1616081222279">
                    <div class="wpb_column vc_col-sm-12">
                        <div class="vc_column-inner ">
                            <div class="wpb_wrapper">
                                <div id="sc_blogger_1295214662" class="sc_blogger sc_blogger_horizontal style_masonry4 masonryWrap">
                                    <div>
                                        <div class="screen-reader-response">
                                            <p role="status" aria-live="polite" aria-atomic="true"></p>
                                            <ul></ul>
                                        </div>
                                        <!-- added by karim  -->
                                        <div id="application-state-wrap">
                                            <!--<h2 id="application-details">تفاصيل الطلب: <span class="details">لوريم ايبسوم</span></h2>-->
                                            <!--<div id="application-search">
                                                <div class="input-wrap">
                                                    <input type="search" id="app-search-in" placeholder="<?php echo $labels["enter_app_number"]; ?>">
                                                    <button type="submit" id="app-search-btn">
                                                        <?php
                                                        //echo $labels["check"];
                                                        ?>
                                                    </button>
                                                </div>
                                            </div>-->
                                            <div id="application-progress-steps">
                                                <?php
                                                foreach ($applications as $application) {
                                                    if (!$application->Incoming_Doc_No_) {
                                                        continue;
                                                    }

                                                    $status = 0;

                                                    if ($application->Ward_Status == "يتم خلق طلب جديد") {
                                                        $status = 25;
                                                    } else if ($application->Ward_Status == "يتم دراسة الطلب والتأكد من المستندات واحتساب قيمة المستحقات") {
                                                        $status = 50;
                                                    } else if ($application->Ward_Status == "يتم تصفية الطلب ماليا") {
                                                        $status = 75;
                                                    } else  if ($application->Ward_Status == "يتم اصدار شيك وعليك انتظار رسالة نصية من الصندوق بموعد حضورك لاستلامه") {
                                                        $status = 100;
                                                    }
                                                ?>
                                                    <div id="ids_application_id_<?php echo $application->Incoming_Doc_No_; ?>" class="progress-step">
                                                        <h3 class="chief-name">
                                                            <?php
                                                            if ($language_code == "ar") {
                                                            echo $application->Application_Type;
                                                            }else {
                                                                if($application->Application_Type=='طلب تعويض نهايةالخدمة'){
                                                                    echo "End of Service Indemnity";
                                                                }else if($application->Application_Type=='وفاة'){
                                                                    echo "Death Assistance";
                                                                }else if($application->Application_Type=='ولادة'){
                                                                    echo "Birth Grant";
                                                                    
                                                                }else if($application->Application_Type=='زواج'){
                                                                    echo "Marriage Grant";
                                                                    
                                                                }else if($application->Application_Type=='مختلف'){
                                                                    echo "Other";
                                                                }
                                                            }
                                                            ?>
                                                        </h3>
                                                        <h3>
                                                            <?php
                                                            echo $application->Incoming_Doc_No_;
                                                            ?>
                                                        </h3>

                                                        <date>
                                                            <?php
                                                            $date = new DateTime($application->Incoming_Doc_Date);
                                                            echo $date->format("Y-m-d");
                                                            ?>
                                                        </date>
                                                        <i class="fa fa-check"></i>

                                                        <div id="app-status-progress" class="progress" style="--value: <?php echo $status; ?>%" data-value="<?php echo $status; ?>%"></div>
                                                        <h5 class="ward_status">
                                                            <?php
                                                            if ($language_code == "ar") {
                                                            echo $application->Ward_Status;
                                                            }else {
                                                             if($application->Ward_Status=="يتم خلق ملف جديد"){
                                                                 
                                                                  echo "A new application is created.";
                                                             }else if($application->Ward_Status=="يتم دراسة الطلب والتأكد من المستندات واحتساب قيمة المستحقات"){
                                                                  echo "The application is under study, documents are to be verified, and the value of the dues is being calculated.";
                                                                 
                                                             }else if($application->Ward_Status=="يتم تصفية الطلب ماليا"){
                                                                 echo "The application is processed financially.";
                                                             }  else if($application->Ward_Status=="يتم اصدار شيك وعليك انتظار رسالة نصية من الصندوق بموعد حضورك لاستلامه"){
                                                                 echo "A check is issued, and you have to wait for receiving a SMS from the fund to inform you when you can receive it.";
                                                             }
                                                            }
                                                            ?>
                                                        </h5>
                                                    </div>

                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <br><br>
                                        <!-- /added by karim  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- </div> class="post_text_area" itemprop="articleBody"> -->
        </article> <!-- 		</article> class="post_content"> -->
    </section> <!-- 	</section> class="post post_format_standard postLeft no_margin post-2474 page type-page status-publish hentry"> -->


</div>



<script>
    jQuery(function($) {
        // Filter Applications by Number Start.
        $("#app-search-btn").click(function(event) {
            let searchString = $("#app-search-in").val();

            console.log(searchString)

            $('#application-progress-steps').fadeTo(500, 0.5);

            $(".progress-step").each(function(index, element) {
                $(this).attr("id").indexOf(searchString) > -1 ? $(this).show() : $(this).hide();
            });

            $('#application-progress-steps').fadeTo(500, 1);
        });

        $("#app-search-in").keyup(function(event) {
            let searchString = $("#app-search-in").val();

            if (searchString) {
                return;
            }

            console.log(searchString)

            $('#application-progress-steps').fadeTo(500, 0.5);

            $(".progress-step").each(function(index, element) {
                $(this).attr("id").indexOf(searchString) > -1 ? $(this).show() : $(this).hide();
            });

            $('#application-progress-steps').fadeTo(500, 1);
        });
        // Filter Applications by Number End.
    });
</script>

<!-- added by karim -->
<script>
    const addTerm = () => {
        const newNode = document.querySelector(".term").cloneNode(true);
        document.querySelector(".terms-wrap").appendChild(newNode);
    }
    const removeTerm = btn => btn.closest(".term").remove();
</script>
<!-- /added by karim  -->

<?php
get_footer();
?>