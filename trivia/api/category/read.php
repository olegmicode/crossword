<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/trivia/config/database.php';
include_once '../../../admin/trivia/objects/category.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$category = new Category($db);

// query question
$statement = $category->getCategories();
$num = $statement->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // questions array
    $data_arr = array();
    $data_arr["records"] = array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $item = array(
            "id" => $id,
            "name" => $name,
            "difficulty" => $difficulty,
            "totalQuestion" => $totalQuestion
        );
  
        array_push($data_arr["records"], $item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($data_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no questions found
    echo json_encode(
        array("message" => "No questions found.")
    );
}