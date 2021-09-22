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

$limit = 100;
$totalSynced = $data->totalSynced;
$totalRecord = $data->totalRecord;

$newTotalSynced = $totalSynced + $limit;
if($newTotalSynced > $totalRecord) {
    $question->perpage = $newTotalSynced - $totalRecord;
} else {
    $question->perpage = $limit;
}

$question->offset = $totalSynced;

// query question
$statement = $question->getQuestions();
$num = $statement->rowCount();

if($num > 0) {
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $answerChars = strlen($correct_answer);
        $incorrect_answer1Chars = strlen($incorrect_answer1);
        $incorrect_answer2Chars = strlen($incorrect_answer2);
        $incorrect_answer3Chars = strlen($incorrect_answer3);

        if( $answerChars > 60 OR
            $incorrect_answer1Chars > 60 OR
            $incorrect_answer2Chars > 60 OR
            $incorrect_answer3Chars > 60
        ) {
            $deletedQuestion = New Question($db);
            $deletedQuestion->id = $id;
            $deletedQuestion->flagDeleted();
        }
    }

    http_response_code(200);
    echo json_encode(array(
        "message" => "Question deleted.",
        "totalSynced" => $newTotalSynced,
        "totalRecord" => $totalRecord
    ));

} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to delete question."));
}