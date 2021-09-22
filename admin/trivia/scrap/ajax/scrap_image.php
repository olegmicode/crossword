<?php
header("Content-Type: application/json");

include_once '../../config/database.php';
include_once '../../objects/question.php';

include_once '../../../../vendor/autoload.php';

$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);
$question2 = new Question($db);

$id = isset($_GET['id']) ? $_GET["id"] : null;
$scrap_by = isset($_GET['scrap_by']) ? $_GET["scrap_by"] : "site";

$question->id = $id;
$question->getQuestionById();

$question_source = trim($question->question_source);
$question_source = trim(explode("#", $question_source)[0]);

$data = [];
$data['id'] = $question->id;
$data['question'] = $question->question;
$data['question_source'] = $question_source;
$data['prev']['scrap_image'] = $question->scrap_image;

# set up the request parameters
$queryString = null;
if ($scrap_by == "site") {
    $queryString = http_build_query([
        'api_key' => '2CB7EB94FA4A45C7AF7841DA88BFCFF8',
        'location' => "Sydney CBD,New South Wales,Australia",
        'search_type' => "images",
        'q' => 'site: '.$question_source
    ]);
}elseif ($scrap_by == "correct_answer") {
    $queryString = http_build_query([
        'api_key' => '2CB7EB94FA4A45C7AF7841DA88BFCFF8',
        'location' => "Sydney CBD,New South Wales,Australia",
        'search_type' => "images",
        'q' => ''.$question->correct_answer
    ]);
}elseif ($scrap_by == "question") {
    $queryString = http_build_query([
        'api_key' => '2CB7EB94FA4A45C7AF7841DA88BFCFF8',
        'location' => "Sydney CBD,New South Wales,Australia",
        'search_type' => "images",
        'q' => ''.$question->question
    ]);
}

if (!empty($queryString)) {
    # make the http GET request to Scale SERP
    $ch = curl_init(sprintf('%s?%s', 'https://api.scaleserp.com/search', $queryString));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $api_result = curl_exec($ch);
    curl_close($ch);

    # print the JSON response from Scale SERP
    $jDecode = json_decode($api_result, true);
    $image = "need update";
    $imageResults = !empty($jDecode['image_results']) ? $jDecode['image_results'] : null;
    if (!empty($imageResults)) {
        $loop = 0;
        foreach ($imageResults as $key => $row) {
            if ($scrap_by == 'site') {
                $link = !empty($row['link']) ? urldecode($row['link']) : null;
                if ($link == $data['question_source']) {
                    $image = !empty($row['image']) ? $row['image'] : null;
                    $loop = $key;
                    break;
                }
            }elseif ($scrap_by == 'correct_answer' || $scrap_by == 'question') {
                if ($key == 0) {
                    $image = !empty($row['image']) ? $row['image'] : null;
                    $loop = $key;
                    break;
                }
            }
        }

        if ($scrap_by == "correct_answer") {
            $data['update'] = $question2->updateImage($id, $image, 2, 'scrap_image');
        }elseif ($scrap_by == "site") {
            $data['update'] = $question2->updateImage($id, $image, 2, 'scrap_image');
        }elseif ($scrap_by == "question") {
            $data['update'] = $question2->updateImage($id, $image, 2, 'scrap_image_question');
        }

        $data['loop'] = $loop;
        $data['current']['scrap_image'] = $image;
        // $data['imageResults'] = $imageResults;

        http_response_code(200);
        echo json_encode([
            "success" => 1,
            "message" => "",
            "queryString" => $queryString,
            "data" => $data
        ]);
    }else{
        $question2->updateImage($id, $image, 2);
        http_response_code(200);
        echo json_encode([
            "success" => 0,
            "message" => "No scrap result",
            "queryString" => $queryString,
            "data" => $data,
            "raw" => $jDecode
        ]);
    }
}else{
    $question2->updateImage($id, $image, 2);
    http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "queryString not found",
        "queryString" => $queryString,
        "data" => $data
    ]);
}
?>