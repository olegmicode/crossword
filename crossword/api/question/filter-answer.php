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
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$question = new Question($db);
  
// get id
$data = json_decode(file_get_contents("php://input"));
  

if(!empty($data->id)) {
    $question->id = $data->id;
    $question->getQuestionById();

    if(!empty($question->question)) {
        // check the spaces
        $trimmedAnswer = trim($question->correct_answer);
        $isHavingSpacing = (strpos($trimmedAnswer, ' ') !== false);

        // delete them if having space
        if($isHavingSpacing) {
            if($question->flagDeleted()){
                http_response_code(200);
                echo json_encode(array("message" => "Question deleted.", "status" => "deleted", "answer" => $question->correct_answer));
            } else {
                http_response_code(200);
                echo json_encode(array("message" => "Failed deleting Question.", "status" => "deletion-failed", "answer" => $question->correct_answer));
            }
        } else {
            http_response_code(200);
            echo json_encode(array("message" => "Question skipped.", "status" => "skip", "answer" => $question->correct_answer));
        }
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("message" => "Data incomplete."));
}


// delete the product
// if($question->flagDeleted()){
  
//     // set response code - 200 ok
//     http_response_code(200);
  
//     // tell the user
//     echo json_encode(array("message" => "Question was deleted."));
// }