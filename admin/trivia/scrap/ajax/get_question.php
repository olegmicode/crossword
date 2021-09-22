<?php
// required headers
header("Content-Type: application/json");

// include database and object files
include_once '../../config/database.php';
include_once '../../objects/question.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$question = new Question($db);

// get posted data
$perpage = !empty($_GET['perpage']) ? (int)$_GET['perpage'] : 1;
$offset = !empty($_GET['offset']) ? (int)$_GET['offset'] : 0;
$baseQuery = !empty($_GET['baseQuery']) ? $_GET['baseQuery'] : "";

$response = $question->getQuestionByQuery($baseQuery, $perpage, $offset);
$query = $response['query'];
$statement = $response['statement'];
$rowCount = $statement->rowCount();

if($rowCount>0){
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode([
        "success" => 1,
        "message" => "",
        "query" => $query,
        "data" => $statement->fetchAll(PDO::FETCH_ASSOC)
    ]);
}
else{
  
    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no questions found
    echo json_encode(
        array(
            "success" => 0,
            "query" => $query,
            "message" => "No questions found.",
        )
    );
}
?>