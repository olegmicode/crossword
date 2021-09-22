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
include_once '../../../admin/crossword/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);
$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->question) &&
    !empty($data->correct_answer) &&
    !empty($data->country_spesific)
){
    
    // set product property values
    $question->id = $data->id;
    $question->category_id = 1;
    $question->question = $data->question;
    $question->correct_answer = $data->correct_answer;
    $question->incorrect_answer1 = "";
    $question->incorrect_answer2 = "";
    $question->incorrect_answer3 = "";
    $question->question_source = "";
    $question->country_spesific = $data->country_spesific;
    
    if($question->update())
    {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array(
            "message" => "Question was updated."
        ));
    }
    else
    {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to update question."));
    }
    
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create question. Data is incomplete."));
}
