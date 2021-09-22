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
include_once '../../../admin/wordsearch/objects/score.php';
  
$database = new Database();
$db = $database->getConnection();
  
$scoreObj = new Score($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->levelId) && !empty($data->userId)){

    $dataScore = !empty($data->score) ? $data->score : 0;

    $scoreObj->user_key = $data->userId;
    $scoreObj->getByUserKey();
    // print_r($scoreObj); exit;
    if(!empty($scoreObj->user_key)) {
        
        // get cookie, reconstruct it
        $scoresData = $scoreObj->scores;
        (!empty($scoresData)) && $scoresDataDecoded = json_decode($scoresData);
        if(!empty($scoresDataDecoded) && !empty($scoresDataDecoded->scores)) {

            // re construct the array, for save existing data
            $scoreExist = false;
            $i = 0;
            foreach($scoresDataDecoded->scores as $score) {

                if($score->levelId == $data->levelId) {
                    $scoresNew[$i] = [
                        "levelId" => $data->levelId,
                        "score" => $dataScore
                    ];
                    $scoreExist = true;
                } else {
                    $scoresNew[$i] = [
                        "levelId" => $score->levelId,
                        "score" => $score->score
                    ];
                }
                $i++;
            }
            
            // if not exist, insert new score
            if(!$scoreExist) {
                $scoresNew[$i] = [
                    "levelId" => $data->levelId,
                    "score" => $dataScore
                ];
            }

        } else {
            // first time insert
            $scoresNew[0] = [
                "levelId" => $data->levelId,
                "score" => $dataScore
            ];
        }

        // print_r($scoresNew);
        // exit;
        $cookie_value = [
            "scores" => $scoresNew,
            "date" => date('Y-m-d h:i:s')
        ];
        $cookie_value_encoded = json_encode($cookie_value);
        
        $scoreObj->scores = $cookie_value_encoded;
        $scoreObj->user_key = $data->userId;
        $scoreObj->updateScores();

        // construct response
        $result = [
            "userId" => $data->userId,
            "data" => $cookie_value
        ];

        http_response_code(200);
        echo json_encode(array(
            "message" => "success",
            "result" => $result
        ));

    }
    else {
        // cannot save
        http_response_code(200);
        echo json_encode(array("message" => "cannot save the score. Record is not found."));
    }

}
  
// tell the user data is incomplete
else {
    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Unable to record score. Data is incomplete.", "data" => $data));
}
