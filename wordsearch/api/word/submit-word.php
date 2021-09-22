<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/wordsearch/config/database.php';
  
// instantiate object
include_once '../../../admin/wordsearch/objects/word.php';
  
$database = new Database();
$db = $database->getConnection();
  
$word = new Word($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if( !empty($data->name) ){

    // check first if the question is exist
    $word->name = $data->name;
    $word->getByName();

    if(empty($word->id)  || (!empty($data->can_duplicated) && $data->can_duplicated)) {
        // set property values
        $word->name = $data->name;
        $word->level_id = (!empty($data->level_id)) ? $data->level_id : 0;
        $word->category_id = (!empty($data->category_id)) ? $data->category_id : 0;
        $word->status = 1;
        $word->description = $data->description;
        $word->modified = date('Y-m-d H:i:s');
        $word->created = date('Y-m-d H:i:s');
        $word->cookie_id = (!empty($data->cookie_id)) ? $data->cookie_id : "";
        $response = $word->create();
        if($response)
        {
            // set response code - 201 created
            http_response_code(201);

            $word->getByName();
            
            // tell the user
            echo json_encode(
                array(
                    "message" => $response,
                    "id" => $word->id
                )
            );
        }  
        else
        {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create word."));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => 'duplicated'));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create word. Data is incomplete."));
}
