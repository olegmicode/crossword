<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// instantiate object
include_once '../../objects/level.php';
include_once '../../objects/question.php';

if(isset($_COOKIE["level"])){
    $cookie_id = $_COOKIE["level"];
    $question = new Question($db);
    $questionStatement = $question->getByCookieId($cookie_id);

} else{
    $cookie_id = 'crossword' . date('Ymdhis');
    setcookie('level', $cookie_id);
}
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
                            <h1>Levels</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="index.php">All Levels</a></li>
                                <li class="breadcrumb-item active">Create Quick Level</li>
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
                        <h3 class="card-title mt-1">Create Quick Level</h3>

                        <div class="card-tools">
                            <a href="index.php" class="btn btn-sm btn-default">Back to list</a>
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <form role="form">
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-7">
                                    <label for="Category">Category:</label>
                                    <select name="Category" id="categoryCreate" class="input-element form-control categoryList" required>
                                        <option value="">Select a category</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="width">Difficulty (1-5):</label>
                                    <select name="difficultyCreate" id="difficultyCreate" class="input-element form-control" required>
                                        <option value="">Set the game difficulty</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                <label for="levelName">Level:</label>
                                    <input class="form-control" id="levelNameCreate" type="text" name="levelNameCreate" placeholder="Enter level name" required></input>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="descriptionCreate" name="descriptionCreate" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-5" style="display: none">
                                    <label for="width">Board width:</label>
                                    <input class="form-control" id="widthCreate" type="text" name="widthCreate" placeholder="Enter the board width" required></input>
                                </div>
                                <div class="col-md-5" style="display: none">
                                    <label for="height">Board height:</label>
                                    <input class="form-control" id="heightCreate" type="text" name="heightCreate" placeholder="Enter the board height" required></input>
                                </div>
                            </div>
                            <div id="questions-container">
                                <?php
                                    $questionItemNo = 0;
                                    while ($row = $questionStatement->fetch(PDO::FETCH_ASSOC)){
                                        extract($row);
                                        $questionItemNo++;?>
                                        <div class="form-group row mt-4 justify-content-md-center question-item" id="question-item-<?php echo $questionItemNo; ?>" data-question-id="<?php echo $id; ?>" data-question-item-no="<?php echo $questionItemNo; ?>">
                                            <div class="col-md-4">
                                                <label for="Question">Question:</label>
                                                <input type="text" class="form-control" id="question-<?php echo $questionItemNo ?>" name="Question[]" required value="<?php echo $question; ?>" placeholder="Enter the question">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="Correct_answer">Correct Answer:</label>
                                                <input class="form-control" id="correct_answer-<?php echo $questionItemNo ?>" type="text" name="Correct_answer[]" placeholder="Enter the CORRECT answer here" required value="<?php echo $correct_answer; ?>"></input>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="actions">Actions:</label>
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success" data-question-item-no="<?php echo $questionItemNo ?>" onclick="saveQuestionItem(this)"><i class="fa fa-check"></i></button>
                                                    <button type="button" class="btn btn-info" data-question-item-no="<?php echo $questionItemNo ?>" onclick="duplicateQuestionItem(this)"><i class="fa fa-copy"></i></button>
                                                    <button type="button" class="btn btn-danger" data-question-item-no="<?php echo $questionItemNo ?>" onclick="deleteQuestionItem(this)"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                    <button type="button" id="addQuestionBtn" class="btn btn-success">Add Question</button>
                                    <button type="submit" id="submitCreateLevel" class="btn btn-success">Generate Game Board</button>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                    <div class="alert alert-success" id="success-alert-create" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong id="success-alert-message">Data successfully submitted!</strong>
                                    </div>

                                    <div class="alert alert-danger" id="error-alert-create" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong>Cannot add new data. Data existed in database.</strong>
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

        <?php include('../elements/footer.php'); ?>
    </div>
    <!-- ./wrapper -->

    <?php include('../elements/footer-js.php'); ?>
    <script>
        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/yourlifechoices-games/games/crossword/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
        }
        var questionItemNo = 0;

        $(document).ready(function() {
            $('#submitCreateLevel').click(async function(e){
                e.preventDefault();
                let isAllQuestionSaved = await saveAllQuestions();
                if(isAllQuestionSaved){
                    submitCreateLevel();
                }
            });
            populateCategories();
            $('#addQuestionBtn').on('click', function(e){
                e.preventDefault();
                addQuestionItem(null);
            });

            let questionItemElements = $('.question-item');
            if(questionItemElements && questionItemElements.length){
                questionItemNo = questionItemElements.length;
            }
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

        function submitCreateLevel() {

            var levelName = $('#levelNameCreate').val();
            var category_id = $('#categoryCreate').val();
            var description = $('#descriptionCreate').val();
            var width = $('#widthCreate').val();
            var height = $('#heightCreate').val();
            var difficulty = $('#difficultyCreate').val();
            let cookie_id = "<?php echo $_COOKIE['level']; ?>";

            if(levelName != '' && width != '' && height !='' && description != '' && difficulty != '') {
                // create json to send
                var data = {
                    'name': levelName,
                    'category_id': category_id,
                    'description': description,
                    'width': width,
                    'height': height,
                    'difficulty': difficulty,
                    'cookie_id': cookie_id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/level/submit-level.php';

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
                            $("#error-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#error-alert-create").slideUp(500);
                            });
                        }
                        else {
                            $('#success-alert-message').text('Data successfully submitted!');
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);

                                $('#levelNameCreate').val('');
                                $('#descriptionCreate').val('');
                                $('#widthCreate').val('');
                                $('#heightCreate').val('');
                                $('#difficultyCreate').val('');
                                $('#categoryCreate').val('');
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

        function addQuestionItem(data){
            questionItemNo ++;
            $('#questions-container').append(' \
                <div class="form-group row mt-4 justify-content-md-center question-item" id="question-item-'+ questionItemNo +'" data-question-id="" data-question-item-no="'+ questionItemNo +'"> \
                    <div class="col-md-4"> \
                        <label for="Question">Question:</label> \
                        <input type="text" class="form-control" id="question-'+ questionItemNo +'" name="Question[]" required value="'+ (data ? data.question : "") +'" placeholder="Enter the question">\
                    </div> \
                    <div class="col-md-4"> \
                        <label for="Correct_answer">Correct Answer:</label> \
                        <input class="form-control" id="correct_answer-'+ questionItemNo +'" type="text" name="Correct_answer[]" placeholder="Enter the CORRECT answer here" required value="'+ (data ? data.correct_answer : "") +'"></input> \
                    </div> \
                    <div class="col-md-2"> \
                        <label for="actions">Actions:</label> \
                        <div class="col-md-12"> \
                            <button type="button" class="btn btn-success" data-question-item-no="'+ questionItemNo +'" onclick="saveQuestionItem(this)"><i class="fa fa-check"></i></button> \
                            <button type="button" class="btn btn-info" data-question-item-no="'+ questionItemNo +'" onclick="duplicateQuestionItem(this)"><i class="fa fa-copy"></i></button> \
                            <button type="button" class="btn btn-danger" data-question-item-no="'+ questionItemNo +'" onclick="deleteQuestionItem(this)"><i class="fa fa-trash"></i></button> \
                        </div> \
                    </div> \
                </div>\
            ');
        }

        function saveQuestionItem(e){
            let no = $(e).data('question-item-no');
            let question = $('#question-' + no).val();
            let correct_answer = $('#correct_answer-'+no).val();
            let country_spesific = 'AU';
            let cookie_id = "<?php echo $_COOKIE['level']; ?>";
            let question_id = $('#question-item-'+no).attr('data-question-id');

            if(question == "" || correct_answer == ""){
                alert('Please fill all question fields to submit the form.');
                return;
            }

            let boardWidth = $('input[name="widthCreate"]').val();
            let boardHeight = $('input[name="heightCreate"]').val();
            if(boardWidth == "" || boardHeight == ""){
                alert('Field width n height must be filled to determine the answers');
                return;
            }else {
                boardWidth = parseInt(boardWidth);
                boardHeight = parseInt(boardHeight);
                let answerMaxLength = (boardWidth > boardHeight) ? boardWidth : boardHeight;
                let correctAnswerLength = correct_answer.length;
                if(correctAnswerLength > answerMaxLength){
                    alert("Max length of correct answer is " + answerMaxLength);
                    return;
                }
            }


            if(question_id){
                var data = {
                    'question': question,
                    'correct_answer': correct_answer,
                    'country_spesific': country_spesific,
                    'id': question_id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/create-without-level-update.php';
                $.ajax({
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        url: ajaxUrl,
                        data: dataJson,
                        success: function(response){
                            $('#success-alert-message').text(response['message']);
                            $('#question-item-'+no).attr('data-question-id', response['id']);
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);
                            });
                        },
                        error: function(xhr,textStatus,error){
                            console.log(error);
                        }
                    });
            }else {
                var data = {
                    'question': question,
                    'correct_answer': correct_answer,
                    'country_spesific': country_spesific,
                    'cookie_id': cookie_id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/question/create-without-level.php';
                $.ajax({
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        url: ajaxUrl,
                        data: dataJson,
                        success: function(response){
                            $('#success-alert-message').text(response['message']);
                            $('#question-item-'+no).attr('data-question-id', response['id']);
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);
                            });
                        },
                        error: function(xhr,textStatus,error){
                            console.log(error);
                        }
                    });
            }
        }

        function deleteQuestionItem(e){
            let no = $(e).data('question-item-no');
            let question_id = $('#question-item-'+no).data('question-id');

            if(question_id){
                var data = {
                    'id': question_id
                }
                var dataJson = JSON.stringify(data)
                var ajaxUrl = apiUrl + '/question/delete.php';
                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){
                        $('#success-alert-message').text(response['message']);
                        $('#question-item-'+no).remove();
                        $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert-create").slideUp(500);
                        });
                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }else {
                $('#question-item-'+no).remove();
            }
        }

        function duplicateQuestionItem(e){
            let no = $(e).data('question-item-no');
            let question = $('#question-'+no).val();
            let correct_answer = $('#correct_answer-'+no).val();

            addQuestionItem({
                question: question,
                correct_answer: correct_answer
            });
        }

        async function saveAllQuestions(){
            let status = 1;
            let questionElements = $('.question-item');
            if(questionElements && questionElements.length > 0){
                for(let i=0; i<questionElements.length; i++){
                    let questionElement = questionElements[i];
                    let question_id = $(questionElement).attr('data-question-id');
                    let no = $(questionElement).attr('data-question-item-no');
                    let question = $('#question-'+no).val();
                    let correct_answer = $('#correct_answer-'+no).val();

                    let boardWidth = $('input[name="widthCreate"]').val();
                    let boardHeight = $('input[name="heightCreate"]').val();
                    if(boardWidth == "" || boardHeight == ""){
                        alert('Field width n height must be filled to determine the answers');
                        status = 0;
                    }else {
                        boardWidth = parseInt(boardWidth);
                        boardHeight = parseInt(boardHeight);
                        let answerMaxLength = (boardWidth > boardHeight) ? boardWidth : boardHeight;
                        let correctAnswerLength = correct_answer.length;
                        if(correctAnswerLength > answerMaxLength){
                            alert("Max length of correct answer is " + answerMaxLength);
                            status = 0;
                        }
                    }

                    if(!question_id && status == 1){
                        if(question == "" || correct_answer == ""){
                            status = 0;
                            alert('Please fill all question fields to submit the form.');
                        }else {
                            let country_spesific = 'AU';
                            let cookie_id = "<?php echo $_COOKIE['level']; ?>";

                            var data = {
                                'question': question,
                                'correct_answer': correct_answer,
                                'country_spesific': country_spesific,
                                'cookie_id': cookie_id
                            }
                            var dataJson = JSON.stringify(data)

                            var ajaxUrl = apiUrl + '/question/create-without-level.php';
                            await $.ajax({
                                type: 'POST',
                                cache: false,
                                contentType: false,
                                processData: false,
                                url: ajaxUrl,
                                data: dataJson,
                                success: function(response){
                                    $('#question-item-'+no).attr('data-question-id', response['id']);
                                    status = 1;
                                },
                                error: function(xhr,textStatus,error){
                                    console.log(error);
                                    status = 0;
                                }
                            });
                        }
                    }

                    if(status == 0) break;
                }
            }
            return status == 1;
        }
    </script>
</body>
</html>
