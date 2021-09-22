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
$searchOrigin = isset($_GET['searchOrigin']) ? $_GET['searchOrigin'] : "";

// SET Param
$question->perpage = 150;
$question->offset = ($page>1) ? ($page * $question->perpage) - $question->perpage : 0;

($searchCountry != "") && $question->searchCountry = $searchCountry;
($searchCategory != "") && $question->searchCategory = $searchCategory;
($searchKeyword != "") && $question->searchKeyword = $searchKeyword;
($isMturk != "") && $question->is_mturk = 1;
($isDraft != "") && $question->isDraft = 1;
($isStar != "") && $question->rate = 1;
($searchOrigin != "") && $question->searchOrigin = $searchOrigin;

$question->deleted = 1;

$question->getAllQuestionsCount();
$statement = $question->getQuestions();
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
                                <li class="breadcrumb-item active">Deleted Questions</li>
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
                        <h3 class="card-title">Deleted Questions</h3>

                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <form role="form">
                            <div class="form-group row justify-content-md-center">
                                <div class="col-md-3">
                                    <label for="Category">Category:</label>
                                    <select name="searchCategory" id="searchCategory" class="input-element form-control categoryList">
                                        <option value="">Select a category</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="Category">Country Spesific:</label>
                                    <select name="searchCountry" id="searchCountry" class="input-element form-control country_spesific">
                                        <option value="">This question is specific to...</option>
                                        <option <?php if($searchCountry == "AU"){ echo 'selected';}?> value="AU">Australia</option>
                                        <option <?php if($searchCountry == "US"){ echo 'selected';}?> value="US">United State</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="origin">Origin:</label>
                                    <input class="form-control" id="searchOrigin" type="text" name="searchOrigin"
                                        placeholder="origin, ex: json-scraped, James, etc"
                                        <?php if($searchOrigin != ''){?>value="<?php echo $searchOrigin ?>"<?php }?> />
                                </div>
                                <div class="col-md-1">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isMturk == "on"){ echo 'checked';}?> class="form-check-input" name="isMturk" id="isMturk">
                                        <label class="form-check-label" for="isMturk">MTURK</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isDraft == "on"){ echo 'checked';}?> class="form-check-input" name="isDraft" id="isDraft">
                                        <label class="form-check-label" for="isDraft">DRAFT</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isStar == "on"){ echo 'checked';}?> class="form-check-input" name="isStar" id="isStar">
                                        <label class="form-check-label" for="isStar">Star</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <label for="searchKeyword">Keyword:</label>
                                    <input class="form-control" id="searchKeyword" type="text" name="searchKeyword"
                                        placeholder="Search question / answer / question references"
                                        <?php if($searchKeyword != ''){?>value="<?php echo $searchKeyword ?>"<?php }?> />
                                </div>

                                <div class="col-md-2">
                                    <label for="">&nbsp;</label>
                                    <button type="submit" id="btnSearch" class="btn btn-success btn-block">Search</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <?php if($rowCount > 0){?>
                            <div style="overflow-x: scroll">
                            <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo $question->totalRows;?> Total records filtered)</small>
                            <div class="clear mt-2"></div>
                            <ul class="pagination pagination-sm float-left">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="float: left">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                            href="deleted.php?searchCategory=<?php echo $searchCategory; ?>&searchCountry=<?php echo $searchCountry; ?>&searchKeyword=<?php echo $searchKeyword; ?>&searchOrigin=<?php echo $searchOrigin; ?>&isMturk=<?php echo  $isMturk; ?>&isDraft=<?php echo $isDraft; ?>&isStar=<?php echo $isStar; ?>&page=<?php echo $i;?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Question</th>
                                        <th>Answers</th>
                                        <th width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $questions_arr = array();
                                $questions_arr["records"] = array();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    $questionChars = strlen($question);
                                    $inGameQuestion = substr($question,0,180);

                                    $isLongAnswer = 0;
                                    $answerChars = strlen($correct_answer);
                                    $incorrect_answer1Chars = strlen($incorrect_answer1);
                                    $incorrect_answer2Chars = strlen($incorrect_answer2);
                                    $incorrect_answer3Chars = strlen($incorrect_answer3);
                                    if( $answerChars > 60 OR
                                        $incorrect_answer1Chars > 60 OR
                                        $incorrect_answer2Chars > 60 OR
                                        $incorrect_answer3Chars > 60
                                    ) {
                                        $isLongAnswer = 1;
                                    }

                                    ?>
                                    <tr id="questionRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $id;?></td>
                                        <td><div id="questionInfo<?php echo $id;?>"></div>
                                            <a href="#" class="btnEdit" data-id="<?php echo $id;?>">
                                                <span id="question<?php echo $id;?>" style="text-transform: capitalize"><?php echo $question; ?></span>
                                            </a>
                                            <?php if($is_mturk == 1){?>
                                                <span class="badge badge-info">mturk</span>
                                            <?php }?>
                                            <?php if($isLongAnswer == 1){?>
                                                <span class="badge badge-warning">long answer</span>
                                            <?php }?>
                                            <?php if(!empty($slug)){?>
                                                <br><small><strong>slug:</strong> <?php echo $slug;?></small>
                                            <?php }?>
                                            <?php if($questionChars > 180){?>
                                                <br>
                                                <span class="badge badge-warning">long question</span> Ingame view:<br>
                                                <small><?php echo $inGameQuestion;?></small>
                                            <?php }?>
                                            <div class="row">
                                                <div class="col">
                                                    <small>Category:
                                                        <span id="category<?php echo $id;?>">
                                                            <?php if(!empty($category_name)) { echo $category_name; } else { echo 'General Knowledge'; } ?>
                                                        <span>
                                                    </small>
                                                    <br>
                                                    <small>Country:
                                                        <span id="country_spesific<?php echo $id;?>">
                                                            <?php if(!empty($country_spesific)) { echo $country_spesific; } else { echo '-'; } ?>
                                                        </span>
                                                    </small>
                                                    <br>
                                                    <small>Reported: <?php echo $reported; ?> times</small>
                                                </div>
                                                <div class="col">
                                                    <small>Status: </small>
                                                    <?php
                                                        if($deleted == 1) { echo '<span class="badge badge-danger">Deleted</span>'; }
                                                        else {
                                                            if($status == 1) { echo "Active"; } else { echo 'Hidden'; }
                                                        }
                                                    ?>
                                                    <br>
                                                    <small>Correct count: <?php echo $correct_count; ?></small>
                                                    <br>
                                                    <small>Incorrect count: <?php echo $incorrect_count; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><i class="fa fa-check"></i> &nbsp;
                                                <span id="correct_answer<?php echo $id;?>" style="text-transform: capitalize"><?php echo $correct_answer; ?></span></strong><br>
                                            <i class="fa fa-times"></i> &nbsp;&nbsp;
                                                <span id="incorrect_answer1<?php echo $id;?>" style="text-transform: capitalize"><?php echo $incorrect_answer1; ?></span><br>
                                            <i class="fa fa-times"></i> &nbsp;&nbsp;
                                                <span id="incorrect_answer2<?php echo $id;?>" style="text-transform: capitalize"><?php echo $incorrect_answer2; ?></span><br>
                                            <i class="fa fa-times"></i> &nbsp;&nbsp;
                                                <span id="incorrect_answer3<?php echo $id;?>" style="text-transform: capitalize"><?php echo $incorrect_answer3; ?></span>
                                        </td>
                                        <td><a href="" class="btnRestore" data-id="<?php echo $id;?>">restore</a></td>
                                    </tr>
                                <?php $no++; }?>
                                </tbody>
                            </table>
                            <div style="overflow-x: scroll; margin-top: 20px">
                            <small>Page <?php echo $page;?> of <?php echo $pages;?></small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                        href="deleted.php?searchCategory=<?php echo $searchCategory; ?>&searchCountry=<?php echo $searchCountry; ?>&searchKeyword=<?php echo $searchKeyword; ?>&searchOrigin=<?php echo $searchOrigin; ?>&isMturk=<?php echo  $isMturk; ?>&isDraft=<?php echo $isDraft; ?>&isStar=<?php echo $isStar; ?>&page=<?php echo $i;?>">
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

    <div class="modal fade" tabindex="-1" id="updateModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-5">
                                <label for="Category">Category:</label>
                                <select name="Category" id="category" class="input-element form-control categoryList" required>
                                    <option value="">Select a category</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="Category">Country Spesific:</label>
                                <select name="country_spesific" id="country_spesific" class="input-element form-control country_spesific" required>
                                    <option value="">This question is specific to...</option>
                                    <option value="AU">Australia</option>
                                    <option value="US">United State</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="question_source">Question source:</label>
                                <input class="form-control" id="question_source" type="text" name="Answer_source" placeholder="URL Source / reference for answer" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Question">Question:</label>
                                <textarea class="form-control" id="question" name="Question" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Correct_answer">Correct Answer:</label>
                                <input class="form-control" id="correct_answer" type="text" name="Correct_answer" placeholder="Enter the CORRECT answer here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_1">Incorrect Answer 1:</label>
                                <input class="form-control" id="incorrect_answer_1" type="text" name="Incorrect_answer_1" placeholder="Enter the INCORRECT answer 1 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_2">Incorrect Answer 2:</label>
                                <input class="form-control" id="incorrect_answer_2" type="text" name="Incorrect_answer_2" placeholder="Enter the INCORRECT answer 2 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_3">Incorrect Answer 3:</label>
                                <input class="form-control" id="incorrect_answer_3" type="text" name="Incorrect_answer_3" placeholder="Enter the INCORRECT answer 3 here" required></input>
                            </div>
                        </div>

                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <input type="hidden" id="question_id"  name="question_id"></input>
                                <button type="submit" id="submitButton" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <div class="alert alert-success" id="success-alert" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Data successfully submitted!</strong>
                                </div>
                                <div id="apiResponse"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

            $('.btnRestore').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                restoreQuestion(id);
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
            var countryUrl ="https://seniorsdiscountclub.com.au/games/admin/trivia/data/countries.json";
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

        function restoreQuestion(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/restore.php';

                $('#questionInfo'+id).html("restoring....<hr>");

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
    </script>
</body>
</html>