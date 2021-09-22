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
include_once '../../../admin/wordsearch/objects/level.php';
include_once '../../../admin/wordsearch/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->name) &&
    !empty($data->permalink)
){
    $level->id = $data->id;
    $level->name = $data->name;
    $level->category_id = $data->category_id;
    $level->description = $data->description;
    $level->alphabet = $data->alphabet;
    $level->size = $data->size;
    $level->hint = $data->hint;
    $level->time_interval = $data->time_interval;
    $level->point_deduction = $data->point_deduction;
    $level->initial_score = $data->initial_score;
    $level->difficulty = $data->difficulty;
    $level->modified = date('Y-m-d H:i:s');
    $level->permalink = $data->permalink;

    if($level->update())
    {
        $category = new Category($db);

        $category->id = $data->category_id;
        $category->getById();

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array(
            "message" => "Data was updated.",
            "category_name" => (!empty($category->name)) ? $category->name : '-'
        ));
    }  
    else
    {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to update the data."));
    }
    
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update the level. Data is incomplete."));
}
