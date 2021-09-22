<?php
header("Content-Type: application/json");

include_once '../../config/database.php';
include_once '../../objects/question.php';

include_once '../../../../vendor/autoload.php';
use Aws\S3\S3Client;
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'ap-southeast-2',
    'credentials' => [
        'key'    => 'AKIAWATYNZCK3QVPPR4C',
        'secret' => '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',
    ]
]);
  
$database = new Database();
$db = $database->getConnection();
  
$question = new Question($db);
$question2 = new Question($db);

$question->id = isset($_GET['id']) ? $_GET["id"] : null;
$question->getQuestionById();

// http_response_code(200);
// echo json_encode([
//     "success" => 1,
//     "message" => "",
//     "data" => $question
// ]);
// exit();

# set up the request parameters
$queryString = http_build_query([
    'api_key' => '2CB7EB94FA4A45C7AF7841DA88BFCFF8',
    'location' => "Sydney CBD,New South Wales,Australia",
    'q'=> $question->question
]);

# make the http GET request to Scale SERP
$ch = curl_init(sprintf('%s?%s', 'https://api.scaleserp.com/search', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$api_result = curl_exec($ch);
curl_close($ch);

# print the JSON response from Scale SERP
$jDecode = json_decode($api_result, true);
// $jDecode = $json;
$answerBox = !empty($jDecode['answer_box']['answers']) ? $jDecode['answer_box']['answers'] : null;
$knowledgeGraph = !empty($jDecode['knowledge_graph']) ? $jDecode['knowledge_graph'] : null;
$block_position = !empty($jDecode['answer_box']['block_position']) ? $jDecode['answer_box']['block_position'] : 1;
$explaination = null;
$answer = null;
$link = null;
$title = null;
$image = null;
if (!empty($answerBox)) {
    if (!empty($answerBox[0]['explaination'])) {
        $explaination = $answerBox[0]['explaination'];
    }

    if (!empty($answerBox[0]['source']['link'])) {
        $link = $answerBox[0]['source']['link'];
    }

    if (!empty($answerBox[0]['answer'])) {
        $classification = !empty($answerBox[0]['classification']) ? $answerBox[0]['classification'] : null;
        if ($classification == "wa:/description" && empty($explaination)) {
            $explaination = $answerBox[0]['answer'];
        }else{
            $answer = $answerBox[0]['answer'];
        }
    }

    if (!empty($answerBox[0]['source']['title'])) {
        $title = $answerBox[0]['source']['title'];
    }

    if (!empty($answerBox[0]['images'][0])) {
        $image = $answerBox[0]['images'][0];
    }
}elseif (!empty($knowledgeGraph)) {
    if (!empty($knowledgeGraph['title'])) {
        $title = $knowledgeGraph['title'];
    }
    if (!empty($knowledgeGraph['description'])) {
        $explaination = $knowledgeGraph['description'];
    }
    if (!empty($knowledgeGraph['source']['link'])) {
        $link = $knowledgeGraph['source']['link'];
    }

}

$organicResults = !empty($jDecode['organic_results']) ? $jDecode['organic_results'] : [];
foreach ($organicResults as $key => $row) {
    if ($key == 0 && empty($link)) {
        $title = !empty($row['title']) ? $row['title'] : null;
        $link = !empty($row['link']) ? $row['link'] : null;
        if (empty($explaination)) {
            $explaination = !empty($row['snippet']) ? $row['snippet'] : null;
        }
    }

    if ($key > 0 && $row['link'] == $link && empty($explaination)) {
        $explaination = !empty($row['snippet']) ? $row['snippet'] : null;
    }

}

$question_source = $link;
$answer_info = $explaination;
$scrap_answer = $answer;
$scrap_title = $title;
$scrap_image = $image;

if (!empty($link)) {
    $data['image'] = 0;
    $data['link'] = 0;
    if (!empty($image)) {
        $data['image'] = 1;
        $data['link'] = $image;
    }else{
        if (!empty($question->scrap_image)) {
            $scrap_image = $question->scrap_image;
        }
    }
    $scrap_source_status = 1;

    $data['update'] = $question2->updateScrape(
                        $question->id, 
                        $question_source, 
                        $answer_info, 
                        $scrap_answer, 
                        $scrap_title, 
                        $scrap_image, 
                        $scrap_source_status
                    );

    $data['question'] = $question;
    http_response_code(200);
    echo json_encode([
        "success" => 1,
        "message" => "",
        "data" => $data
    ]);
}else{
    http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "No scrap result"
    ]);
}
?>