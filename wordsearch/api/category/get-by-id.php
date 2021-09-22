<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/wordsearch/config/database.php';
include_once '../../../admin/wordsearch/objects/category.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// query question
$category->id = $data->id;
$category->getById();
  
// check if more than 0 record found
if(!empty($category->name)){

    // question array
    $data_arr = array(
        "id" => $category->id,
        "name" => html_entity_decode($category->name)
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