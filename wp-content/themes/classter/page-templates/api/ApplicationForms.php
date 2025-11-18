

<?php


include_once '../../models/MokhtarResponse.php';

$url= "http://77.42.157.190:7048/BC160/OData/Company('Testing%20IDs')/ApplicationForms?\$filter=Customer_No%20eq%20'C000422'";
$username="IDSadmin";
$password="IDSpassword";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM|CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers[] = "Accept: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERPWD,  "$username:$password");
$output = curl_exec($ch);
$info = curl_getinfo($ch);

if (  $info['http_code'] != 200 ){
    echo "Return code is {$info['http_code']} \n"
        .curl_error($ch);
} else {
    
 echo   $output;

}
curl_close($ch);
?>