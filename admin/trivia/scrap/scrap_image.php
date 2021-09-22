<?php
$page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 1;
$baseQuery = !empty($_GET['baseQuery']) ? $_GET['baseQuery'] : 1;
$key = !empty($_GET['key']) ? $_GET['key'] : '';
$scrap_by = !empty($_GET['scrap_by']) ? $_GET['scrap_by'] : 'site';
// SET Param
$perpage = $limit;
$offset = ($page>1) ? ($page * $perpage) - $perpage : 0;
$ylcKey = "ylcGame123";
if ($ylcKey != $key) {
    echo "You don't have permission";exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <pre id="results"></pre>
    <div id="results2"></pre>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var totalRow = 0;
            var totalUpdatedRow = 0;
            var totalImg = 0;
            var totalUpdatedImg = 0;
            var scrapeBy = "<?php echo $scrap_by; ?>";
            var apiUrl = '';
            if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
                var apiUrl = 'http://localhost/ylc-games/admin/trivia/scrap';
            } else {
                var apiUrl = 'https://seniorsdiscountclub.com.au/games/admin/trivia/scrap';
            }

            $.ajax({
                type: 'GET',
                url:  apiUrl + "/ajax/get_question.php",
                data: {
                    perpage: "<?php echo $perpage; ?>",
                    offset: "<?php echo $offset; ?>",
                    baseQuery: "<?php echo $baseQuery; ?>",
                },
                success: function(response) {
                    if (response.success == 1) {
                        // console.log(response);
                        totalRow = response.data.length;
                        response.data.forEach(function(item) {
                            scrapImage(item);
                        });
                    }else{
                        $("#results").append(response.message);
                    }

                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });

            function scrapImage(item) {
                // console.log("totalRow", totalRow);
                $("#results").append("scrapImage based on <?php echo $scrap_by; ?> ("+item.id+" | "+item.question.trim()+" | "+item.scrap_image+")...<br>");
                $.ajax({
                    type: 'GET',
                    url:  apiUrl + "/ajax/scrap_image.php",
                    data: {
                        id: item.id,
                        scrap_by: "<?php echo $scrap_by; ?>",
                    },
                    success: function(response) {
                        console.log(response);
                        $("#results").append("response_scrap_image:<br>");
                        if (response.success == 1) {
                            $("#results").append("question: "+response.data.question + "<br>");
                            $("#results").append("question source: <a href='"+response.data.question_source+"'>"+response.data.question_source+"</a><br>");
                            $("#results").append("scrap_image based on <?php echo $scrap_by; ?>: <a href='"+response.data.current.scrap_image+"'>"+response.data.current.scrap_image+"</a><br>");
                            $("#results").append("<img style='width: 100px; height: 100px' src='"+response.data.current.scrap_image+"'><br>");

                            if (response.data.current.scrap_image != null || response.data.current.scrap_image != "undefined") {
                                totalImg++;
                                uploadS3Resize(item.id, response.data.current.scrap_image)
                            }
                        }else{
                            $("#results").append(JSON.stringify(response, undefined, 2));
                        }
                        $("#results").append("<br>----<br>");
                        totalUpdatedRow++;
                        updateCount();
                    },
                    error: function(xhr,textStatus,error) {
                        console.log(error);
                        $("#results").append("response:<br>");
                        $("#results").append(error);
                        $("#results").append("<br>----<br>");
                        totalUpdatedRow++;
                        updateCount();
                    }
                });
            }

            function uploadS3Resize(id, image) {
                $("#results").append("uploadS3Resize ("+id+", "+image+")...<br>");
                var filename = id;
                if (scrapeBy == "question") {
                    filename = id+"-question";
                }
                $.ajax({
                    type: 'POST',
                    url:  apiUrl + "/ajax/upload_s3_resize.php",
                    data: {
                        id: id,
                        image: image,
                        filename: filename
                    },
                    success: function(response) {
                        console.log(response);
                        $("#results").append("response_s3_resize :<br>");
                        $("#results").append(JSON.stringify(response, undefined, 2));
                        $("#results").append("<br>----<br>");
                        if (response.success == 1) {
                            if (response.data.s3_url != null) {
                                if (scrapeBy == "correct_answer" || scrapeBy == "site") {
                                    updateImageDB(id, response.data.s3_url, 4, 'scrap_image')
                                }else{
                                    updateImageDB(id, response.data.s3_url, 4, 'scrap_image_question')
                                }
                            }else{
                                totalUpdatedImg++;
                            }
                        }else{
                            totalUpdatedImg++;
                        }
                        updateCount();
                    },
                    error: function(xhr,textStatus,error) {
                        console.log(error);
                        $("#results").append("response_s3 :<br>");
                        $("#results").append(error);
                        $("#results").append("<br>----<br>");
                        totalUpdatedImg++;
                        updateCount();
                    }
                });
            }

            function updateImageDB(id, image_url, scrap_source_status, col_image) {
                $("#results").append("updateimageDB ("+id+", "+image_url+", "+scrap_source_status+", "+col_image+")...<br>");
                $.ajax({
                    type: 'POST',
                    url:  apiUrl + "/ajax/update_img.php",
                    data: {
                        id: id,
                        image_url: image_url,
                        scrap_source_status: scrap_source_status,
                        col_image: col_image
                    },
                    success: function(response) {
                        console.log(response);
                        $("#results").append("response_update_image :<br>");
                        $("#results").append(JSON.stringify(response, undefined, 2));
                        $("#results").append("<br>----<br>");
                        totalUpdatedImg++;
                        updateCount();
                    },
                    error: function(xhr,textStatus,error) {
                        console.log(error);
                        $("#results").append("response_update_image :<br>");
                        $("#results").append(error);
                        $("#results").append("<br>----<br>");
                        totalUpdatedImg++;
                        updateCount();
                    }
                });
            }

            function updateCount() {
                if (totalRow == totalUpdatedRow && totalImg == totalUpdatedImg) {
                    alert("done");
                }
            }
        });
    </script>
</body>
</html>

