<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../../../admin/wordsearch/config/database.php';
  
// instantiate object
include_once '../../../admin/wordsearch/objects/word.php';
include_once '../../../admin/wordsearch/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$word = new Word($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->id) &&
    !empty($data->name)
){
    $word->id = $data->id;
    $word->name = $data->name;
    $word->description = $data->description;
    $word->category_id = (!empty($data->category_id)) ? $data->category_id : 0;

    if($word->update())
    {
        $category = new Category($db);

        $category->id = $data->category_id;
        $category->getById();

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array(
            "message" => "Data was updated.",
            "category_name" => (!empty($category->name)) ? $category->name : '-'
        ));
    }  
    else
    {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to update the data."));
    }
    
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update the word. Data is incomplete."));
}
