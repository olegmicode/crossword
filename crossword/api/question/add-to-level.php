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

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id) && !empty($data->level_id)){
    
    // get total question inside this level
    $level = new Level($db);
    $level->id = $data->level_id;
    $level->getById();

    if($level->totalQuestion < 40) {

        // check if answer length is lower than grid size
        
        $question = new Question($db);
        $question->id = $data->id;
        $question->getQuestionById();
        $answerChars = strlen($question->correct_answer);

        $isAnswerFit = ($answerChars <= $level->width);

        if($isAnswerFit) {
            $questionToUpdate = new Question($db);
            $questionToUpdate->id = $data->id;

            $toggleStatus = '';

            if($question->level_id == 0){
                // set to new level
                $questionToUpdate->level_id = $data->level_id;
                $toggleStatus = 'add';
            } else {
                // set to defined level
                $questionToUpdate->level_id = 0;
                $toggleStatus = 'remove';
            }

            $level->board_json = null;
            $level->modified = date('Y-m-d H:i:s');
            $level->updateBoardJSON();

            $questionToUpdate->setLevel();
            
            http_response_code(200);
            echo json_encode(array(
                "question" => $question,
                "questionToUpdate" => $questionToUpdate,
                "toggleStatus" => $toggleStatus,
                "totalQuestion" => $level->totalQuestion
            ));
        } else {
            http_response_code(200);
            echo json_encode(array(
                "question" => null,
                "questionToUpdate" => null,
                "toggleStatus" => 'word_too_long',
                "message" => "Answer is longer than grid size"
            ));
        }
        

    } else {
        http_response_code(200);
        echo json_encode(array(
            "question" => null,
            "questionToUpdate" => null,
            "toggleStatus" => 'limit_exceeded',
            "message" => "Total question limit exceeded (limit per level: 40 questions)",
            "totalQuestion" => $level->totalQuestion
        ));
    }
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the question. Data is incomplete."));
}
