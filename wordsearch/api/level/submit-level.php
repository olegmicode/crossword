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
include_once '../../../admin/wordsearch/objects/word.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
// print_r($data);
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->alphabet) &&
    !empty($data->size) &&
    !empty($data->hint) &&
    !empty($data->time_interval) &&
    !empty($data->point_deduction) &&
    !empty($data->difficulty)
){

    // check first if the question is exist
    $level->name = $data->name;
    $level->getByName();

    if(empty($level->id)) {
    
        // set property values
        $level->name = $data->name;
        $level->category_id = $data->category_id;
        $level->description = $data->description;
        $level->alphabet = $data->alphabet;
        $level->size = $data->size;
        $level->hint = $data->hint;
        $level->time_interval = $data->time_interval;
        $level->point_deduction = $data->point_deduction;
        $level->initial_score = $data->initial_score;
        $level->status = 1;
        $level->difficulty = $data->difficulty;
        $level->modified = date('Y-m-d H:i:s');
        $level->created = date('Y-m-d H:i:s');
        $level->permalink = bin2hex(openssl_random_pseudo_bytes(5));
        
        if($level->create())
        {
            // set response code - 201 created
            http_response_code(201);

            if(!empty($data->cookie_id)){
                $word = new Word($db);
                $level->getByName();

                $word->cookie_id = $data->cookie_id;
                $word->level_id = $level->id;
                $word->updateByCookieId();
            }

            // tell the user
            echo json_encode(array("message" => "Level was created."));
        }  
        else
        {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create level."));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "duplicated"));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create level. Data is incomplete."));
}
