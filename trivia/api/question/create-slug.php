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
$statement = $questions->getQuestionsWithNoSlug();
$num = $statement->rowCount();

if($num > 0) {
    $questionResult = array();
    $i=0;
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $questionToSluggify = new Question($db);

        $questionToSluggify->question = $question;
        if(empty($questionToSluggify->slug)) {
            $questionToSluggify->slugify();
        }

        if(!empty($questionToSluggify->slug)) {
            
            // save slug
            $questionToSluggify->id = $id;
            $response = $questionToSluggify->setSlug();
            
            $questionResult[] = [
                'id' => $id,
                'question' => $question,
                'slug' => $questionToSluggify->slug
            ];
        }

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