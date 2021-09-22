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
        'key'    => 'AKIAXJLJL63GQLWJED6C',
        'secret' => 'AiqdS0cRNzTqSxBF0x//7BAQQQifE5yAeOl+x8kB',
    ]
]);
$bucket = "yourlifechoices";
$bucket_folder = "ylc_games/";

$id = isset($_POST['id']) ? $_POST["id"] : null;
$filename = isset($_POST['filename']) ? $_POST["filename"] : $id;
$image = isset($_POST['image']) ? $_POST["image"] : null;

if (empty($image)) {
    http_response_code(200);
    echo json_encode([
        "success" => 0,
        "message" => "No image or question found"
    ]);
}else{
    // $body = file_get_contents($image);
    $mime = @getimagesize($image);
    $img_header = @get_headers($image, 1);
    $filesize = @$img_header["Content-Length"];
    if (!empty($mime)) {
        if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($image); }
        if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($image); }
        if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($image); }
        if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($image); }
    }else{
        $src_img = imagecreatefromjpeg($image);
    }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);
    // 200*200 will get 40kb
    $newWidth = 200;
    $newHeight = $newWidth;

    if (empty($filesize) || $filesize > 30000) {
        if($old_x > $old_y)
        {
            $thumb_w = $newWidth;
            $thumb_h = $old_y/$old_x*$newWidth;
        }

        if($old_x < $old_y)
        {
            $thumb_w = $old_x/$old_y*$newHeight;
            $thumb_h = $newHeight;
        }

        if($old_x == $old_y)
        {
            $thumb_w = $newWidth;
            $thumb_h = $newHeight;
        }
    }else{
        $thumb_w = $old_x;
        $thumb_h = $old_y;
    }

    // header('Content-Type: image/jpeg');
    ob_start();
    $dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

    if (!empty($mime)) {
        if($mime['mime']=='image/png'){ $result = imagepng($dst_img,null,8); }
        if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,null,100); }
        if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,null,100); }
        if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,null,100); }
    }else{
        $result = imagejpeg($dst_img,null,100);
    }

    $imageForS3 = ob_get_contents();
    ob_end_clean();

    // echo $result; exit();
    try {
        $sendS3 = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $bucket_folder.''.$filename.'.jpg',
            'Body' => $imageForS3,
            'ContentType' => !empty($mime['mime']) ? $mime['mime'] : 'image/jpg',
            'ACL' => 'public-read',
        ]);

        $response = [];
        $response['s3_url'] = $sendS3['ObjectURL'];
        http_response_code(200);
        echo json_encode([
            "success" => 1,
            "message" => "",
            "data" => $response
        ]);

    } catch (Aws\S3\Exception\S3Exception $e) {
        http_response_code(200);
        echo json_encode([
            "success" => 0,
            "message" => "There was an error uploading the file"
        ]);
    }
}
?>