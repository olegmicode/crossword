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
include_once '../../../admin/crossword/objects/question.php';
include_once '../../../admin/crossword/objects/level.php';

$database = new Database();
$db = $database->getConnection();

$level = new Level($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id)){
    
    $question = new Question($db);
    $question->id = $data->id;
    $question->getQuestionById();
    
    $questionToUpdate = new Question($db);
    $questionToUpdate->id = $data->id;

    if($question->status == 1){
        $questionToUpdate->status = 0;
    } else {
        $questionToUpdate->status = 1;
    }

    $level->id = $question->level_id;
    $level->board_json = null;
    $level->modified = date('Y-m-d H:i:s');
    $level->updateBoardJSON();

    $questionToUpdate->setStatus();
    
    http_response_code(200);
    echo json_encode(array(
        "status" => $questionToUpdate->status,
        "id" => $questionToUpdate->id
    ));
    
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the question. Data is incomplete."));
}
