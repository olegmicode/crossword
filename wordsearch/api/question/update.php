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
include_once '../../../admin/wordsearch/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);
$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->category_id) &&
    !empty($data->question) &&
    !empty($data->correct_answer) &&
    !empty($data->incorrect_answer1) &&
    !empty($data->incorrect_answer2) &&
    !empty($data->incorrect_answer3) &&
    !empty($data->question_source)
){
    
    // set product property values
    $question->id = $data->id;
    $question->category_id = $data->category_id;
    $question->question = $data->question;
    $question->correct_answer = $data->correct_answer;
    $question->incorrect_answer1 = $data->incorrect_answer1;
    $question->incorrect_answer2 = $data->incorrect_answer2;
    $question->incorrect_answer3 = $data->incorrect_answer3;
    $question->question_source = $data->question_source;
    $question->country_spesific = !empty($data->country_spesific) ? $data->country_spesific : "";
    // $question->correct_count = 0;
    // $question->incorrect_count = 0;
    // $question->reported = 0;
    // $question->status = 1;
    // $question->created = date('Y-m-d H:i:s');

    $category->id = $data->category_id;
    $category->getById();

    if($question->update())
    {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array(
            "message" => "Question was updated.",
            "category_name" => (!empty($category->name)) ? $category->name : 'General Knowledge'
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
