<?php

$ids_mysql;

if (!defined('IDS_ABSPATH')) {
    define('IDS_ABSPATH', "https://$_SERVER[HTTP_HOST]");
}

function ids_db_connect()
{
    // return  mysqli_connect("localhost", "mokhtund_ids", "bC3V&e~@jIb1", "mokhtund_db");
    // return  mysqli_connect("10.1.1.32", "remote", "P@ssw0rd", "makhateer_db");
    return  mysqli_connect("localhost", "root", "", "makhateer_db");
}

function ids_get_haceb_makhateer()
{
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
    // print_r($response_data);
    // exit;
    $info = curl_getinfo($ch);

    if ($info['http_code'] != 200) {
        return curl_error($ch);
    } else {
       
        return json_decode($response_data)->value;
    }
}

function ids_username_exists($username)
{
    global $ids_mysql;

    if (!$ids_mysql) {
        die("Unable to connect to the database!");
    }

    $sql = "SELECT * FROM wp_users WHERE user_login='$username' LIMIT 1";
    $query_result = mysqli_query($ids_mysql, $sql);

    $user = mysqli_fetch_assoc($query_result);

    if ($user) {
        return $user["ID"];
    }

    return false;
}

function ids_wp_hash_password($password)
{
    global $wp_hasher;

    if (empty($wp_hasher)) {
        require_once 'wp-includes/class-phpass.php';
        // By default, use the portable hash from phpass.
        $wp_hasher = new PasswordHash(8, true);
    }

    return $wp_hasher->HashPassword(trim($password));
}

function ids_db_query($sql)
{
    global $ids_mysql;

    return $ids_mysql->query($sql) === TRUE;
}

if (isset($_GET["haceb_sync_makhateer"])) {

    $makhateer = ids_get_haceb_makhateer();

    if (is_array($makhateer)) {

        global $ids_mysql;

        $ids_mysql = ids_db_connect();


        $sql = "DELETE FROM `wp_ids_haceb_makhateer`";
        mysqli_query($ids_mysql, $sql);

        foreach ($makhateer as $mokhtar) {
            $sql = "INSERT INTO `wp_ids_haceb_makhateer`
                    (
                        No_,
                        Name,
                        Phone_No_,
                        Country_Region_Code,
                        City,
                        region,
                        Member_of_Board_of_Director,
                        Active_Election
                    )
                    VALUES(
					'{$mokhtar->No_}',
					'{$mokhtar->Name}',
					'{$mokhtar->Phone_No_}',
					'{$mokhtar->Country_Region_Code}',
					'{$mokhtar->City}',
					'{$mokhtar->region}',
					'{$mokhtar->Member_of_Board_of_Director}',
					'{$mokhtar->Active_Election}'
				    )";

            mysqli_query($ids_mysql, $sql);
        }
        $current_url = "https://$_SERVER[HTTP_HOST]/sync-haceb.php?wp_sync_makhateer=true";

        header("Location: $current_url");

        exit();
    }
} else if (isset($_GET["wp_sync_makhateer"])) {
    global $ids_mysql;

    $ids_mysql = ids_db_connect();

    $sql = "SELECT * FROM `wp_ids_haceb_makhateer`";
    $query_result_makhateer = mysqli_query($ids_mysql, $sql);
  
    $new_users = array();

    $rrr = 0;

    while ($mokhtar = $query_result_makhateer->fetch_assoc()) {
        $wp_user_id = ids_username_exists($mokhtar["No_"]);
        $ids_mysql->set_charset("utf8");

  
        if ($wp_user_id) {
            //echo $wp_user_id . "\n";
            // User already exists => Update user.

            $wp_mokhtar_id = $wp_user_id;

            $sql = "UPDATE wp_users SET user_nicename='{$mokhtar["Name"]}', display_name='{$mokhtar["Name"]}' WHERE ID=$wp_mokhtar_id";
            ids_db_query($sql);


            $metas = array();

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='first_name' ";
            $query_result = mysqli_query($ids_mysql, $sql);

            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'first_name','" . $mokhtar["Name"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["Name"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='first_name'";
                ids_db_query($query_meta_update);
            }
                

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='IsApproved' ";
            $query_result = mysqli_query($ids_mysql, $sql);

            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'IsApproved',1)";
                ids_db_query($query_meta_insert);
            }
                

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='wp_capabilities' ";
            $query_result = mysqli_query($ids_mysql, $sql);

            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'wp_capabilities','a:1:{s:7:\"Mokhtar\";b:1;}')";
                ids_db_query($query_meta_insert);
            }
          

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='nickname'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'nickname','" . $mokhtar["Name"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["Name"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='nickname'";
                ids_db_query($query_meta_update);
            }

      
            
            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='phone'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'phone','" . $mokhtar["Phone_No_"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["Phone_No_"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='phone'";
                ids_db_query($query_meta_update);
            }
            
            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='IsApproved'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'IsApproved','true')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='true' WHERE `user_id`=$wp_mokhtar_id AND meta_key='IsApproved'";
                ids_db_query($query_meta_update);
            }
            

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='Address'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'Address','" . $mokhtar["City"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["City"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='Address'";
                ids_db_query($query_meta_update);
            }
            

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='RegionCode'";
            $query_result = mysqli_query($ids_mysql, $sql);

          
            // $RegionCode=mb_convert_encoding($mokhtar["Country_Region_Code"], "UTF-8");
            $RegionCode=$mokhtar["Country_Region_Code"];
           

            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'RegionCode','" . $RegionCode. "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $RegionCode . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='RegionCode'";
                ids_db_query($query_meta_update);
            }

            
            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='region'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'region','" . $mokhtar["region"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["region"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='region'";
                ids_db_query($query_meta_update);
            }
            

            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='MemberOfBoardDirector'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'MemberOfBoardDirector','" . $mokhtar["Member_of_Board_of_Director"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["Member_of_Board_of_Director"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='MemberOfBoardDirector'";
                ids_db_query($query_meta_update);
            }
            


            $sql = "SELECT * FROM wp_usermeta  WHERE `user_id`=$wp_mokhtar_id AND meta_key='ActiveElection'";
            $query_result = mysqli_query($ids_mysql, $sql);


            if (mysqli_num_rows($query_result)==0) {
                $query_meta_insert = "insert into wp_usermeta  (user_id,meta_key,meta_value) Value ($wp_mokhtar_id,'ActiveElection','" . $mokhtar["Active_Election"] . "')";
                ids_db_query($query_meta_insert);
            }else{
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mokhtar["Active_Election"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='ActiveElection'";
                ids_db_query($query_meta_update);
            }
            




            // Fetch "MohafazaID" based on its name in the table "makhateer".

            $sql = "SELECT `user_id` FROM wp_usermeta WHERE meta_key='RegionCode' AND meta_value='" . $mokhtar["Country_Region_Code"] . "' LIMIT 1";
            $query_result = mysqli_query($ids_mysql, $sql);

     
            if (mysqli_num_rows($query_result)) 
                $user_data = mysqli_fetch_assoc($query_result);

            if ($user_data) {
                $sql = "SELECT meta_value FROM wp_usermeta WHERE `user_id`=" . $user_data["user_id"] . " AND meta_key='MohafazaID' LIMIT 1";
                $query_result = mysqli_query($ids_mysql, $sql);

                if (!$query_result) {
                    echo ("Error description: " . mysqli_error($ids_mysql));
                }

                $mohafaza_data = mysqli_fetch_assoc($query_result);
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mohafaza_data["meta_value"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='MohafazaID'";
                ids_db_query($query_meta_update);
            }

            $sql = "SELECT `user_id` FROM wp_usermeta WHERE meta_key='Address' AND meta_value='" . $mokhtar["City"] . "' LIMIT 1";
            $query_result = mysqli_query($ids_mysql, $sql);

            if (!$query_result) {
                echo ("Error description: " . mysqli_error($ids_mysql));
            }

            $user_data = mysqli_fetch_assoc($query_result);

            if ($user_data) {
                $sql = "SELECT meta_value FROM wp_usermeta WHERE `user_id`=" . $user_data["user_id"] . " AND meta_key='KazaID' LIMIT 1";
                $query_result = mysqli_query($ids_mysql, $sql);

                if (!$query_result) {
                    echo ("Error description: " . mysqli_error($ids_mysql));
                }
                
                $mohafaza_data = mysqli_fetch_assoc($query_result);
                $query_meta_update = "UPDATE wp_usermeta SET meta_value='" . $mohafaza_data["meta_value"] . "' WHERE `user_id`=$wp_mokhtar_id AND meta_key='KazaID'";
                ids_db_query($query_meta_update);
            }

            // Fetch "MohafazaID" based on its name in the table "makhateer.
        } else {

            $new_users[$mokhtar["No_"]] = "";

            $sql = "INSERT INTO wp_users(
                user_login,
                user_pass,
                user_nicename,
                user_email,
                user_url,
                user_registered,
                user_activation_key,
                user_status,
                display_name
            ) VALUES(
                '{$mokhtar["No_"]}',
                '" . ids_wp_hash_password("P@ssw0rd1") . "',
                '{$mokhtar["Name"]}',
                '',
                '',
                '" . gmdate('Y-m-d H:i:s') . "',
                '',
                0,
                '{$mokhtar["Name"]}'
            )";


            if (ids_db_query($sql)) {

                $wp_mokhtar_id = $ids_mysql->insert_id;

                $new_users[$mokhtar["No_"]] = $wp_mokhtar_id;


                $metas = array();

                $metas[] = "($wp_mokhtar_id,'wp_capabilities','a:1:{s:7:\"Mokhtar\";b:1;}')";
                $metas[] = "($wp_mokhtar_id,'first_name','" . $mokhtar["Name"] . "')";
                $metas[] = "($wp_mokhtar_id,'nickname','" . $mokhtar["Name"] . "')";
                $metas[] = "($wp_mokhtar_id,'phone','" . $mokhtar["Phone_No_"] . "')";
                $metas[] = "($wp_mokhtar_id,'IsApproved','" . true . "')";
                $metas[] = "($wp_mokhtar_id,'Address','" . $mokhtar["City"] . "')";
                $metas[] = "($wp_mokhtar_id,'RegionCode','" . $mokhtar["Country_Region_Code"] . "')";
                $metas[] = "($wp_mokhtar_id,'region','" . $mokhtar["region"] . "')";
                $metas[] = "($wp_mokhtar_id,'MemberOfBoardDirector','" . $mokhtar["Member_of_Board_of_Director"] . "')";
                $metas[] = "($wp_mokhtar_id,'ActiveElection','" . $mokhtar["Active_Election"] . "')";


                $sql = "SELECT `user_id` FROM wp_usermeta WHERE meta_key='RegionCode' AND meta_value='" . $mokhtar["Country_Region_Code"] . "' LIMIT 1";
                $query_result = mysqli_query($ids_mysql, $sql);
                $user_data = mysqli_fetch_assoc($query_result);

                if ($user_data) {
                    $sql = "SELECT meta_value FROM wp_usermeta WHERE `user_id`=" . $user_data["user_id"] . " AND meta_key='MohafazaID' LIMIT 1";
                    $query_result = mysqli_query($ids_mysql, $sql);
                    $mohafaza_data = mysqli_fetch_assoc($query_result);

                    $metas[] = "($wp_mokhtar_id,'MohafazaID'," . $mohafaza_data["meta_value"] . ")";
                }


                $sql = "SELECT `user_id` FROM wp_usermeta WHERE meta_key='Address' AND meta_value='" . $mokhtar["City"] . "' LIMIT 1";
                $query_result = mysqli_query($ids_mysql, $sql);
                $user_data = mysqli_fetch_assoc($query_result);

                if ($user_data) {
                    $sql = "SELECT meta_value FROM wp_usermeta WHERE `user_id`=" . $user_data["user_id"] . " AND meta_key='KazaID' LIMIT 1";
                    $query_result = mysqli_query($ids_mysql, $sql);
                    $kaza_data = mysqli_fetch_assoc($query_result);

                    $metas[] = "($wp_mokhtar_id,'KazaID'," . $kaza_data["meta_value"] . ")";
                }


                $sql = "INSERT INTO wp_usermeta(
                    `user_id`,
                    meta_key,
                    meta_value
                    ) VALUES " . implode(",", $metas);

                ids_db_query($sql);
            }
        }
    }
}

mail("s.merhi@ids.com.lb", "Makhateer", "New Makhateer: " . count($new_users));

//return $new_users;
