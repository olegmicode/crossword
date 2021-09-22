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

// GET
$page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
$searchCountry = isset($_GET['searchCountry']) ? $_GET['searchCountry'] : "";
$searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : "";
$searchKeyword = isset($_GET['searchKeyword']) ? $_GET['searchKeyword'] : "";

$isMturk = isset($_GET['isMturk']) ? $_GET['isMturk'] : "";
$isDraft = isset($_GET['isDraft']) ? $_GET['isDraft'] : "";
$isStar = isset($_GET['isStar']) ? $_GET['isStar'] : "";
$searchOrigin = isset($_GET['origin']) ? $_GET['origin'] : "";

// SET Param
$question->perpage = 50000;
$question->offset = ($page>1) ? ($page * $question->perpage) - $question->perpage : 0;

($searchCountry != "") && $question->searchCountry = $searchCountry;
($searchCategory != "") && $question->searchCategory = $searchCategory;
($searchKeyword != "") && $question->searchKeyword = $searchKeyword;
($isMturk != "") && $question->is_mturk = 1;
($isDraft != "") && $question->isDraft = 1;
($isStar != "") && $question->rate = 1;
($searchOrigin != "") && $question->searchOrigin = $searchOrigin;


$statement = $question->getEscapedQuestion();
$rowCount = $statement->rowCount();
$pages = ceil($question->totalRows / $question->perpage);
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
                                <li class="breadcrumb-item active">Incomplete Questions</li>
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
                        <h3 class="card-title">Incomplete Questions</h3>

                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">

                        <hr>
                        <?php if($rowCount > 0){?>

                            <table class="table table-striped" id="questionData">
                                <thead>

                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">ID</th>
                                        <th>Question</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $questions_arr = array();
                                $questions_arr["records"] = array();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);

                                    $scrap_titleOriginal = str_replace("&amp;", "<strong>&amp;</strong>", $scrap_title);
                                    $scrap_titleClean = str_replace("&amp;", "&", $scrap_title);

                                    ?>
                                    <tr id="questionRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $no;?></td>
                                        <td><?php echo $id;?></td>

                                        <td><div id="questionInfo<?php echo $id;?>"></div>
                                            <a href="#" class="btnEdit" data-id="<?php echo $id;?>">
                                                <span id="question<?php echo $id;?>"><?php echo $question; ?></span>
                                            </a>
                                            <?php if($is_mturk == 1){?>
                                                <span class="badge badge-info">mturk</span>
                                            <?php }?>
                                            <?php if(!empty($origin)){?>
                                                <span class="badge badge-warning"><?php echo $origin; ?></span>
                                            <?php }?>
                                            <?php if($scrap_titleClean != $scrap_title){?>
                                                <span class="badge badge-warning">need clean up</span>
                                            <?php } ?>
                                            <br>
                                            <small><strong>SCRAP TITLE:</strong> <?php echo $scrap_titleOriginal;?></small>
                                            <br>
                                            <small><strong>SCRAP TITLE clean:</strong> <?php echo $scrap_titleClean;?></small>
                                        </td>

                                    </tr>

                                    <?php
                                    // if($scrap_titleClean != $scrap_title){
                                    //     $questionFormatted = new Question($db);
                                    //     $questionFormatted->id = $id;
                                    //     $questionFormatted->scrap_title = $scrap_titleClean;
                                    //     $questionFormatted->cleanEscaped();
                                    // }

                                    ?>

                                    <?php $no++; ?>
                                <?php }?>
                                </tbody>
                            </table>

                            <div style="overflow-x: scroll; margin-top: 20px">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?></small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                            href="incomplete.php?searchCategory=<?php echo $searchCategory; ?>&searchCountry=<?php echo $searchCountry; ?>&searchKeyword=<?php echo $searchKeyword; ?>&searchOrigin=<?php echo $searchOrigin; ?>&isMturk=<?php echo $isMturk; ?>&isDraft=<?php echo $isDraft; ?>&isStar=<?php echo $isStar; ?>&page=<?php echo $i;?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>
                        <?php } else {?>
                            <div class="alert alert-warning">Question data is not found. Please update your filter or clear up your filter to see all questions.</div>
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

            $('.btnEdit').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                getQuestion(id);
            });

            $('#submitButton').click(function(e){
                e.preventDefault();
                submitQuestion();
            });

            $('.btnAdd').click(function(e){
                e.preventDefault();
                $('#createModal').modal('show');
            });

            $('#submitCreateQuestion').click(function(e){
                e.preventDefault();
                submitCreateQuestion();
            });

            $('.btnDelete').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                deleteQuestion(id);
            });

            $('#btnSubmitCategory').click(function(e){
                e.preventDefault();
                var cat_id = $('#applyCategory').val();
                submitCategory(cat_id);
            });

            $('#btnSubmitDelete').click(function(e){
                e.preventDefault();
                if (confirm('Are you sure you want to delete all marked questions?')) {
                    submitDelete();
                }
            });

            $('.btnDraftToggle').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                draftToggleQuestion(id);
            });

            $('#btnSubmitDraft').click(function(e){
                e.preventDefault();
                if (confirm('Are you sure you want to toggle all marked questions status?')) {
                    submitDraftToggle();
                }
            });

            $('.rateQuestion').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                toggleRate(id);
            });

            $('#questionCheckToggle').click(function() {
                console.log('check all')
                var checkedStatus = this.checked;
                $('#questionData tbody tr').find('td:last :checkbox').each(function() {
                    $(this).prop('checked', checkedStatus);
                });
            });


            // populateCountries();
            populateCategories();

        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/trivia/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/trivia/api';
        }

        function populateCountries() {
            var countryUrl = "https://seniorsdiscountclub.com.au/games/admin/trivia/data/countries.json";
            $.ajax({
                dataType: "json",
                url: countryUrl,
                success: function(response) {

                    for (var key in response) {
                        $('.country_spesific').append('<option value="' + key + '">' + response[key] + '</option>');
                    }
                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });
        }

        function populateCategories() {
            var ajaxUrl = apiUrl + "/category/read.php";
            $.ajax({
                type: 'GET',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                success: function(response) {

                    response.records.forEach(function(item) {
                        if(item.id != 10) {
                            $('.categoryList').append('<option value="' + item.id + '">' + item.name + '</option>');
                        }
                    });
                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });
        }

        function getQuestion(id) {

            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/question/get-by-id.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response){

                    console.log(response);

                    $('#question_id').val(response.id);
                    $('#category').val(response.category_id);
                    $('#question').val(response.question);
                    $('#question_source').val(response.question_source);
                    $('#country_spesific').val(response.country_spesific);
                    $('#correct_answer').val(response.correct_answer);
                    $('#incorrect_answer_1').val(response.incorrect_answer1);
                    $('#incorrect_answer_2').val(response.incorrect_answer2);
                    $('#incorrect_answer_3').val(response.incorrect_answer3);

                    $('#updateModal').modal('show');
                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });
        }

        function submitQuestion() {
            var question_id = $('#question_id').val();
            var category = $('#category').val();
            var question_source = $('#question_source').val();
            var country_spesific = $('#country_spesific').val();
            var question = $('#question').val();
            var correct_answer = $('#correct_answer').val();
            var incorrect_answer_1 = $('#incorrect_answer_1').val();
            var incorrect_answer_2 = $('#incorrect_answer_2').val();
            var incorrect_answer_3 = $('#incorrect_answer_3').val();

            if(category != '' && question != '' && question_source !='' && correct_answer != '' && incorrect_answer_1 != '' && incorrect_answer_2 != '' && incorrect_answer_3 != '') {
                // create json to send
                var data = {
                    'id': question_id,
                    'category_id': category,
                    'question_source': question_source,
                    'country_spesific': country_spesific,
                    'question': question,
                    'correct_answer': correct_answer,
                    'incorrect_answer1': incorrect_answer_1,
                    'incorrect_answer2': incorrect_answer_2,
                    'incorrect_answer3': incorrect_answer_3
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/update.php';

                console.log(dataJson);

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        console.log(response);

                        $("#success-alert").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);

                            // update the row
                            $('#category'+question_id).html(response.category_name);
                            $('#question'+question_id).html(question);
                            $('#country_spesific'+question_id).html(country_spesific);
                            $('#question_source'+question_id).html(question_source);
                            $('#correct_answer'+question_id).html(correct_answer);
                            $('#incorrect_answer1'+question_id).html(incorrect_answer_1);
                            $('#incorrect_answer2'+question_id).html(incorrect_answer_2);
                            $('#incorrect_answer3'+question_id).html(incorrect_answer_3);


                            $('#updateModal').modal('hide');
                        });
                    },
                    error: function(xhr,textStatus,error){
                        alert(error);
                    }
                });
            }
            else {
                alert('Please fill all fields to submit the form.');
            }

        }

        function submitCreateQuestion() {
            var category = $('#categoryCreate').val();
            var question_source = $('#question_sourceCreate').val();
            var country_spesific = $('#country_spesificCreate').val();
            var question = $('#questionCreate').val();
            var correct_answer = $('#correct_answerCreate').val();
            var incorrect_answer_1 = $('#incorrect_answer_1Create').val();
            var incorrect_answer_2 = $('#incorrect_answer_2Create').val();
            var incorrect_answer_3 = $('#incorrect_answer_3Create').val();

            if(category != '' && question != '' && question_source !='' && correct_answer != '' && incorrect_answer_1 != '' && incorrect_answer_2 != '' && incorrect_answer_3 != '') {
                // create json to send
                var data = {
                    'category_id': category,
                    'question_source': question_source,
                    'country_spesific': country_spesific,
                    'question': question,
                    'correct_answer': correct_answer,
                    'incorrect_answer1': incorrect_answer_1,
                    'incorrect_answer2': incorrect_answer_2,
                    'incorrect_answer3': incorrect_answer_3
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + ' /question/submit-question.php';

                console.log(dataJson);

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        console.log(response);

                        if(response.message === "duplicated") {
                            $("#error-alert").fadeTo(1000, 500).slideUp(500, function(){
                                $("#error-alert").slideUp(500);
                            });
                        }
                        else {
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);

                                $('#categoryCreate').val('');
                                $('#question_sourceCreate').val('');
                                $('#country_spesificCreate').val('');
                                $('#questionCreate').val('');
                                $('#correct_answerCreate').val('');
                                $('#incorrect_answer_1Create').val('');
                                $('#incorrect_answer_2Create').val('');
                                $('#incorrect_answer_3Create').val('');

                                $('#createModal').modal('hide');
                            });
                        }


                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }
            else {
                alert('Please fill all fields to submit the form.');
            }

        }

        function deleteQuestion(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/delete.php';

                $('#questionInfo'+id).html("deleting....<hr>");

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        // alert(response);
                        setTimeout(function(){
                            $('#questionRow'+id).hide();
                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        alert(error);
                    }
                });
            }
            else {
                alert('Question is not found.');
            }

        }

        function submitCategory(id) {
            if(id != '') {


                // get all selected checkbox
                var questions = [];
                $.each($("input[name='questionCheck']:checked"), function(){
                    questions.push($(this).val());
                });
                // alert("My selected questions are: " + questions.join(", "));

                // create json to send
                var data = {
                    'category_id': id,
                    'questions': questions
                }
                var dataJson = JSON.stringify(data)

                // console.log(data);
                var ajaxUrl = apiUrl + '/question/update-category.php';

                $('#btnSubmitCategory').html("updating...");

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        $('#btnSubmitCategory').html("done!");

                        //update rows
                        $.each($("input[name='questionCheck']:checked"), function(){
                            $('#category'+$(this).val()).html(response.category_name);
                        });

                        console.log(response);
                        setTimeout(function(){
                            $('#btnSubmitCategory').html("Apply");

                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        alert(error);
                    }
                });
            }
            else {
                alert('Category is not selected.');
            }
        }

        function submitDelete() {

            // get all selected checkbox
            var questions = [];
            $.each($("input[name='questionCheck']:checked"), function(){
                questions.push($(this).val());
            });

            if(questions === undefined || questions.length == 0) {

                alert('Question is not selected. Please select at least 1 question.');

            } else {

                // create json to send
                var data = {
                    'questions': questions
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/delete-selected.php';

                $('#btnSubmitDelete').html("deleting...");

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        $('#btnSubmitDelete').html("done!");

                        //update rows
                        $.each($("input[name='questionCheck']:checked"), function(){
                            $('#questionRow'+$(this).val()).hide();
                        });

                        console.log(response);
                        setTimeout(function(){
                            $('#btnSubmitDelete').html("Delete All");

                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        alert(error);
                    }
                });
            }
        }

        function draftToggleQuestion(id) {

            // create json to send
            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data)

            var ajaxUrl = apiUrl + '/question/toggle-status.php';

            console.log('data');
            console.log(dataJson);

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                beforeSend: function() {
                    $('.spanStatusBtn'+id).html('<div class="spinner-border spinner-border-sm" role="status"></div>');
                },
                success: function(response){

                    console.log('response');
                    console.log(response);

                    if(response.rate == 1) {
                        $('.spanStatusBtn'+id).html('set draft');
                        $('.spanStatus'+id).html('ACTIVE');
                    }
                    if(response.rate == 0) {
                        $('.spanStatusBtn'+id).html('activate');
                        $('.spanStatus'+id).html('DRAFT');
                    }

                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });

        }

        function submitDraftToggle() {

            // get all selected checkbox
            var questions = [];
            $.each($("input[name='questionCheck']:checked"), function(){
                questions.push($(this).val());
            });

            if(questions === undefined || questions.length == 0) {

                alert('Question is not selected. Please select at least 1 question.');

            } else {

                // create json to send
                var data = {
                    'questions': questions
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/toggle-status-selected.php';

                $('#btnSubmitDraft').html("updating...");

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        $('#btnSubmitDraft').html("done!");

                        //update rows
                        // $.each($("input[name='questionCheck']:checked"), function(){
                        //     $('#questionRow'+$(this).val()).hide();
                        // });

                        console.log(response);
                        response.result.forEach(function(item, index) {

                            if(item.status == 1) {
                                $('.spanStatusBtn'+item.id).html('set draft');
                                $('.spanStatus'+item.id).html('ACTIVE');
                            }
                            if(item.status == 0) {
                                $('.spanStatusBtn'+item.id).html('activate');
                                $('.spanStatus'+item.id).html('DRAFT');
                            }
                        });

                        setTimeout(function(){
                            $('#btnSubmitDraft').html("Toggle");

                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }
        }

        function toggleRate(id) {

            // create json to send
            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data)

            var ajaxUrl = apiUrl +'/question/toggle-rate.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response){

                    console.log('response');
                    console.log(response);

                    if(response.rate == 1) {
                        $('.rate'+id).css({ color: "yellow" });
                    }
                    if(response.rate == 0) {
                        $('.rate'+id).css({ color: "grey" });
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
