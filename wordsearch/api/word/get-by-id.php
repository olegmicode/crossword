<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/wordsearch/config/database.php';
include_once '../../../admin/wordsearch/objects/word.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$word = new Word($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// query question
$word->id = $data->id;
$word->getById();
  
// check if more than 0 record found
if(!empty($word->name)){

    // question array
    $data_arr = array(
        "id" => $word->id,
        "name" => html_entity_decode($word->name),
        "description" => html_entity_decode($word->description),
        "category_id" => html_entity_decode($word->category_id),
        "category_name" => html_entity_decode($word->category_name),
    );

    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($data_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no questions found
    echo json_encode(
        array("message" => "No level found.")
    );
}