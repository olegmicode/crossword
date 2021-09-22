<?php
header("Content-Type: application/json");

include_once '../../config/database.php';
include_once '../../objects/question.php';

$database = new Database();
$db = $database->getConnection();

$question = new Question($db);

$id = !empty($_POST['id']) ? $_POST["id"] : null;
$image_url = !empty($_POST['image_url']) ? $_POST["image_url"] : null;
$col_image = !empty($_POST['col_image']) ? $_POST["col_image"] : 'scrap_image';
$scrap_source_status = !empty($_POST['scrap_source_status']) ? $_POST["scrap_source_status"] : 1;

if (empty($image_url)) {
    http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "No image found"
    ]);
}else{
    $data['update_image'] = $question->updateImage($id, $image_url, $scrap_source_status, $col_image);
    http_response_code(200);
    echo json_encode([
        "success" => 1,
        "message" => "",
        "data" => $data
    ]);
}
?>