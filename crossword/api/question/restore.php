<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/crossword/config/database.php';
  
// instantiate object
include_once '../../../admin/crossword/objects/question.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$question = new Question($db);
  
// get id
$data = json_decode(file_get_contents("php://input"));
  
// set id to be deleted
$question->id = $data->id;
  
// delete the product
if($question->restoreDeleted()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Question is restored."));
}
  
// if unable to delete the product
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to restore the question."));
}