<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../../../admin/crossword/config/database.php';
include_once '../../../admin/crossword/objects/UserSetting.php';

// instantiate database object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$setting = new UserSetting($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->setting_param)  && !empty($data->user_key) && !empty($data->setting_value)){
    
    $setting->user_key = $data->user_key;
    $setting->setting_param = $data->setting_param;
    $setting->setting_value = $data->setting_value;
    
    if($setting->setSetting()){
 
        http_response_code(200);
        echo json_encode(
            array(
                "status" => 'success',
                "message" => "Setting saved."
            )
        );
    }
    else {
        http_response_code(200);
        echo json_encode(
            array(
                "status" => 'error',
                "message" => "Setting not saved."
            )
        );
    }
}
else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to get setting. Data is incomplete."));
}