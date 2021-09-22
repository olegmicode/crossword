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
$category = new Category($db);

$question->getAllQuestionsCount();
$totalRecord = $question->totalRows;
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
                                <li class="breadcrumb-item active">Maintenance</li>
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
                            <div class="card-header"><h5>Delete long questions</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Removing question that have more than 180 chars. All deleted questions will be listed on the deleted Question interface.</p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnDeleteLongQuestion" class="btn btn-success">Run script</button>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h5>Delete long answers</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Removing question that having answer more than 180 chars. All deleted questions will be listed on the deleted Question interface.</p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnDeleteLongAnswer" class="btn btn-success">Run script</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h5>Reformat answers</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Reformating answer starter characters.</p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnFormatAnswer" class="btn btn-success">Run script</button>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h5>Reformat special characters</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Reformating special characters.</p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnFormatChar" class="btn btn-success">Run script</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h5>Sluggify Questions</h5></div>
                            <div class="card-body" style="min-height: 100px !important;">
                                <p>Creating slug for questions</p>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="btnSluggify" class="btn btn-success">Run script</button>
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
        $(document).ready(function() {

            $('#btnDeleteLongQuestion').click(function(e){
                e.preventDefault();
                // deleteLongQuestion(0, <?php echo $totalRecord;?>);
            });

            $('#btnDeleteLongAnswer').click(function(e){
                e.preventDefault();
                // deleteLongAnswer(0, <?php echo $totalRecord;?>);
            });

            $('#btnFormatAnswer').click(function(e){
                e.preventDefault();
                // formatAnswer(0, <?php echo $totalRecord;?>);
            });

            $('#btnFormatChar').click(function(e){
                e.preventDefault();
                // formatChar(0, <?php echo $totalRecord;?>);
            });

            $('#btnSluggify').click(function(e){
                e.preventDefault();
                createSlug(0, <?php echo $totalRecord;?>);
            });
        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/trivia/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/trivia/api';
        }

        function deleteLongQuestion(totalSynced=0, totalRecord) {

            var data = {
                'totalSynced': totalSynced,
                'totalRecord': totalRecord
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = '/question/delete-long-question.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#btnDeleteLongQuestion').html('Sync in process... '+ totalSynced +' data updated.');
                },
                success: function(response){

                    console.log(response);
                    // var ObjData = JSON.parse(response);
                    var newTotalSynced = response.totalSynced;
                    var totalRecord = response.totalRecord;

                    if(newTotalSynced < totalRecord) {
                        deleteLongQuestion(newTotalSynced, totalRecord);
                    } else {
                        $('#btnDeleteLongQuestion').html('Run script complete!');
                        setTimeout(function(){
                            $('#btnDeleteLongQuestion').html('Run script');
                        }, 1500);

                    }
                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });
        }

        function deleteLongAnswer(totalSynced=0, totalRecord) {

            var data = {
                'totalSynced': totalSynced,
                'totalRecord': totalRecord
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = '/question/delete-long-answer.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#btnDeleteLongAnswer').html('Sync in process... '+ totalSynced +' data updated.');
                },
                success: function(response){

                    console.log(response);
                    // var ObjData = JSON.parse(response);
                    var newTotalSynced = response.totalSynced;
                    var totalRecord = response.totalRecord;

                    if(newTotalSynced < totalRecord) {
                        deleteLongAnswer(newTotalSynced, totalRecord);
                    } else {
                        $('#btnDeleteLongAnswer').html('Run script complete!');
                        setTimeout(function(){
                            $('#btnDeleteLongAnswer').html('Run script');
                        }, 1500);

                    }
                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });
        }

        function formatAnswer(totalSynced=0, totalRecord) {
            var data = {
                'totalSynced': totalSynced,
                'totalRecord': totalRecord
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = '/question/format-answer.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#btnFormatAnswer').html('Sync in process... '+ totalSynced +' data updated.');
                },
                success: function(response){

                    console.log(response);
                    // var ObjData = JSON.parse(response);
                    var newTotalSynced = response.totalSynced;
                    var totalRecord = response.totalRecord;

                    if(newTotalSynced < totalRecord) {
                        formatAnswer(newTotalSynced, totalRecord);
                    } else {
                        $('#btnFormatAnswer').html('Run script complete!');
                        setTimeout(function(){
                            $('#btnFormatAnswer').html('Run script');
                        }, 1500);

                    }
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
                }
            });
        }

        function formatChar(totalSynced=0, totalRecord) {
            var data = {
                'totalSynced': totalSynced,
                'totalRecord': totalRecord
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/question/format-escaped.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#btnFormatChar').html('Sync in process... '+ totalSynced +' data updated.');
                },
                success: function(response){

                    console.log(response);
                    // var ObjData = JSON.parse(response);
                    var newTotalSynced = response.totalSynced;
                    var totalRecord = response.totalRecord;

                    if(newTotalSynced < totalRecord) {
                        formatChar(newTotalSynced, totalRecord);
                    } else {
                        $('#btnFormatChar').html('Run script complete!');
                        setTimeout(function(){
                            $('#btnFormatChar').html('Run script');
                        }, 1500);

                    }
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
                }
            });
        }

        function createSlug(totalSynced=0, totalRecord) {
            var data = {
                'totalSynced': totalSynced,
                'totalRecord': totalRecord
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/question/create-slug.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('#btnSluggify').html('Sync in process... '+ totalSynced +' data updated.');
                },
                success: function(response){

                    console.log(response);
                    // var ObjData = JSON.parse(response);
                    var newTotalSynced = response.totalSynced;
                    var totalRecord = response.totalRecord;

                    if(newTotalSynced < totalRecord) {
                        createSlug(newTotalSynced, totalRecord);
                    } else {
                        $('#btnSluggify').html('Run script complete!');
                        setTimeout(function(){
                            $('#btnSluggify').html('Run script');
                        }, 1500);

                    }
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>
