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

$question->id = $_GET['id'];
$question->getQuestionById();

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
                            <li class="breadcrumb-item">All Questions</li>
                            <li class="breadcrumb-item active">Question</li>
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
                    <h3 class="card-title">Edit Question</h3>
                </div>
                <div class="card-body">
                    <form role="form">
                        <div class="row justify-content-md-center">
                            <div class="col-md-5">
                                <label for="Category">Category:</label> 
                                <select name="Category" id="category" class="input-element form-control" required>
                                    <option value="">Select a category</option>
                                    <option <?php if($question->category_id == 2){?>selected<?php }?> value="2">General Knowledge</option>
                                    <option <?php if($question->category_id == 8){?>selected<?php }?> value="8">TV &amp; Movies</option>
                                    <option <?php if($question->category_id == 4){?>selected<?php }?> value="4">History</option>
                                    <option <?php if($question->category_id == 12){?>selected<?php }?> value="12">Food &amp; Drink</option>
                                    <option <?php if($question->category_id == 3){?>selected<?php }?> value="3">Geography</option>
                                    <option <?php if($question->category_id == 7){?>selected<?php }?> value="7">Science &amp; Technology</option>
                                    <option <?php if($question->category_id == 13){?>selected<?php }?> value="13">Words &amp; Language</option>
                                    <option <?php if($question->category_id == 11){?>selected<?php }?> value="11">Plants &amp; Animals</option>
                                    <option <?php if($question->category_id == 5){?>selected<?php }?> value="5">Music</option>
                                    <option <?php if($question->category_id == 1){?>selected<?php }?> value="1">Books</option>
                                    <option <?php if($question->category_id == 9){?>selected<?php }?> value="9">World Politics</option>
                                    <option <?php if($question->category_id == 6){?>selected<?php }?> value="6">Sports</option>
                                    <option <?php if($question->category_id == 14){?>selected<?php }?> value="14">Maths &amp; Numbers</option>
                                    <option <?php if($question->category_id == 15){?>selected<?php }?> value="15">Religion</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="Category">Country Spesific:</label> 
                                <select name="country_spesific" id="country_spesific" class="input-element form-control country_spesific" required>
                                    <option value="">This question is specific to...</option>
                                    <option <?php if($question->country_spesific == 'AU'){?>selected<?php }?> value="AU">Australia</option>
                                    <option <?php if($question->country_spesific == 'US'){?>selected<?php }?> value="US">United State</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="question_source">Question source:</label> 
                                <input class="form-control" value="<?php echo $question->question_source;?>" id="question_source" type="text" name="Answer_source" placeholder="URL Source / reference for answer" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Question">Question:</label> 
                                <textarea class="form-control" id="question" name="Question" required><?php echo $question->question;?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Correct_answer">Correct Answer:</label> 
                                <input class="form-control" value="<?php echo $question->correct_answer;?>" id="correct_answer" type="text" name="Correct_answer" placeholder="Enter the CORRECT answer here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_1">Incorrect Answer 1:</label> 
                                <input class="form-control" value="<?php echo $question->incorrect_answer1;?>" id="incorrect_answer_1" type="text" name="Incorrect_answer_1" placeholder="Enter the INCORRECT answer 1 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_2">Incorrect Answer 2:</label> 
                                <input class="form-control" value="<?php echo $question->incorrect_answer2;?>" id="incorrect_answer_2" type="text" name="Incorrect_answer_2" placeholder="Enter the INCORRECT answer 2 here" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_3">Incorrect Answer 3:</label> 
                                <input class="form-control" value="<?php echo $question->incorrect_answer3;?>" id="incorrect_answer_3" type="text" name="Incorrect_answer_3" placeholder="Enter the INCORRECT answer 3 here" required></input>
                            </div>
                        </div>
                        
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <input type="hidden" value="<?php echo $question->id;?>" id="question_id"  name="question_id"></input>
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
        
        $('#submitButton').click(function(e){
            e.preventDefault();
            submitQuestion();
        });

    });

    function submitQuestion() {
        var question_id = $('#question_id').val();
        var category = $('#category').val();
        var question_source = $('#question_source').val();
        var country_spesific = "";
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
            var ajaxUrl = 'https://seniorsdiscountclub.com.au/games/trivia/api/question/update.php';

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

                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                        window.location.href = "https://seniorsdiscountclub.com.au/games/admin/trivia/views/question/";
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
