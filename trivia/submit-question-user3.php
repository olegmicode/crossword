<?php
// get database connection
include_once './administrator/config/database.php';

// instantiate object
include_once './administrator/objects/question.php';
include_once './administrator/objects/category.php';

$database = new Database();
$db = $database->getConnection();

$question = new Question($db);
$category = new Category($db);

// $question->id = $_GET['id'];
// $question->getQuestionById();

?>
<!DOCTYPE html>
<html>
<head>
    <?php include('./administrator/views/elements/html-head-edit.php'); ?>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Submit a Question</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Submit a Question</h3>
                    <div class="col-sm-12 text-right">
                        <p><a href="#" class="openInstruction">View Instructions</a></p>
                    </div>
                </div>
                <div class="card-body">
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
                                <button type="submit" id="submitCreateQuestion" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <div class="alert alert-success" id="success-alert" style="display: none">
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
                <!-- /.card-body -->

            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php // include('./administrator/views/elements/footer.php'); ?>

    <div class="modal fade" tabindex="-1" id="instructionModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit a trivia question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>In this task, we want you to submit an ORIGINAL trivia question and answers that you have written yourself.
                    You will also be required to select an appropriate category for the question,
                    as well as submit a correct answer and 3 incorrect answers.</p>
                    <p>For example, in the question textbox you might put ‘<i>Who directed the original Star Wars movie?</i>’.
                    On the category dropdown you would select ‘<i>Entertainment</i>’.
                    For the correct answer you would put ‘<i>George Lucas</i>’,
                    and for incorrect answers you could put ‘<i>Steven Spielberg</i>’, ‘<i>Alfred Hitchcock</i>’, and
                    ‘<i>Stanley Kubrick</i>’.</p>
                    <p><b>Please note that all trivia questions must be original / your own work.
                    All fields are mandatory & please check your spelling and grammar before submitting.</b></p>
                    <p>Note also that you should provide a URL source / reference proving the accuracy of your answer.
                    The quickest way to do this is generally to enter your question into Google and then paste the URL
                    of the top result into the URL Source / Reference textbox below.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./wrapper -->

    <?php include('./administrator/views/elements/footer-js-edit.php'); ?>

    <script>

    $(document).ready(function() {

        $('#submitCreateQuestion').click(function(e){
                e.preventDefault();
                submitCreateQuestion();
        });

        $('.openInstruction').click(function(e){
            e.preventDefault();
            $('#instructionModal').modal('show');
        });

        populateCategories();
    });

    var apiUrl = '';
    if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
        var apiUrl = 'http://localhost/ylc-games/trivia/administrator/api';
    } else {
        var apiUrl = 'https://seniorsdiscountclub.com.au/games/trivia/administrator/api';
    }

    function populateCategories() {

        var ajaxUrl = apiUrl + '/category/read.php';

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
                'incorrect_answer3': incorrect_answer_3,
                'origin' : 'User3'
            }
            var dataJson = JSON.stringify(data)

            var ajaxUrl = apiUrl + '/question/submit-question-public.php';

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
                        $("#success-alert").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert").slideUp(500);

                            location.reload();
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
    </script>
</body>
</html>
