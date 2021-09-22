<?php
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
if(!empty($data->question_id)){
    // get question
    $question = new Question($db);
    $question->id = $data->question_id;
    $question->getQuestionById();

    if(!empty($question->question)) {
        $updatedQuestion = new Question($db);

        $newCorrect_count = (!empty($data->correct_count)) ? $data->correct_count : 0;
        $newIncorrect_count = (!empty($data->incorrect_count)) ? $data->incorrect_count : 0;
        $newReported = (!empty($data->reported)) ? $data->reported : 0;

        $updatedQuestion->id = $data->question_id;
        $updatedQuestion->incorrect_count = $question->incorrect_count + $newIncorrect_count;
        $updatedQuestion->correct_count = $question->correct_count + $newCorrect_count;
        $updatedQuestion->reported = $question->reported + $newReported;
        $updatedQuestion->ingame_occurrence = $question->ingame_occurrence +1;

        if($updatedQuestion->setStatistic())
        {
            // set response code - 201 created
            http_response_code(201);
            echo json_encode(array(
                "message" => "Question statistic updated."
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
        echo json_encode(array("message" => "Unable to update question."));
    }
}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update the question. Data is incomplete."));
}
