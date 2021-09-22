<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/crossword/config/database.php';
  
// instantiate object
include_once '../../../admin/crossword/objects/level.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->id)){
    
    $level->id = $data->id;
    $level->board_json = !empty($data->board_json) ? $data->board_json : null;
    $level->modified = date('Y-m-d H:i:s');
    
    if($level->updateBoardJSON())
    {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array(
            "message" => "Data was updated."
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