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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var totalRow = 0;
            var totalUpdatedRow = 0;
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
                        totalRow = response.data.length;
                        response.data.forEach(function(item) {
                            updateAnswer(item);
                        });
                    }else{
                        $("#results").append(response.message);
                    }

                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });

            function updateAnswer(item) {
                // console.log("totalRow", totalRow);
                $("#results").append("getting scrap ("+item.id+", "+item.question+")...<br>");
                $.ajax({
                    type: 'GET',
                    url:  apiUrl + "/ajax/check_answer_info.php",
                    data: {
                        id: item.id
                    },
                    success: function(response) {
                        console.log(response);
                        $("#results").append("response:<br>");
                        $("#results").append(JSON.stringify(response, undefined, 2));
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

            function updateCount() {
                if (totalRow == totalUpdatedRow) {
                    alert("done");
                }
            }
        });
    </script>
</body>
</html>

