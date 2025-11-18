<?php

echo get_site_url();

return;

include_once '../models/MokhtarResponse.php';
include_once '../config/config.php';

include_once '../config/Database.php';
include_once '../models/Makhateer.php';

$database = new Database();
$db = $database->connect();
$Config = new config();
$username = isset($_GET['username']) ? $_GET['username'] : die();
$password = isset($_GET['password']) ? $_GET['password'] : die();
$url = $Config->APIUrl . "Customer";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers[] = "Accept: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERPWD,  "$username:$password");
$output = curl_exec($ch);
$info = curl_getinfo($ch);


if ($info['http_code'] != 200) {
    echo "Return code is {$info} \n"
        . curl_error($ch);
} else {

    $manage = json_decode($output, true);

    foreach ($manage["value"] as $mokhtar) {

        $Makhteer = new Makhateer($db);

        $Makhteer->CId = null;

        $Makhteer->CId  = $mokhtar["No_"];

        $Makhteer->read_Profile();
        if ($Makhteer->Id == null) {
            $Makhteer->address = $mokhtar["City"];
            $Makhteer->name = $mokhtar["Name"];
            $Makhteer->phone = $mokhtar["Phone_No_"];
            $Makhteer->regionCode=$mokhtar["Country_Region_Code"];
            $Makhteer->memberOfBoardDirector=$mokhtar["Member_of_Board_of_Director"];
            $Makhteer->activeElection=$mokhtar["Active_Election"];
            $Makhteer->create();
        } else {
            //update


        }
        // Get post
        //  $Makhteer->read_Profile();
    }
    echo "Success";
}
curl_close($ch);
