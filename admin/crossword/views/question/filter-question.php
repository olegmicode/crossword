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

// get all question, loop it
// check and filter for number, special char, more than 1 word
// flag them as deleted
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
                                <li class="breadcrumb-item active">Filter Question</li>
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
                            <div class="card-header"><h5>Filter Question</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Filtering data, remove question with numbers, special chars, more than 1 words.</p>
                                <p id="syncInfo" class="alert alert-info">Sync question.</p>
                                <div id="syncLog" style="height: 400px; overflow-y: scroll; font-size: small; background: #eee"></div>
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
        var skipCount = 0;
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/crossword/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
        }

        $(document).ready(function() {

            $('#syncInfo').html('Preparing data filtering...');
            setTimeout(function(){
                filterQuestion();
            }, 3000);

        });

        function hasNumber(myString) {
            return /\d/.test(myString);
        }

        function filterQuestion(page=1) {
            var data = {
                'page': page,
                'perpage': 50
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/question/get-questions.php';
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

                            // var iteratorSpace = 0;
                            // var i = 0;
                            // var arr = item.correct_answer.split(''); // converted the string to an array and then checked:
                            // if(arr[i] === ' '){
                            //     iteratorSpace++;
                            // }

                            var containNumber = hasNumber(item.correct_answer);
                            var trimmedAnswer = item.correct_answer.trim();
                            // if(containNumber) {
                            if (/^\S+$/g.test(trimmedAnswer) || trimmedAnswer === '') {
                                return false;
                            } else {
                                var data = {
                                    'id': item.id
                                }
                                var dataJson = JSON.stringify(data);
                                console.log(dataJson);

                                var ajaxUrl = apiUrl + '/question/delete.php';
                                $.ajax({
                                    type: 'POST',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    url: ajaxUrl,
                                    data: dataJson,
                                    beforeSend: function() {
                                        $('#syncInfo').html('Deleting data...');
                                    },
                                    success: function(response){

                                        console.log(response);
                                        $('#syncLog').append('<span><i class="fa fa-check"></i> More than 1 word - #'+ item.id +' > '+ item.correct_answer +' - Deleted</span><br>');

                                    },
                                    error: function(xhr,textStatus,error){
                                        console.log(error);
                                        $('#syncLog').append('<span><i class="fa fa-times"></i> More than 1 word - #'+ item.id +' > '+ item.correct_answer +' - Deletion failed </span><br>');
                                    }
                                });

                                // $('#syncLog').append('<span><i class="fa fa-times"></i> Contain spaces - #'+ item.id +'# > -'+ item.correct_answer +'- </span><br>');
                            }

                            // var data = {
                            //     'id': item.id
                            // }
                            // var dataJson = JSON.stringify(data);
                            // // console.log(dataJson);

                            // var ajaxUrl = apiUrl + '/question/filter-answer.php';
                            // $.ajax({
                            //     type: 'POST',
                            //     cache: false,
                            //     contentType: false,
                            //     processData: false,
                            //     url: ajaxUrl,
                            //     data: dataJson,
                            //     beforeSend: function() {
                            //         $('#syncInfo').html('Filtering data...');
                            //     },
                            //     success: function(response){

                            //         console.log(response);
                            //         if(response.status == 'deleted') {
                            //             $('#syncLog').append('<span><i class="fa fa-check"></i>  deleted #'+ item.id +' > '+ item.correct_answer +'</span><br>');
                            //         }
                            //         else if(response.status == 'deletion-failed') {
                            //             $('#syncLog').append('<span><i class="fa fa-times"></i>  deletion failed #'+ item.id +' > '+ item.correct_answer +'</span><br>');
                            //         }
                            //         else if(response.status == 'skip') {
                            //             $('#syncLog').append('<span><i class="fa fa-check"></i>  skip #'+ item.id +' > '+ item.correct_answer +'</span><br>');
                            //         }


                            //     },
                            //     error: function(xhr,textStatus,error){
                            //         console.log(error);
                            //         // $('#syncLog').append('<span><i class="fa fa-times"></i> #'+ item.id +' > '+ item.correct_answer +' - Deletion failed </span><br>');
                            //     }
                            // });

                        });

                        page++;
                        $('#syncInfo').html('Fetching data complete. preparing fetching page '+ page +' ...');

                        // if(page < 10) {
                            setTimeout(function(){
                                filterQuestion(page);
                            }, 1500);
                        // }
                    } else {
                        $('#syncInfo').html('Sync-ing is complete');
                    }

                },
                error: function(xhr,textStatus,error){
                    $('#syncInfo').html('Sync-ing is complete');
                }
            });

        }

    </script>
</body>
</html>