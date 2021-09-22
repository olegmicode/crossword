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
    $questionResult = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        
        $questionResult[$id][] = ucfirst($correct_answer);
        $questionResult[$id][] = ucfirst($incorrect_answer1);
        $questionResult[$id][] = ucfirst($incorrect_answer2);
        $questionResult[$id][] = ucfirst($incorrect_answer3);

        $questionFormatted = new Question($db);
        // update the field
        $questionFormatted->id = $id;
        $questionFormatted->correct_answer = ucfirst($correct_answer);
        $questionFormatted->incorrect_answer1 = ucfirst($incorrect_answer1);
        $questionFormatted->incorrect_answer2 = ucfirst($incorrect_answer2);
        $questionFormatted->incorrect_answer3 = ucfirst($incorrect_answer3);

        $questionFormatted->formatAnswer();
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