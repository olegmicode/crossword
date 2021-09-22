<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/crossword/config/database.php';
include_once '../../../admin/crossword/objects/level.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// query question
$level->id = $data->id;
$level->getById();
  
// check if more than 0 record found
if(!empty($level->name)){
  
    // question array
    $data_arr = array(
        "id" => $level->id,
        "category_id" => $level->category_id,
        "name" => html_entity_decode($level->name),
        "description" => html_entity_decode($level->description),
        "width" => $level->width,
        "height" => $level->height,
        "difficulty" => $level->difficulty,
        "status" => $level->status,
        "deleted" => $level->deleted,
        "permalink" => $level->permalink
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