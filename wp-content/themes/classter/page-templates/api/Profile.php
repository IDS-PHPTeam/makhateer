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

  $Makhteer->CId  =isset($_GET['CId']) ? $_GET['CId'] : die();

  // Get post
  $Makhteer->read_Profile();
  
  // Create array
   $Makhteer_arr = array(
     'id' => $Makhteer->Id,
     'name' => $Makhteer->Name,
     'phone' => $Makhteer->Phone,
     'CID' => $Makhteer->CId,
     'res'=> $Makhteer->res,
     'dob' =>$Makhteer->DOB,
     'address'=> $Makhteer->Address,
     'regionCode'=>$Makhteer->RegionCode,
     'memberOfBoardDirector'=>$Makhteer->MemberOfBoardDirector,
     'activeElection'=>$Makhteer->ActiveElection
    
   );

   // Make JSON
   print_r(json_encode($Makhteer_arr));

?>
