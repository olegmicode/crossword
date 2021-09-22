<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';
  
// instantiate object
include_once '../../objects/level.php';
include_once '../../objects/question.php';
include_once '../../objects/category.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);
$question = new Question($db);
$category = new Category($db);

/**
 * get level by id
 * get questions in this level
 * add feature to add more questions
 * add feature to generate question positions
 * add feature to remove questions
 */

// GET
$level_id = isset($_GET['level']) ? $_GET['level'] : "";

// SET Param
($level_id != "") && $question->level_id = $level_id;
($level_id != "") && $level->id = $level_id;

$level->getById();
if(empty($level->name)) {
    header('Location: index.php');
}
$question->perpage = 100;
$question->offset = 0;
$question->level_id = $level_id;
$questionStatement = $question->getQuestions();
$rowCount = $questionStatement->rowCount();
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
                            <h1>Level</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Levels</a></li>
                                <li class="breadcrumb-item active">View</li>
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
                        <h3 class="card-title"><?php echo $level->name;?></h3>

                        <div class="card-tools">
                            <button id="btn-regenerate-grid" class="btn btn-sm btn-success mr-4">Regenerate Grid</button>    
                            <a href="https://seniorsdiscountclub.com.au/games/crossword/?l=<?php echo $level->permalink;?>" target="_blank" class="btn btn-sm btn-success mr-4">View board</a>    
                            <a href="../question/index.php?isStar=on&level=<?php echo $level->id;?>" class="btn btn-sm btn-success mr-4">Add questions</a>  
                            <a href="index.php" class="btn btn-sm btn-default">Back to list</a>    
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <p><strong>Description</strong>: <?php echo $level->description;?></p>
                        <div class="row">
                            <div class="col">
                                <small>Status: 
                                <?php 
                                    if($level->deleted == 1) { echo "Deleted"; } 
                                    else { 
                                        if($level->status == 1) { echo "Active"; } else { echo 'Hidden'; } 
                                    } 
                                ?>
                                </small>
                            </div>
                            <div class="col">
                                <small>Total Questions: <?php echo $level->totalQuestion; ?></small>
                            </div>
                        </div>
                        <hr>
                        <form action="" class="form-group row mt-4 justify-content-md-center" id="question-form" data-level-id="<?php echo $level_id; ?>" data-board-width="<?php echo $level->width; ?>" data-board-height="<?php echo $level->height; ?>">
                            <div class="col-md-6">
                                <label for="Question">Question:</label>
                                <input type="text" class="form-control" name="Question" required placeholder="Enter the question">
                            </div>
                            <div class="col-md-4">
                                <label for="Correct_answer">Correct Answer:</label>
                                <input class="form-control" type="text" name="Correct_answer" placeholder="Enter the CORRECT answer here" required>
                            </div>
                            <div class="col-md-2">
                                <label for="actions">Actions:</label>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                </div>
                            </div>
                            <div class="form-group row col-md-12 mt-4 justify-content-md-center">
                                <div class="col-md-12" style="padding: 0px">
                                    <div class="alert alert-success" id="success-alert-question" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong id="success-alert-question-message"></strong>
                                    </div>

                                    <div class="alert alert-danger" id="error-alert-question" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong id="error-alert-question-message"></strong>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php if($rowCount > 0){?>
                            
 
                            <table class="table table-striped" id="questionData">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>    
                                        <th style="width: 10px">ID</th>
                                        <th style="width: 10px"></th>
                                        <th>Question</th>
                                        <th style="width: 200px">Answers</th>
                                        <th></th>
                                        <!-- <th><button type="submit" id="btnSubmitRemove" class="btn btn-sm btn-danger btn-block">Remove All</button></th> -->
                                        <!-- <th><input type="checkbox" name="questionCheckToggle" id="questionCheckToggle" value="all" /></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $questions_arr = array();
                                $questions_arr["records"] = array();
                                while ($row = $questionStatement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    $questionChars = strlen($question);
                                    $inGameQuestion = substr($question,0,180);
                                    if($questionChars > 180) {
                                        $deletedQuestion = New Question($db);
                                        $deletedQuestion->id = $id;
                                        $deletedQuestion->flagDeleted();
                                    }
                                    $answerChars = strlen(trim($correct_answer));
                                    if($questionChars <= 180) {
                                    ?>
                                    <tr id="questionRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $no;?></td>
                                        <td><?php echo $id;?></td>
                                        <td><?php 
                                            if($rate == 0) {
                                                ?><i class="fa fa-star rate<?php echo $id;?> rateQuestion" 
                                                    data-id="<?php echo $id;?>" style="color: grey; cursor: pointer"></i><?php
                                            } elseif($rate == 1) {
                                                ?><i class="fa fa-star rate<?php echo $id;?> rateQuestion" 
                                                    data-id="<?php echo $id;?>" style="color: yellow; cursor: pointer"></i><?php
                                            }
                                        ?></td>
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
                                                    <small>Created: <?php echo date('d M Y H:i:s', strtotime($created)); ?></small>
                                                </div>
                                                <div class="col">
                                                    <small>Status: </small>
                                                    <?php 
                                                        if($deleted == 1) { echo '<span class="badge badge-secondary">DELETED</span>'; } 
                                                        else { 
                                                            if($status == 1) { 
                                                                echo '<span class="badge badge-info spanStatus'.$id.'">ACTIVE</span>'; 
                                                            } else { 
                                                                echo '<span class="badge badge-info spanStatus'.$id.'">DRAFT</span>'; 
                                                            } 
                                                        } 
                                                    ?>                                                    
                                                    <br>
                                                    <small>Reported: <?php echo $reported; ?> times</small>
                                                    <br>
                                                    <small>Last Updated: <?php 
                                                        if($modified != '0000-00-00 00:00:00' AND !empty($modified)) { echo date('d M Y H:i:s', strtotime($modified)); } else { echo '-'; } ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><span id="correct_answer<?php echo $id;?>" style="text-transform: capitalize">
                                                <?php echo $correct_answer; ?> (<?php echo $answerChars;?>)</span></strong>
                                        </td>
                                        <td>
                                            <a href="" id="btnRemoveQuestion<?php echo $id;?>" class="btnRemoveQuestion btn btn-danger btn-sm"
                                                data-id="<?php echo $id;?>" data-level-id="<?php echo $level_id;?>">remove</a></td>
                                        <!-- <td><input type="checkbox" name="questionCheck" id="questionCheck" value="<?php echo $id;?>" /></td> -->
                                    </tr>  
                                    <?php } ?>
                                <?php $no++; }?>
                                </tbody>
                            </table>

                            
                        <?php } else {?>
                            <div class="alert alert-warning">Question data is not found. Please add question to complete the question sets for this level.</div>
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
                        <!-- <div class="form-group row mt-4 justify-content-md-center">
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
                        </div> -->
                        
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

    <div class="modal fade" tabindex="-1" id="createModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-5">
                                <label for="Category">Category:</label> 
                                <select name="Category" id="categoryCreate" class="input-element form-control categoryList" required>
                                    <option value="">Select a category</option>                                    
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="Category">Country Spesific:</label> 
                                <select name="country_spesific" id="country_spesificCreate" class="input-element form-control country_spesific">
                                    <option value="">This question is specific to...</option>
                                    <option value="AU">Australia</option>
                                    <option value="US">United State</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="question_source">Question source:</label> 
                                <input class="form-control" id="question_sourceCreate" type="text" name="Answer_source" placeholder="URL Source / reference for answer" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Question">Question:</label> 
                                <textarea class="form-control" id="questionCreate" name="Question" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Correct_answer">Correct Answer:</label> 
                                <input class="form-control" id="correct_answerCreate" type="text" name="Correct_answer" placeholder="Enter the CORRECT answer here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_1">Incorrect Answer 1:</label> 
                                <input class="form-control" id="incorrect_answer_1Create" type="text" name="Incorrect_answer_1" placeholder="Enter the INCORRECT answer 1 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_2">Incorrect Answer 2:</label> 
                                <input class="form-control" id="incorrect_answer_2Create" type="text" name="Incorrect_answer_2" placeholder="Enter the INCORRECT answer 2 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_3">Incorrect Answer 3:</label> 
                                <input class="form-control" id="incorrect_answer_3Create" type="text" name="Incorrect_answer_3" placeholder="Enter the INCORRECT answer 3 here" required></input>
                            </div>
                        </div>
                        
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <button type="submit" id="submitCreateQuestion" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <div class="alert alert-success" id="success-alert-create" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Data successfully submitted!</strong>
                                </div>
                                <div class="alert alert-danger" id="error-alert" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Cannot submit the data. There is already similar question in our database.</strong>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../elements/footer-js.php'); ?>
    <script>
        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/yourlifechoices-games/games/crossword/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
        }
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

            // $('.btnAdd').click(function(e){
            //     e.preventDefault();
            //     $('#createModal').modal('show');
            // });

            // $('#submitCreateQuestion').click(function(e){
            //     e.preventDefault();
            //     submitCreateQuestion();
            // });
            
            // $('.btnDelete').click(function(e){
            //     e.preventDefault();
            //     var id = $(this).attr('data-id');
            //     deleteQuestion(id);
            // });

            // $('#btnSubmitCategory').click(function(e){
            //     e.preventDefault();
            //     var cat_id = $('#applyCategory').val();
            //     submitCategory(cat_id);
            // });

            // $('#btnSubmitDelete').click(function(e){
            //     e.preventDefault();
            //     if (confirm('Are you sure you want to delete all marked questions?')) {
            //         submitDelete();
            //     }
            // });
            
            // $('.btnDraftToggle').click(function(e){
            //     e.preventDefault();
            //     var id = $(this).attr('data-id');
            //     draftToggleQuestion(id);
            // });

            // $('#btnSubmitDraft').click(function(e){
            //     e.preventDefault();
            //     if (confirm('Are you sure you want to toggle all marked questions status?')) {
            //         submitDraftToggle();
            //     }
            // });

            // $('.rateQuestion').click(function(e){
            //     e.preventDefault();
            //     var id = $(this).attr('data-id');
            //     toggleRate(id);
            // });

            // $('#questionCheckToggle').click(function() {
            //     console.log('check all')
            //     var checkedStatus = this.checked;
            //     $('#questionData tbody tr').find('td:last :checkbox').each(function() {
            //         $(this).prop('checked', checkedStatus);
            //     });
            // });

            $('.btnRemoveQuestion').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                removeQuestion(id);
            });
            

            // populateCountries();
            // populateCategories();

            $('#question-form').on('submit', function(e){
                e.preventDefault();
                createQuestion();
                return false;
            });

            $('#btn-regenerate-grid').on('click', function(){
                let level_id = "<?php echo $level_id; ?>";
                let ajaxUrl = apiUrl + '/level/update-board-json.php';
                let data = {
                    'id': level_id
                }
                $.ajax({ 
                    type: 'POST', 
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl, 
                    data: JSON.stringify(data),
                    success: function(response){  
                        alert(response.message);
                    }, 
                    error: function(xhr,textStatus,error){ 
                        console.log(error); 
                    } 
                });
            })

            populateCategories();
        });
        
        function populateCategories() {
            var categoryId = $('#searchForm').attr('data-category-id');

            var ajaxUrl = apiUrl + "/category/read.php";
            $.ajax({
                type: 'GET', 
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl, 
                success: function(response) {
                    
                    response.records.forEach(function(item) {
                        if(categoryId == item.id) {
                            $('.categoryList').append('<option selected value="' + item.id + '">' + item.name + '</option>');
                        } else {
                            $('.categoryList').append('<option value="' + item.id + '">' + item.name + '</option>');
                        }
                    });
                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });
        }

        function removeQuestion(id) {
            let level_id = "<?php echo $level_id; ?>";
            if(id != '') {
                // create json to send
                var data = {
                    'id': id,
                    'level_id': level_id
                }
                var dataJson = JSON.stringify(data)
                
                console.log(dataJson);
                var ajaxUrl = apiUrl + '/question/remove-from-level.php';
                
                $('#questionInfo'+id).html("deleting....<hr>");

                $.ajax({ 
                    type: 'POST', 
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl, 
                    data: dataJson,
                    success: function(response){  

                        console.log(response);
                        
                            $('#btnAddQuestion'+id).html('Removed');
                            setTimeout(function(){ 
                                $('#questionRow'+id).hide();
                            }, 1000);
                    }, 
                    error: function(xhr,textStatus,error){ 
                        console.log(error); 
                    } 
                });
            }
            else {
                alert('Question is not found.'); 
            }

        }
        function createQuestion(){
            let question = $('input[name="Question"]').val();
            let correct_answer = $('input[name="Correct_answer"]').val();
            let level_id = $('#question-form').attr('data-level-id');
            
            let boardWidth = $('#question-form').attr('data-board-width');
            let boardHeight = $('#question-form').attr('data-board-height');
            let correctAnswerLength = correct_answer.length;
            let answerMaxLength = (boardWidth > boardHeight) ? boardWidth : boardHeight;

            if(correctAnswerLength > answerMaxLength){
                $('#error-alert-question-message').text("Max length of correct answer is " + answerMaxLength);
                $("#error-alert-question").fadeTo(1000, 500).slideUp(500, function(){
                    $("#error-alert-question").slideUp(500);
                });
                return;
            }

            var data = {
                'question': question,
                'correct_answer': correct_answer,
                'level_id': level_id
            }

            var dataJson = JSON.stringify(data)
            
            var ajaxUrl = apiUrl + '/question/create-with-level.php';
            $.ajax({ 
                    type: 'POST', 
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl, 
                    data: dataJson,
                    success: function(response){  
                        $('#success-alert-question-message').text(response['message']);
                        $("#success-alert-question").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert-question").slideUp(500);
                        });
                        window.location.reload();
                    }, 
                    error: function(xhr,textStatus,error){ 
                        console.log(error); 
                        $('#error-alert-question-message').text("Failed. Please try again.");
                        $("#error-alert-question").fadeTo(1000, 500).slideUp(500, function(){
                            $("#error-alert-question").slideUp(500);
                        });
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
            
            if(category != '' && question != '' && question_source !='' && correct_answer != '') {
                // create json to send
                var data = {
                    'id': question_id,
                    'category_id': category,
                    'question_source': question_source,
                    'country_spesific': country_spesific,
                    'question': question,
                    'correct_answer': correct_answer
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
    </script>
</body>
</html>
