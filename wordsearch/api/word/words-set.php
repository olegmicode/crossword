<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../../../admin/wordsearch/config/database.php';
include_once '../../../admin/wordsearch/objects/word.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$word = new Word($db);

$data = json_decode(file_get_contents("php://input"));

$word->level_id = $data->level_id;

// query question
$statement = $word->getByLevel();
$num = $statement->rowCount();

// check if more than 0 record found
if($num>0){
  
    // questions array
    $questions_arr = array();
    $questions_arr["records"] = array();
  
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $question_item = array(
            "id" => $id,
            "name" => html_entity_decode($name),
            "description" => $description
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