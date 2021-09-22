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
include_once '../../../admin/crossword/objects/question.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->description) &&
    !empty($data->width) &&
    !empty($data->height) &&
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
        $level->width = $data->width;
        $level->height = $data->height;
        $level->status = 1;
        $level->difficulty = $data->difficulty;
        $level->deleted = 0;
        $level->modified = date('Y-m-d H:i:s');
        $level->created = date('Y-m-d H:i:s');
        $level->permalink = bin2hex(openssl_random_pseudo_bytes(5));
        $res = $level->create();

        if($res)
        {
            if(!empty($data->cookie_id)){
                $question = new Question($db);
                $level->getByName();

                $question->cookie_id = $data->cookie_id;
                $question->level_id = $level->id;
                $question->updateByCookieId();
            }

            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => $res));
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
