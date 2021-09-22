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
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare object
$questions = new Question($db);
  
// get id
$data = json_decode(file_get_contents("php://input"));

$limit = 100;
$totalSynced = $data->totalSynced;
$totalRecord = $data->totalRecord;

$newTotalSynced = $totalSynced + $limit;
if($newTotalSynced > $totalRecord) {
    $questions->perpage = $newTotalSynced - $totalRecord;
} else {
    $questions->perpage = $limit;
}

$questions->offset = $totalSynced;

// query question
$statement = $questions->getEscapedQuestion();
$num = $statement->rowCount();

if($num > 0) {
    $questionResult = array();
    $i=0;
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        // $cleanQuestion = str_replace('amp;amp;amp;', "", $question);
        // $cleanQuestion = str_replace('amp;', "'", $cleanQuestion);

        // $cleanCorrect_answer = str_replace('quot;', "'", $correct_answer);
        // $cleanCorrect_answer = str_replace('amp;', "'", $cleanCorrect_answer);

        // $cleanIncorrect_answer1 = str_replace('quot;', "'", $incorrect_answer1);
        // $cleanIncorrect_answer1 = str_replace('amp;', "'", $cleanIncorrect_answer1);

        // $cleanIncorrect_answer2 = str_replace('quot;', "'", $incorrect_answer2);
        // $cleanIncorrect_answer2 = str_replace('amp;', "'", $cleanIncorrect_answer2);

        // $cleanIncorrect_answer3 = str_replace('quot;', "'", $incorrect_answer3);
        // $cleanIncorrect_answer3 = str_replace('amp;', "'", $cleanIncorrect_answer3);
        
        
        $cleanScrapedContent = str_replace("''quot;", "", $answer_info);
        
        if($answer_info != $cleanScrapedContent) {
            $questionResult[$i]['old_answer_info'] = $answer_info;
            $questionResult[$i]['clean_answer_info'] = $cleanScrapedContent;
        }

        // $questionFormatted = new Question($db);
        // update the field
        // $questionFormatted->id = $id;
        // $questionFormatted->getQuestionById();
        // $questionFormatted->question = $cleanQuestion;
        // $questionFormatted->answer_info = $cleanScrapedContent;
        // $questionFormatted->correct_answer = $cleanCorrect_answer;
        // $questionFormatted->incorrect_answer1 = $cleanIncorrect_answer1;
        // $questionFormatted->incorrect_answer2 = $cleanIncorrect_answer2;
        // $questionFormatted->incorrect_answer3 = $cleanIncorrect_answer3;

        // $questionFormatted->update();

        $i++;
    }

    http_response_code(200);
    echo json_encode(array(
        "message" => "Question updated.",
        "totalSynced" => $newTotalSynced,
        "totalRecord" => $totalRecord,
        "result" => $questionResult
    ));

} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update question."));
}