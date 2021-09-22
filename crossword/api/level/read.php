<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../../../admin/crossword/config/database.php';
include_once '../../../admin/crossword/objects/level.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$level = new Level($db);

// query question
$level->deleted = 0;
$statement = $level->getLevels();
$num = $statement->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // questions array
    $data_arr = array();
    $data_arr["records"] = array();
  
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
  
        $item = array(
            "id" => $id,
            "name" => html_entity_decode($name),
            "description" => html_entity_decode($description),
            "width" => $width,
            "height" => $height,
            "difficulty" => $difficulty,
            "status" => $status,
            "deleted" => $deleted,
            "permalink" => $permalink,
            "board_json" => $board_json
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
        array("message" => "No levels found.")
    );
}