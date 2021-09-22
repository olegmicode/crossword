<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../../../admin/crossword/config/database.php';
  
// instantiate object
include_once '../../../admin/crossword/objects/score.php';
  
$database = new Database();
$db = $database->getConnection();
  
$scoreObj = new Score($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$message = '';
if(!empty($data->userId)) {
    
    $scoreObj->user_key = $data->userId;
    $scoreObj->getByUserKey();

    if(!empty($scoreObj->user_key)) {

        // construct response
        $result = [
            "userId" => $scoreObj->user_key,
            "data" => (!empty($scoreObj->scores)) ? json_decode($scoreObj->scores) : null
        ];

        http_response_code(200);
        echo json_encode(array(
            "message" => 'Record found',
            "result" => $result
        ));
    } else {

        // set response code - 400 bad request
        http_response_code(200);
        echo json_encode(array(
            "result" => null,
            "message" => "Unable to get score record. User Id not found."
        ));
    }
}
else {
    // set response code - 400 bad request
    http_response_code(200);
    echo json_encode(array("message" => "Unable to get score record. Data is incomplete."));
}