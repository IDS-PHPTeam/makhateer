
<?php


$haceb_username = 'IDSadmin';
$haceb_password = 'IDSpassword';
$haceb_api_url = "http://77.42.157.190:7048/BC160/OData/Company('الصندوق%20التعاوني%20للمختارين')/Customer";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $haceb_api_url);

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers[] = "Accept: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERPWD,  "$haceb_username:$haceb_password");
$response_data = curl_exec($ch);
$info = curl_getinfo($ch);

if ($info['http_code'] != 200) {
    return curl_error($ch);
} else {
    $makhateer = json_decode($response_data);

    $ccc = 0;
    
    $ids_mysql = mysqli_connect("10.1.1.32", "remote", "P@ssw0rd", "makhateer_db");

    foreach ($makhateer->value as $mokhtar) {
        $sql = "INSERT INTO haceb_users VALUES('" . $mokhtar->No_ . "', '" . $mokhtar->Name . "')";
        //$wpdb->get_results($sql);
        mysqli_query($ids_mysql, $sql);

        exit();
        $ccc++;
    }

    return $ccc;
}

?>