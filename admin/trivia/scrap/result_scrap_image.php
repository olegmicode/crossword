<?php
$page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 1;
$baseQuery = !empty($_GET['baseQuery']) ? $_GET['baseQuery'] : 1;
$key = !empty($_GET['key']) ? $_GET['key'] : '';
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
            var apiUrl = '';
            if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
                var apiUrl = 'http://localhost/ylc-games/admin/trivia/scrap';
            } else {
                var apiUrl = 'https://seniorsdiscountclub.com.au/gamesadmin//trivia/scrap';
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
                $("#results").append("id: "+item.id+"<br>");
                $("#results").append("question: "+item.question.trim()+"<br>");
                $("#results").append("answer_info: "+item.answer_info.trim()+"<br>");
                $("#results").append("correct_answer: "+item.correct_answer.trim()+"<br>");
                $("#results").append("question_source: "+item.question_source+"<br>");
                $("#results").append("scrap_image (base on answer): "+item.scrap_image+"<br>");
                $("#results").append("<img style='width: 100px; height: 100px' src='"+item.scrap_image+"'><br>");
                $("#results").append("scrap_image (base on question): "+item.scrap_image_question+"<br>");
                $("#results").append("<img style='width: 100px; height: 100px' src='"+item.scrap_image_question+"'><br>");
                $("#results").append("<br>----<br>");
            }
        });
    </script>
</body>
</html>

