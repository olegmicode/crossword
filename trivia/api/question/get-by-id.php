<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/trivia/config/database.php';
include_once '../../../admin/trivia/objects/question.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$question = new Question($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// query question
$question->id = $data->id;
$question->getQuestionById();
  
// check if more than 0 record found
if(!empty($question->question)){
  
    // question array
    $question_arr = array(
        "id" => $question->id,
        "category_id" => $question->category_id,
        "question" => html_entity_decode($question->question),
        "correct_answer" => $question->correct_answer,
        "incorrect_answer1" => $question->incorrect_answer1,
        "incorrect_answer2" => $question->incorrect_answer2,
        "incorrect_answer3" => $question->incorrect_answer3,
        "question_source" => $question->question_source,
        "country_spesific" => $question->country_spesific,
        "reported" => $question->reported,
        "status" => $question->status,
        "answer_info" => $question->answer_info,
        "created" => $question->created,
        "scrap_title" => $question->scrap_title,
        "scrap_answer" => $question->scrap_answer,
        "scrap_image" => $question->scrap_image,
        "scrap_image_question" => $question->scrap_image_question,
        "scrap_source_status" => $question->scrap_source_status,
        "is_alt_img" => $question->is_alt_img
    );

    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($question_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no questions found
    echo json_encode(
        array("message" => "No questions found.")
    );
}