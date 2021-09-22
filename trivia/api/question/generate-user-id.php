<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../../../admin/trivia/config/database.php';
  
// instantiate object
include_once '../../../admin/trivia/objects/score.php';
include_once '../../../admin/trivia/objects/UserSetting.php';
  
$database = new Database();
$db = $database->getConnection();
  
$scoreObj = new Score($db);
$settingObj = new UserSetting($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$scores_field = [
    'scores' => null,
    'date' => date('Y-m-d h:i:s')
];
$scores_field_encoded = json_encode($scores_field);
$message = '';
$scoreObj->user_key = 'YLCGamesTrivia-User-' . date('Ymdhis');
$scoreObj->scores = $scores_field_encoded;

if($scoreObj->generateUserKey()) {

    $settingObj->user_key = $scoreObj->user_key;
    $settingObj->setting_param = 'onboarding_state';
    $settingObj->setting_value = 0;
    $settingObj->addSetting();
    
    // construct response
    $result = [
        "userId" => $scoreObj->user_key,
        "data" => $scores_field
    ];

    http_response_code(200);
    echo json_encode(array(
        "message" => 'New record generated',
        "result" => $result
    ));
} 
else {
    http_response_code(200);
    echo json_encode(array(
        "message" => 'Failed to generate key',
        "result" => null
    ));
}

