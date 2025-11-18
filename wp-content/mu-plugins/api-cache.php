<?php
function get_api_cache(){

//dont run if we are calling to cache the file (see later in the code)
if( isset($_GET['cachecall']) && $_GET['cachecall'] === true)
    return;

$url = "$_SERVER[REQUEST_URI]";

// print_r('working');
// do a little error checking
// $url1 = explode('?', $url);
// print_r($url1);


$uri= explode('/',$url);
// print_r($uri);
// exit;
// print_r($uri[5]);
// $end = explode('?',$uri[5]);
// print_r($end[0]);

// $merge = array_merge($uri,$end);
// print_r($merge);

//we have a array (1st key is blank)
// if( $uri[1] !== 'wp-json' || $uri[2] !== 'wp' || $uri[3] !== 'v2'){
//     print_r('working0');
//     return;
// } 

//lock down the possible endpoints we dont want idiots playing with this...
$allowed_endpoints= array(
    'lawslaws',
    'test',
    'testfunc',
    'hadi2',
    'news-categories',
    'news',
    'laws',
    'notifications',
    'decrees',
    'decisions',
    'diary',
    'requests',
    'faq',
    'pages',
    'phone-book',
    'apps',
    // 'message-us',
    // 'newsletter'
);
// print_r($uri);
$endpoint= array_pop($uri); // not sure if this is valid or not, is there more structure to some api calls?
// print_r($endpoint);

function wprc_add_custom_news_categories( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'news-categories', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'news-categories';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_news_categories', 10, 1);

function wprc_add_custom_news( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'news', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'news';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_news', 10, 1);

function wprc_add_custom_laws( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'laws', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'laws';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_laws', 10, 1);

function wprc_add_custom_notifications( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'notifications', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'notifications';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_notifications', 10, 1);

function wprc_add_custom_decrees( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'decrees', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'decrees';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_decrees', 10, 1);

function wprc_add_custom_decisions( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'decisions', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'decisions';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_decisions', 10, 1);

function wprc_add_custom_diary( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'diary', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'diary';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_diary', 10, 1);

function wprc_add_custom_requests( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'requests', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'requests';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_requests', 10, 1);

function wprc_add_custom_faq( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'faq', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'faq';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_faq', 10, 1);

function wprc_add_custom_pages( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'pages', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'pages';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_pages', 10, 1);

function wprc_add_custom_phone_book( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'phone-book', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'phone-book';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_phone_book', 10, 1);

function wprc_add_custom_regions( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'regions', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'regions';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_regions', 10, 1);

function wprc_add_custom_search( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'search', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'search';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_search', 10, 1);

function wprc_add_custom_team( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'team', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'team';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_team', 10, 1);

function wprc_add_custom_bids( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'bids', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'bids';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_bids', 10, 1);

function wprc_add_custom_about_us( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'about-us', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'about-us';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_about_us', 10, 1);

function wprc_add_custom_apps( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'apps', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'apps';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_apps', 10, 1);


function wprc_add_custom_services( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'services', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'services';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_services', 10, 1);

function wprc_add_custom_map( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'map', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'map';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_map', 10, 1);

function wprc_add_custom_makhateer( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'makhateer', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'makhateer';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_makhateer', 10, 1);

function wprc_add_custom_polls( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'polls', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'polls';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_polls', 10, 1);

function wprc_add_custom_statistics( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'statistics', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'statistics';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_statistics', 10, 1);

function wprc_add_custom_makhateer_map( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'makhateer-map', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'makhateer-map';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_makhateer_map', 10, 1);

function wprc_add_custom_sites( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'sites', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'sites';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_sites', 10, 1);


function wprc_add_custom_templates( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'templates', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'templates';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_templates', 10, 1);



function wprc_add_custom_message_us( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'message-us', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'message-us';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_message_us', 10, 1);

function wprc_add_custom_newsletter( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'newsletter', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'newsletter';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_newsletter', 10, 1);

function wprc_add_custom_auth( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'auth', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'auth';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_auth', 10, 1);

function wprc_add_custom_profile( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'profile', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'profile';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_profile', 10, 1);

function wprc_add_custom_eos( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'eos', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'eos';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_eos', 10, 1);

function wprc_add_custom_lost_password( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'lost-password', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'lost-password';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_lost_password', 10, 1);

function wprc_add_custom_mark_notification( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'mark-notification', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'mark-notification';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_mark_notification', 10, 1);

function wprc_add_custom_update_device( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'update-device', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'update-device';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_update_device', 10, 1);

function wprc_add_custom_update_devicev13( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'api/v1' ] ) || ! in_array( 'update-device-v1-3', $allowed_endpoints[ 'api/v1' ] ) ) {
        $allowed_endpoints[ 'api/v1' ][] = 'update-device-v1-3';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_custom_update_devicev13', 10, 1);



if( !in_array( $endpoint, $allowed_endpoints)){
    // print_r('working1');
    return;
} 

//ok reasonably confident its a api call...

$cache_folder= get_stylesheet_directory().'/api_cache/';

// prob best if not within php server but to get you going
if(! file_exists ( $cache_folder ) ){
    // print_r('working2');
    mkdir($cache_folder); //warning 777!!
}


/*
* Need to choose a method of control for your cached json files
* you could clear out the folder on update post/ taxonomies etc
* or cron clear out hourly/weekly whatever freq you want
*/


if( file_exists($cache_folder.$endpoint.'.json') ){
    // print_r('working3');
    $json= file_get_contents($cache_folder.$endpoint.'.json');
    header('Content-Type: application/json');
    echo $json;
    exit;// we need nothing else from php exit 
} else {
    // print_r('working4');
    //make sure there will be no errors etc..
    $ch = curl_init();
    $url= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?cachecall=true";
    $timeout= 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $json = curl_exec($ch);
    curl_close($ch);
    file_put_contents($cache_folder.$endpoint.'.json', $json);  
}

}

get_api_cache();  