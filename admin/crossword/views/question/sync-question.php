<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

// instantiate object
include_once '../../objects/question.php';
include_once '../../objects/category.php';

$database = new Database();
$db = $database->getConnection();

$question = new Question($db);

?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../elements/html-head.php'); ?>
</head>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include('../elements/navbar.php'); ?>

        <?php include('../elements/sidebar.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Questions</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Sync Question</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h5>Sync Question</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Getting data from trivia database, and import to crossword database.</p>
                                <p id="syncInfo" class="alert alert-info">Sync question.</p>
                                <div id="syncLog" style="height: 400px; overflow-y: scroll; font-size: small"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include('../elements/footer.php'); ?>
    </div>
    <!-- ./wrapper -->

    <?php include('../elements/footer-js.php'); ?>
    <script>
        var apiUrl = '';
        var triviaApiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/crossword/api';
            var triviaApiUrl = 'http://localhost/ylc-games/trivia/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
            var triviaApiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
        }

        $(document).ready(function() {

            $('#syncInfo').html('Preparing data sync-ing...');
            setTimeout(function(){
                syncQuestion();
            }, 3000);

        });

        function syncQuestion(page=1) {
            var data = {
                'page': page,
                'perpage': 50
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = triviaApiUrl + '/question/get-questions.php';
            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#syncInfo').html('Fetching data...');
                },
                success: function(response){

                    console.log(response);
                    if(response.records) {

                        // loop the data
                        response.records.forEach(function(item) {
                            console.log(item);

                            var data = {
                                'category_id': item.category_id,
                                'question_source': item.question_source,
                                'country_spesific': item.country_spesific,
                                'question': item.question,
                                'correct_answer': item.correct_answer,
                                'answer_info': item.answer_info,
                                'is_alt_img': item.is_alt_img,
                                'rate': item.rate,
                                'reported': item.reported,
                                'scrap_image': item.scrap_image,
                                'scrap_title': item.scrap_title,
                                'slug': item.slug,
                                'status': item.status
                            }

                            var dataJson = JSON.stringify(data);
                            console.log(dataJson);

                            var ajaxUrl = apiUrl + '/question/submit-question-by-sync.php';
                            $.ajax({
                                type: 'POST',
                                cache: false,
                                contentType: false,
                                processData: false,
                                url: ajaxUrl,
                                data: dataJson,
                                beforeSend: function() {
                                    $('#syncInfo').html('Saving data...');
                                },
                                success: function(response){

                                    console.log(response);
                                    if(response.status == "duplicated") {
                                        $('#syncLog').append('<p><i class="fa fa-times"></i> Failed: (duplicated) - '+ item.id +' > '+ item.slug +'</p>');
                                    }
                                    else if(response.status == "failed") {
                                        $('#syncLog').append('<p><i class="fa fa-times"></i> Failed: (Unable to create question) - '+ item.id +' > '+ item.slug +'</p>');
                                    }
                                    else if(response.status == "incomplete") {
                                        $('#syncLog').append('<p><i class="fa fa-times"></i> Failed: (Data incomplete) - '+ item.id +' > '+ item.slug +'</p>');
                                    }
                                    else {
                                        $('#syncLog').append('<p><i class="fa fa-check"></i> Success: '+ item.id +' > '+ item.slug +'</p>');
                                    }

                                },
                                error: function(xhr,textStatus,error){
                                    console.log(error);
                                }
                            });

                        });


                        page++;
                        $('#syncInfo').html('Fetching data complete. preparing fetching page '+ page +' ...');

                        // if(page < 10) {
                            setTimeout(function(){
                                syncQuestion(page);
                            }, 1500);
                        // }
                    } else {
                        $('#syncInfo').html('Sync-ing is complete');
                    }

                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });

        }

    </script>
</body>
</html>