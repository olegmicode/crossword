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
include_once '../../../admin/wordsearch/objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if( !empty($data->name) ){

    // check first if the question is exist
    $category->name = $data->name;
    $category->getByName();

    if(empty($category->id)) {
    
        // set property values
        $category->name = $data->name;
        $category->modified = date('Y-m-d H:i:s');
        $category->created = date('Y-m-d H:i:s');

        if($category->create())
        {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("message" => "Category was created."));
        }  
        else
        {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create category."));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "duplicated"));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
}
