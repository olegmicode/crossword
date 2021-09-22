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
include_once '../../../admin/crossword/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->category_id)){
    
    // get category name
    $category = new Category($db);
    $category->id = $data->category_id;
    $category->getById();

    if(!empty($category->id)) {
        
        // loop question id
        $question = new Question($db);
        foreach($data->questions as $questionId) {
            $question->id = $questionId;
            $question->category_id = $category->id;

            $question->categorySync();
        }

        http_response_code(200);
        echo json_encode(array(
            "category_name" => $category->name
        ));
    }
    else {
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
