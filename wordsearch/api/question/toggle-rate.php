<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/wordsearch/config/database.php';
  
// instantiate object
include_once '../../../admin/wordsearch/objects/question.php';
  
$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id)){
    
    $currentQuestion = new Question($db);
    $currentQuestion->id = $data->id;
    $currentQuestion->getQuestionById();
    
    $questionToUpdate = new Question($db);
    $questionToUpdate->id = $data->id;

    if($currentQuestion->rate == 1){
        $questionToUpdate->rate = 0;
    } else {
        $questionToUpdate->rate = 1;
    }

    $questionToUpdate->setRate();
    
    http_response_code(200);
    echo json_encode(array(
        "prev_rate" => $currentQuestion->rate,
        "rate" => $questionToUpdate->rate,
        "id" => $questionToUpdate->id
    ));
    
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the question. Data is incomplete."));
}
