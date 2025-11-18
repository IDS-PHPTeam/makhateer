<?php

// Load Composer's autoloader
require ABSPATH .'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;

// $notification['title']="Qasemti - New Order #15879";
// $notification['body']="New Order #15879";

// $notification['type']="1";
// $notification['typeId']="1";
// $notification['badge']="1";
// $notification['id']="1";


// push_notification_v1(['fdKF7vP97E2uriTztjVl8Q:APA91bEUYm3rT6xWko5Rz6xDtvb-LRYg-2Fh0061Ln74EGFiwLrUlslqIEwnTtzjRbT1wq86dkQJl6pQ6EvWopEwc0MjS25WDt80eR0B7I2DIHwUS0O4hR7XOIcEKP8ssOrci_qjwep7'], $notification, ['1']);
// exit;

// push_notification_v1($array_device_tokens,$arrNotification, $array_mobilenotification_ids, 2);

function push_notification_v1($registation_ids, $notification, $mobile_notification_ids, $device_type)
{
    
    global $wpdb;
    
    if($notification['body']):

    // Path to your service account JSON file
    $serviceAccountFile = ServiceAccountFile;
    // Set FCM URL for HTTP v1 API
    $fcmUrl =FCM_URL;

    // Array of device tokens
    $deviceTokens = $registation_ids;
    if (!is_array($deviceTokens)) {
        $deviceTokens = (array) $deviceTokens;
    }
    // $deviceTokens = ['fdKF7vP97E2uriTztjVl8Q:APA91bEUYm3rT6xWko5Rz6xDtvb-LRYg-2Fh0061Ln74EGFiwLrUlslqIEwnTtzjRbT1wq86dkQJl6pQ6EvWopEwc0MjS25WDt80eR0B7I2DIHwUS0O4hR7XOIcEKP8ssOrci_qjwep7'];

    
    // The notification message
    $notification1 = [
        'title' => $notification["title"],
        'body'  => $notification["body"],
    ];

    if($device_type==1){
        $data = [
            'type'  => (string)$notification["type"],
            'typeId'  => (string)$notification["typeId"],
            'id'  => (string)$notification["id"],
            'badge'  => (string)$notification["badge"]
        ];
    }elseif($device_type==2){
        $data = [
            'title' => (string)$notification["title"],
            'body'  => (string)$notification["body"],
            'type'  => (string)$notification["type"],
            'typeId'  => (string)$notification["typeId"],
            'id'  => (string)$notification["id"],
            'badge'  => (string)$notification["badge"]
        ];
    }
        
    

    // Get OAuth2 token using the service account credentials
    $scopes = [Google_API_FBM];
    $credentials = new ServiceAccountCredentials($scopes, $serviceAccountFile);
    $authToken = $credentials->fetchAuthToken();

    // Create an HTTP client
    $client = new Client();
   
   
    foreach ($deviceTokens as $deviceToken) {

    if($device_type==1){
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => $notification1,
                'data'=>$data
            ]
        ];
    }elseif($device_type==2){
        $message = [
            'message' => [
                'token' => $deviceToken,
                'data'=>$data
            ]
        ];

    } 
    try {
        // Send the HTTP POST request to FCM
        $response = $client->post($fcmUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $authToken['access_token'],
                'Content-Type'  => 'application/json',
            ],
            'json' => $message
        ]);

        // Optionally handle successful response
        $statusCode = $response->getStatusCode();
       if ($statusCode === 200) {
        $query = "UPDATE wp_mobile_notifications SET is_sent = 1
        where ID IN ('" . implode("','", $mobile_notification_ids) . "') \n";
       }
    
    
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // Handle request exceptions (like network errors, invalid responses, etc.)
        $query = "UPDATE wp_mobile_notifications SET is_sent = -1
        where ID IN ('" . implode("','", $mobile_notification_ids) . "') \n";
    
    } catch (\Exception $e) {
        // Handle any other general exceptions
        $query = "UPDATE wp_mobile_notifications SET is_sent = -1
        where ID IN ('" . implode("','", $mobile_notification_ids) . "') \n";
    }
    $wpdb->query($query);

    }
    endif;
}
