<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/trivia/config/database.php';
  
// instantiate object
include_once '../../../admin/trivia/objects/question.php';
  
$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->id)){
    // get question
    $question = new Question($db);
    $question->id = $data->id;
    $question->getQuestionById();

    if(!empty($question->question)) {

        $updatedQuestion = new Question($db);
        $updatedQuestion->id = $data->id;
        if($data->isAlt) {
            // clear alt img
            $updatedQuestion->scrap_image_question = "";
            $response = $updatedQuestion->setScrapImageQuestion();
        } else {
            // clear main img
            $updatedQuestion->scrap_image = "";
            $response = $updatedQuestion->setScrapImage();
        }
        
        if($response)
        {
            // set response code - 201 created
            http_response_code(200);
            echo json_encode(array(
                "action" => (!empty($data->isAlt)) ? 'clearing alt image' : 'clearing main image',
                "message" => "question updated",
                "response" => $response
            ));
        }  
        else
        {
            // set response code - 400 bad request
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update question."));
        }
    }
    else
    {
        // set response code - 400 bad request
        http_response_code(400);
        echo json_encode(array("message" => "Question not found."));
    }
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the question. Data is incomplete."));
}
