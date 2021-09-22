
<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

// instantiate object
include_once '../../objects/score.php';

$database = new Database();
$db = $database->getConnection();

$score = new Score($db);

// redo checking
$scoreList = $score->getHallOfFame();
// $rowCount = $statement->rowCount();

// var_dump($question);exit();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../elements/html-head.php'); ?>
    <style>
    .selectedImage {
        border: 1px solid #333 !important;
    }
    .scrap_image_wrapper, .scrap_image_alt_wrapper {
        padding: 4px;
        background: green;
        border-radius: 4px;
        text-align: center;
        cursor: pointer;
    }
    .selected_text {
        font-size: smaller;
        color: white;
        display: block;
    }
    .img_thumb, .img_thumb_alt {
        float: left;
        margin: 2px;
        width: 75px;
        height: 75px;
        overflow: hidden !important;
        border-radius: 4px;
    }
    .img_question {
        float: left;
        margin: 5px;
        padding: 5px;
        background: #999;
        width: 15%;
        border-radius: 4px;
    }
    .img_id {
        color: #fff;
        display: block;
        font-size: small;
    }
    .img_id a {
        color: #fff;
        font-weight: bold;
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
                                <li class="breadcrumb-item active">Hall of Fame</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hall of Fame</h3>

                        <div class="card-tools">
                            <a href="#" id="deleteAll" class="btn btn-sm btn-danger">delete all</a>
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">

                        <?php if(!empty($scoreList)){?>
                            <div class="row">
                                <div class="col">
                                    <table class="table table-striped" id="questionData">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">No</th>
                                            <th>Player</th>
                                            <th class="text-right">Score</th>
                                            <th>Played at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        for($i=0; $i<10; $i++){ ?>
                                        <?php if(!empty($scoreList[$i])){?>
                                        <tr>
                                            <td><?php echo $no ;?></td>
                                            <td><?php echo $scoreList[$i]['user'] ;?></td>
                                            <td class="text-right"><?php echo number_format($scoreList[$i]['score'], 0, '.', ',') ;?></td>
                                            <td><?php echo date('d M Y, h:i', strtotime($scoreList[$i]['last_played'])) ;?></td>
                                        </tr>
                                        <?php $no++; } ?>
                                        <?php } ?>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } else {?>
                            <div class="alert alert-warning">Score data is not found.</div>
                        <?php }?>
                    </div>
                    <!-- /.card-body -->

                </div>
                <!-- /.card -->

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

            $('#deleteAll').click(function(e){
                e.preventDefault();
                if (confirm('Are you sure you want to delete all scores? This action cannot be reversed.')) {
                    deleteScores();
                }
            });



        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/crossword/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
        }

        function deleteScores() {

            var ajaxUrl = apiUrl + "/score/delete-scores.php";

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>
