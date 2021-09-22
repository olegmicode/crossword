<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';
  
$database = new Database();
$db = $database->getConnection();

// instantiate object
include_once '../../objects/level.php';
include_once '../../objects/word.php';

if(isset($_COOKIE["wordsearch_level"])){
    $cookie_id = $_COOKIE["wordsearch_level"];
    $word = new Word($db);
    $wordStatement = $word->getByCookieId($cookie_id);
} else{
    $cookie_id = 'wordsearch' . date('Ymdhis');
    setcookie('wordsearch_level', $cookie_id);
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
                                    <select name="Category" id="categoryCreate" class="input-element form-control categoryList">
                                        <option value="">Select a category</option>                                    
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="width">Difficulty (1-5):</label> 
                                    <select name="difficultyCreate" id="difficultyCreate" class="input-element form-control">
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
                                    <textarea class="form-control" id="descriptionCreate" name="descriptionCreate"></textarea>
                                </div>
                            </div>
                            <!-- <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-5">
                                    <label for="width">Alphabet:</label> 
                                    <input class="form-control" id="alphabetCreate" type="text" name="alphabetCreate" placeholder="Enter the alphabet that can be used in game" required></input>
                                </div>
                                <div class="col-md-5">
                                    <label for="width">Initial Score:</label> 
                                    <input class="form-control" id="initial_scoreCreate" type="text" name="initial_scoreCreate" placeholder="How many score to put in start" required></input>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-5">
                                    <label for="width">Board Size:</label> 
                                    <input class="form-control" id="sizeCreate" type="text" name="sizeCreate" placeholder="Enter the board size" required></input>
                                </div>
                                <div class="col-md-5">
                                    <label for="height">Hint:</label> 
                                    <input class="form-control" id="hintCreate" type="text" name="hintCreate" placeholder="How many hint available" required></input>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-5">
                                    <label for="width">Time Interval:</label> 
                                    <input class="form-control" id="time_intervalCreate" type="text" name="time_intervalCreate" placeholder="Time interval used to deduct points" required></input>
                                </div>
                                <div class="col-md-5">
                                    <label for="height">Point Deduction:</label> 
                                    <input class="form-control" id="point_deductionCreate" type="text" name="point_deductionCreate" placeholder="How many point will be deducted in time interval" required></input>
                                </div>
                            </div> -->
                            <div id="words-container">
                                <?php
                                    $wordItemNo = 0;
                                    while ($row = $wordStatement->fetch(PDO::FETCH_ASSOC)){
                                        extract($row); 
                                        $wordItemNo++;?>
                                        <div class="form-group row mt-4 justify-content-md-center word-item" id="word-item-<?php echo $wordItemNo; ?>" data-word-id="<?php echo $id; ?>" data-word-item-no="<?php echo $wordItemNo; ?>">
                                            <div class="col-md-4">
                                                <label>Word:</label>
                                                <input type="text" class="form-control" name="word_name[]" required placeholder="Enter word name" id="word_name-<?php echo $wordItemNo; ?>" value="<?php echo $name; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Description (optional):</label>
                                                <input class="form-control"type="text" name="word_description[]" placeholder="Enter word description" id="word_description-<?php echo $wordItemNo; ?>" value="<?php echo $description; ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="actions">Actions:</label>
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success" data-word-item-no="<?php echo $wordItemNo; ?>" onclick="saveWordItem(this)"><i class="fa fa-check"></i></button>
                                                    <button type="button" class="btn btn-info" data-word-item-no="<?php echo $wordItemNo; ?>" onclick="duplicateWordItem(this)"><i class="fa fa-copy"></i></button>
                                                    <button type="button" class="btn btn-danger" data-word-item-no="<?php echo $wordItemNo; ?>" onclick="deleteWordItem(this)"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                    <button type="button" id="addWordBtn" class="btn btn-success">Add Word</button>
                                    <button type="submit" id="submitCreateLevel" class="btn btn-success">Save Changes</button>
                                    <button type="button" id="setDefaultValue" class="btn btn-default ml-4">Set default value</button>
                                </div>
                            </div>
                            <div class="form-group row mt-4 justify-content-md-center">
                                <div class="col-md-10">
                                    <div class="alert alert-success" id="success-alert-create" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong id="success-alert-create-message"></strong>
                                    </div>
                                    <div class="alert alert-danger" id="error-alert-create" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <strong>Data is already exist.</strong>
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
            var apiUrl = 'http://localhost/yourlifechoices-games/games/wordsearch/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/wordsearch/api';
        }
        var wordItemNo = 0;

        $(document).ready(function() {
            $('#submitCreateLevel').click(async function(e){
                e.preventDefault();
                let isAllWordSaved = await saveAllWords();
                if(isAllWordSaved){
                    submitCreateLevel();
                }
            });
            $('#setDefaultValue').click(function(e){
                e.preventDefault();

                $('#difficultyCreate').val(3);
                // $('#alphabetCreate').val('abcdefghijklmnopqrstuvwxyz');
                // $('#initial_scoreCreate').val(0);
                // $('#sizeCreate').val(10);
                // $('#hintCreate').val(3);
                // $('#time_intervalCreate').val(15);
                // $('#point_deductionCreate').val(1);
            });

            populateCategories();
            $('#addWordBtn').on('click', function(e){
                e.preventDefault();
                addWordItem(null);
            });

            let wordItemElements = $('.word-item');
            if(wordItemElements && wordItemElements.length){
                wordItemNo = wordItemElements.length;
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
            let cookie_id = "<?php echo $_COOKIE['wordsearch_level']; ?>";
            var levelName = $('#levelNameCreate').val();
            var category_id = $('#categoryCreate').val() ? $('#categoryCreate').val() : 2;
            var description = $('#descriptionCreate').val();
            // var alphabet = $('#alphabetCreate').val();
            // var size = $('#sizeCreate').val();
            // var hint = $('#hintCreate').val();
            // var time_interval = $('#time_intervalCreate').val();
            // var point_deduction = $('#point_deductionCreate').val();
            // var initial_score = $('#initial_scoreCreate').val();
            var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var size = 10;
            var hint = 3;
            var time_interval = 15;
            var point_deduction = 1;
            var initial_score = 0;
            var difficulty = $('#difficultyCreate').val() ? $('#difficultyCreate').val() : 3;

            if(levelName != '') {
                // create json to send
                var data = {
                    'name': levelName,
                    'category_id': category_id,
                    'description': description,
                    'alphabet': alphabet,
                    'size': size,
                    'hint': hint,
                    'time_interval': time_interval,
                    'point_deduction': point_deduction,
                    'initial_score': initial_score,
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
                            $('#success-alert-create-message').text('Data successfully submitted!');
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);
                                
                                $('#levelNameCreate').val('');
                                $('#descriptionCreate').val('');
                                // $('#alphabetCreate').val('');
                                // $('#sizeCreate').val('');
                                // $('#hintCreate').val('');
                                // $('#time_intervalCreate').val('');
                                // $('#point_deductionCreate').val('');
                                // $('#initial_scoreCreate').val('');
                                $('#difficultyCreate').val('');

                                $('#createModal').modal('hide');
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

        function addWordItem(data){
            wordItemNo ++;
            $('#words-container').append(' \
                <div class="form-group row mt-4 justify-content-md-center word-item" id="word-item-'+ wordItemNo +'" data-word-id="" data-word-item-no="'+ wordItemNo +'"> \
                    <div class="col-md-4"> \
                        <label>Word:</label> \
                        <input type="text" class="form-control" name="word_name[]" required placeholder="Enter word name" id="word_name-'+ wordItemNo +'" value="'+ (data ? data.name : "") +'"> \
                    </div> \
                    <div class="col-md-4"> \
                        <label>Description (optional):</label> \
                        <input class="form-control"type="text" name="word_description[]" placeholder="Enter word description" id="word_description-'+ wordItemNo +'" value="'+ (data ? data.description : "") +'"> \
                    </div> \
                    <div class="col-md-2"> \
                        <label for="actions">Actions:</label> \
                        <div class="col-md-12"> \
                            <button type="button" class="btn btn-success" data-word-item-no="'+ wordItemNo +'" onclick="saveWordItem(this)"><i class="fa fa-check"></i></button> \
                            <button type="button" class="btn btn-info" data-word-item-no="'+ wordItemNo +'" onclick="duplicateWordItem(this)"><i class="fa fa-copy"></i></button> \
                            <button type="button" class="btn btn-danger" data-word-item-no="'+ wordItemNo +'" onclick="deleteWordItem(this)"><i class="fa fa-trash"></i></button> \
                        </div> \
                    </div> \
                </div> \
            ');
        }

        function saveWordItem(e){
            let no = $(e).data('word-item-no');
            let name = $('#word_name-' + no).val();
            let description = $('#word_description-'+no).val();
            let cookie_id = "<?php echo $_COOKIE['wordsearch_level']; ?>";
            let word_id = $('#word-item-'+no).attr('data-word-id');
            
            if(name == ""){
                alert('Please fill Word field to submit the form.');
                return;
            }

            if(!/^[A-Z]+$/.test(name)){
                alert('all answer MUST alphabet and uppercase');
                return;
            }

            if(checkIsDuplicated(name, no)){
                alert('The word is duplicated');
                return;
            }

            let boardSize = $('input[name="sizeCreate"]').val();
            if(boardSize == ""){
                alert('Field board size must be filled to determine the Word name');
                return;
            }else {
                boardSize = parseInt(boardSize);
                let nameLength = name.length;
                if(nameLength > boardSize){
                    alert("Max length of Word name is " + boardSize);
                    return;
                }
            }


            if(word_id){
                var data = {
                    'id': word_id,
                    'name': name,
                    'description': description,
                    'level_id': 0,
                    'category_id': 1    
                }
                var dataJson = JSON.stringify(data)
                
                var ajaxUrl = apiUrl + '/word/update.php';
                $.ajax({ 
                        type: 'POST', 
                        cache: false,
                        contentType: false,
                        processData: false,
                        url: ajaxUrl, 
                        data: dataJson,
                        success: function(response){  
                            $('#success-alert-create-message').text(response['message']);
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
                    'name': name,
                    'description': description,
                    'level_id': 0,
                    'category_id': 1,
                    'cookie_id': cookie_id,
                    'can_duplicated': true      
                }
                var dataJson = JSON.stringify(data)
                
                var ajaxUrl = apiUrl + '/word/submit-word.php';
                $.ajax({ 
                        type: 'POST', 
                        cache: false,
                        contentType: false,
                        processData: false,
                        url: ajaxUrl, 
                        data: dataJson,
                        success: function(response){  
                            if(response['message'] == 'duplicated'){
                                $("#error-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                    $("#error-alert-create").slideUp(500);
                                });
                            }else {
                                $('#success-alert-create-message').text("Word has been created !");
                                $('#word-item-'+no).attr('data-word-id', response['id']);
                                $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                    $("#success-alert-create").slideUp(500);
                                });
                            }
                        }, 
                        error: function(xhr,textStatus,error){ 
                            console.log(error); 
                        } 
                    });
            }
        }

        function duplicateWordItem(e){
            let no = $(e).data('word-item-no');
            let name = $('#word_name-'+no).val();
            let description = $('#word_description-'+no).val();

            addWordItem({
                name: name,
                description: description
            });
        }

        function deleteWordItem(e){
            let no = $(e).data('word-item-no');
            let word_id = $('#word-item-'+no).data('word-id');

            if(word_id){
                var data = {
                    'id': word_id     
                }
                var dataJson = JSON.stringify(data)
                var ajaxUrl = apiUrl + '/word/delete.php';
                $.ajax({ 
                    type: 'POST', 
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl, 
                    data: dataJson,
                    success: function(response){  
                        $('#success-alert-create-message').text(response['message']);
                        $('#word-item-'+no).remove();
                        $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                            $("#success-alert-create").slideUp(500);
                        });
                    }, 
                    error: function(xhr,textStatus,error){ 
                        console.log(error); 
                    } 
                });
            }else {
                $('#word-item-'+no).remove();
            }
        }

        async function saveAllWords(){
            if(checkIsDuplicated()){
                alert('The word is duplicated');
                return;
            }

            let cookie_id = "<?php echo $_COOKIE['wordsearch_level']; ?>";
            let status = 1;
            let wordElements = $('.word-item');
            if(wordElements && wordElements.length > 0){
                for(let i=0; i<wordElements.length; i++){
                    let wordElement = wordElements[i];
                    let word_id = $(wordElement).attr('data-word-id');
                    let no = $(wordElement).attr('data-word-item-no');
                    let name = $('#word_name-'+no).val();
                    let description = $('#word_description-'+no).val();

                    let boardSize = $('input[name="sizeCreate"]').val();
                    if(boardSize == ""){
                        alert('Field board size must be filled to determine the Word name');
                        status = 0;
                    }else {
                        boardSize = parseInt(boardSize);
                        let nameLength = name.length;
                        if(nameLength > boardSize){
                            alert("Max length of Word name is " + boardSize);
                            status = 0;
                        }
                    }

                    if(status == 1){
                        if(name == ""){
                            status = 0;
                            alert('Please fill Word field to submit the form.');
                        }else {
                            if(word_id){
                                var data = {
                                    'id': word_id,
                                    'name': name,
                                    'description': description,
                                    'level_id': 0,
                                    'category_id': 1    
                                }
                                var dataJson = JSON.stringify(data)
                                
                                var ajaxUrl = apiUrl + '/word/update.php';
                                await $.ajax({ 
                                        type: 'POST', 
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        url: ajaxUrl, 
                                        data: dataJson,
                                        success: function(response){
                                            status = 1;
                                        }, 
                                        error: function(xhr,textStatus,error){ 
                                            status = 0;
                                            console.log(error); 
                                        } 
                                    });
                            }else {
                                var data = {
                                    'name': name,
                                    'description': description,
                                    'level_id': 0,
                                    'category_id': 1,
                                    'cookie_id': cookie_id,
                                    'can_duplicated': true     
                                }
                                var dataJson = JSON.stringify(data)
                                
                                var ajaxUrl = apiUrl + '/word/submit-word.php';
                                await $.ajax({ 
                                        type: 'POST', 
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        url: ajaxUrl, 
                                        data: dataJson,
                                        success: function(response){  
                                            if(response['message'] == 'duplicated'){
                                                $("#error-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                                    $("#error-alert-create").slideUp(500);
                                                });
                                                status = 0;
                                            }else {
                                                status = 1;
                                            }
                                        }, 
                                        error: function(xhr,textStatus,error){ 
                                            console.log(error); 
                                            status = 0;
                                        } 
                                    });
                            }
                        }
                    }

                    if(status == 0) break;
                }
            }
            return status == 1;
        }

        function checkIsDuplicated(new_word, selected_no){
            let cookie_id = "<?php echo $_COOKIE['wordsearch_level']; ?>";
            let wordElements = $('.word-item');
            if(wordElements && wordElements.length > 0){
                for(let i=0; i<wordElements.length; i++){
                    let wordElement = wordElements[i];
                    let no = $(wordElement).attr('data-word-item-no');
                    let name = $('#word_name-'+no).val();
                    if(new_word){
                        if(name == new_word && (selected_no == null || no != selected_no)){
                            return true;
                        }
                    } else {
                        if(checkIsDuplicated(name, no)){
                            return true;
                        }
                    }
                }
            }

            return false;
        }
        
    </script>
</body>
</html>
