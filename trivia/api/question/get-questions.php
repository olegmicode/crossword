<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../../../admin/trivia/config/database.php';
include_once '../../../admin/trivia/objects/question.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$question = new Question($db);

// params: page, perpage,
$data = json_decode(file_get_contents("php://input"));

$page = (!empty($data->page)) ? $data->page : 1;
$question->perpage = (!empty($data->perpage)) ? $data->perpage : 50;

$question->offset = ($page>1) ? ($page * $question->perpage) - $question->perpage : 0;
$question->rate = 1;
$statement = $question->getQuestions();
$rowCount = $statement->rowCount();

// check if more than 0 record found
if($rowCount>0){
    
    // questions array
    $questions_arr = array();
    $questions_arr["records"] = array();
  
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
        
        $available_img = (!empty($scrap_image)) ? $scrap_image : $scrap_image_question;
        $scrap_image_prefered = (!empty($is_alt_img)) ? $scrap_image_question : $available_img;
        $scrap_image_selected = (!empty($scrap_image_prefered)) ? $scrap_image_prefered : null;
        
        $question_item = array(
            "id" => $id,
            "category_id" => $category_id,
            "question" => html_entity_decode($question),
            "correct_answer" => $correct_answer,
            "incorrect_answer1" => $incorrect_answer1,
            "incorrect_answer2" => $incorrect_answer2,
            "incorrect_answer3" => $incorrect_answer3,
            "question_source" => $question_source,
            "country_spesific" => $country_spesific,
            "reported" => $reported,
            "scrap_title" => $scrap_title,
            "answer_info" => $answer_info,
            "scrap_image" => $scrap_image_selected,
            "correct_count" => $correct_count,
            "incorrect_count" => $incorrect_count,
            "rate" => $rate,
            "is_alt_img" => $is_alt_img,
            "status" => $status,
            "created" => $created,
            'slug' => $slug
        );
  
        array_push($questions_arr["records"], $question_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($questions_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no questions found
    echo json_encode(
        array("message" => "No questions found.")
    );
}