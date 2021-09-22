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

// query question
$statement = $question->read();
$num = $statement->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // questions array
    $questions_arr = array();
    $questions_arr["records"] = array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
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
            "status" => $status,
            "created" => $created
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