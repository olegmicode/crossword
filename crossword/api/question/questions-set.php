<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../../../admin/crossword/config/database.php';
include_once '../../../admin/crossword/objects/question.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$question = new Question($db);

$data = json_decode(file_get_contents("php://input"));

$question->level_id = $data->level_id;

// query question
$statement = $question->getByLevel();
$num = $statement->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // questions array
    $questions_arr = array();
    $questions_arr["records"] = array();
    $questions_arr["totalRecords"] = 0;
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $question_item = array(
            "id" => $id,
            "level_id" => $level_id,
            "category_id" => $category_id,
            "question" => html_entity_decode($question),
            "correct_answer" => $correct_answer,
            "question_source" => $question_source,
            "country_spesific" => $country_spesific,
            "reported" => $reported,
            "status" => $status,
            "created" => $created
        );
  
        array_push($questions_arr["records"], $question_item);
        $questions_arr["totalRecords"] ++;
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