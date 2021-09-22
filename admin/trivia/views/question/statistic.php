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

$mostOccurrence = $question->getMostOccurrence();
$mostOccurrenceCount = $mostOccurrence->rowCount();

$mostLiked = $question->getMostLiked();
$mostLikedCount = $mostLiked->rowCount();

$mostDisliked = $question->getMostDisliked();
$mostDislikedCount = $mostDisliked->rowCount();

$mostReported = $question->getMostReported();
$mostReportedCount = $mostReported->rowCount();

$mostCorrect = $question->getMostCorrect();
$mostCorrectCount = $mostCorrect->rowCount();

$mostIncorrect = $question->getMostIncorrect();
$mostIncorrectCount = $mostIncorrect->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../elements/html-head.php'); ?>
    <style>
        .statistic ul, .statistic ul li {
            list-style: none;
            margin: 0px;
            padding: 0px;
        }
        .statistic ul {
            margin-bottom: 30px;
        }
        .statistic ul li {
            margin-bottom: 10px;
        }
    </style>
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
                            <h1>Statistic</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Statistic</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">


                        <div class="row statistic">
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Occurred Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostOccurrenceCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostOccurrence->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $ingame_occurrence;?></td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Liked Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostLikedCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostLiked->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $likes;?></td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Disliked Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostDislikedCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostDisliked->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $dislikes;?></td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
                                    </div>
                                </div>

                            </div>
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Reported Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostReportedCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostReported->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $reported;?></td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Answered correctly Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostCorrectCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostCorrect->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $correct_count;?></td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Most Answered incorrectly Questions</h3>
                                    </div>
                                    <div class="card-body" style="overflow: hidden">
                                        <?php if($mostIncorrectCount > 0) {?>
                                        <table class="table table-striped">
                                            <?php while ($row = $mostIncorrect->fetch(PDO::FETCH_ASSOC)){
                                                extract($row); ?>
                                                <tr>
                                                    <td><?php echo $question;?></td>
                                                    <td width="70" class="text-right"><?php echo $incorrect_count;?></td>
                                                <tr>
                                            <?php }?>
                                        </table>
                                        <?php } else {?>
                                            <div class="alert alert-info">No data collected</div>
                                        <?php }?>
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

            // $('.btnEdit').click(function(e){
            //     e.preventDefault();
            //     var id = $(this).attr('data-id');
            //     getQuestion(id);
            // });

            populateCategories();

        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/trivia/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/trivia/api';
        }


    </script>
</body>
</html>
