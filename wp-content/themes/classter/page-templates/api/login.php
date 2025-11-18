<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../config/Database.php';
  include_once '../models/Makhateer.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  // Instantiate blog category object
  $Makhteer = new Makhateer($db);

  // Get ID
  //$Makhteer->id = isset($_GET['username']) ? $_GET['username'] : die();
  $Makhteer->CId  =isset($_GET['CId']) ? $_GET['CId'] : die();
$Makhteer->Password =isset($_GET['Password']) ? $_GET['Password'] : die();

  // Get post
  $Makhteer->read_single();
  
  $message=($Makhteer->res == true ? "Successfully Logged In" : " Wrong Username or Password");
  // Create array
   $Makhteer_arr = array(
     'id' => $Makhteer->Id,
     'fullName' => $Makhteer->Name,
     'phone' => $Makhteer->Phone,
     'CID' => $Makhteer->CId
    
   );
   session_start();
   $_SESSION["loggedIn"] = "true";

   echo json_encode(
    array('message' => $message,'success' =>$Makhteer->res,'data'=>$Makhteer_arr)
  );

?>
