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

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id) && !empty($data->level_id)){
    
    $word = new Word($db);
    $word->id = $data->id;
    $word->getById();
    
    $wordToUpdate = new Word($db);
    $wordToUpdate->id = $data->id;

    $toggleStatus = '';

    if($word->level_id == 0){
        // set to new level
        $wordToUpdate->level_id = $data->level_id;
        $toggleStatus = 'add';
    } else {
        // set to defined level
        $wordToUpdate->level_id = 0;
        $toggleStatus = 'remove';
    }

    $wordToUpdate->setLevel();
    
    http_response_code(200);
    echo json_encode(array(
        "word" => $word,
        "wordToUpdate" => $wordToUpdate,
        "level_id" => $wordToUpdate->level_id,
        "id" => $wordToUpdate->id,
        "toggleStatus" => $toggleStatus
    ));
    
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the word. Data is incomplete."));
}
