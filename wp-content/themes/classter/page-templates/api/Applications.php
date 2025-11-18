<?php


include_once '../models/MokhtarResponse.php';
include_once '../config/config.php';

$Config = new config();

$id = isset($_GET['id']) ? $_GET['id'] : "";

$username = isset($_GET['username']) ? $_GET['username'] : die();
$password = isset($_GET['password']) ? $_GET['password'] : die();

if ($id != "")
    $url = $Config->APIUrl . "Applications?\$filter=Customer_No_%20eq%20'$id'";
else
    $url = $Config->APIUrl . "Applications";

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
    print_r($info);
    echo "Return code is {$info} \n"
        . curl_error($ch);
} else {

    echo   $output;
}
curl_close($ch);
