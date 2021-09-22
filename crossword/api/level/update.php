<?php
require_once '../../../admin/auth.php';
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
    !empty($data->id) &&
    !empty($data->name) &&
    !empty($data->description) &&
    !empty($data->width) &&
    !empty($data->height) &&
    !empty($data->difficulty) &&
    !empty($data->permalink)
){
    
    $level->id = $data->id;
    $level->name = $data->name;
    $level->category_id = $data->category_id;
    $level->description = $data->description;
    $level->width = $data->width;
    $level->height = $data->height;
    $level->difficulty = $data->difficulty;
    $level->modified = date('Y-m-d H:i:s');
    $level->permalink = $data->permalink;
    
    $board_change_status = true;
    if(!empty($data->is_board_changed) && $data->is_board_changed){
        $level->board_json = null;
        $board_change_status = $level->updateBoardJSON();
    }

    if($board_change_status && $level->update())
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
