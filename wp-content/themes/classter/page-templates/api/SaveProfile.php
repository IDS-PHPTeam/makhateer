
<?php


include_once '../models/MokhtarResponse.php';
include_once '../config/config.php';

include_once '../config/Database.php';
include_once '../models/Makhateer.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
// Instantiate blog category object
$Makhteer = new Makhateer($db);

$Config = new config();

$entityBody = file_get_contents('php://input');

$Profile = json_decode($entityBody, true);



$Makhteer = new Makhateer($db);

$Makhteer->CId  = $Profile["cID"];
$Makhteer->address = $Profile["address"];
$Makhteer->name = $Profile["name"];
$Makhteer->phone = $Profile["phone"];
$result = $Makhteer->update();
if ($result) {
    echo json_encode(
        array('message' => 'Changes Done Successfully', 'success' => true)
    );
} else {
    echo json_encode(
        array('message' => 'Error Occured', 'success' => false)
    );
}
?>

