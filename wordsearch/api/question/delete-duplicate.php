<?php
require_once '../../../admin/auth.php';
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/wordsearch/config/database.php';
  
// instantiate object
include_once '../../../admin/wordsearch/objects/question.php';
  
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
        $questionChars = strlen($question);
        $inGameQuestion = substr($question,0,180);
        if($questionChars > 180) {
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