<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../config/Database.php';
  include_once '../models/Makhateer.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate  object
  $category = new Makhateer($db);

  $result = $category->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any categories
  if($num > 0) {
        $cat_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cat_item = array(
            'id' => $id,
            'fullName' => $name,
            'mokhtarId'=>$mokhtarId,
            'cId' => $cId,
            'phone' =>  $phone,
            'address'=> $address,
            'regionCode'=>$regionCode,
            'memberOfBoardDirector'=>$memberOfBoardDirector,
            'activeElection'=>$activeElection
          );

          // Push to "data"
          array_push($cat_arr, $cat_item);
        }

        // Turn to JSON & output
       echo json_encode(array( 'success'=>true,'message'=>'Successfully done','data'=>$cat_arr));
   
  } else {
      
    echo json_encode(
      array('data'=>[],'message' => 'No Makhateer Found','success' =>false)
    );
  }

  ?>