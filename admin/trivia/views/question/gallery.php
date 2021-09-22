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

$isScrapSource = isset($_GET['isScrapSource']) ? $_GET['isScrapSource'] : "";
$isMturk = isset($_GET['isMturk']) ? $_GET['isMturk'] : "";
$isDraft = isset($_GET['isDraft']) ? $_GET['isDraft'] : "";
$isStar = isset($_GET['isStar']) ? $_GET['isStar'] : "";
$searchOrigin = isset($_GET['searchOrigin']) ? $_GET['searchOrigin'] : "";

// SET Param
$question->perpage = 300;
$question->offset = ($page>1) ? ($page * $question->perpage) - $question->perpage : 0;

($searchCountry != "") && $question->searchCountry = $searchCountry;
($searchCategory != "") && $question->searchCategory = $searchCategory;
($searchKeyword != "") && $question->searchKeyword = $searchKeyword;
($isMturk != "") && $question->is_mturk = 1;
($isDraft != "") && $question->isDraft = 1;
($isScrapSource != "") && $question->scrap_source_status = 1;
($isStar != "") && $question->rate = $isStar;
($searchOrigin != "") && $question->searchOrigin = $searchOrigin;

$question->getAllQuestionsCount();

// redo checking
($isStar != "") && $question->rate = $isStar;
$statement = $question->getQuestions();
$rowCount = $statement->rowCount();
$pages = ceil($question->totalRows / $question->perpage);

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
                            <h1>Questions</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">All Questions</li>
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
                        <h3 class="card-title">All Questions</h3>

                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <form role="form" id="searchForm" data-category-id="<?php echo $searchCategory;?>">
                            <div class="form-group row justify-content-md-center">
                                <div class="col-md-4">
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
                                        <input type="checkbox" <?php if($isStar == 1){ echo 'checked';}?> class="form-check-input" value="1" name="isStar" id="isStar">
                                        <label class="form-check-label" for="isStar">Star</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isStar == -1){ echo 'checked';}?> class="form-check-input" value="-1" name="isStar" id="isNoStar">
                                        <label class="form-check-label" for="isNoStar">No Star</label>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isScrapSource == "on"){ echo 'checked';}?> class="form-check-input" name="isScrapSource" id="isScrapSource">
                                        <label class="form-check-label" for="isScrapSource">Scraped</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="searchKeyword">Keyword:</label>
                                    <input class="form-control" id="searchKeyword" type="text" name="searchKeyword"
                                        placeholder="Search question / answer / question references"
                                        <?php if($searchKeyword != ''){?>value="<?php echo $searchKeyword ?>"<?php }?> />
                                </div>
                                <div class="col-md-4">
                                    <label for="origin">Origin:</label>
                                    <input class="form-control" id="searchOrigin" type="text" name="searchOrigin"
                                        placeholder="origin, ex: json-scraped, James, etc"
                                        <?php if($searchOrigin != ''){?>value="<?php echo $searchOrigin ?>"<?php }?> />
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
                                <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo number_format($question->totalRows);?> Total records filtered)</small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                            href="gallery.php?searchCategory=<?php echo $searchCategory; ?>&searchCountry=<?php echo $searchCountry; ?>&searchKeyword=<?php echo $searchKeyword; ?>&searchOrigin=<?php echo $searchOrigin; ?>&isMturk=<?php echo $isMturk; ?>&isDraft=<?php echo $isDraft; ?>&isStar=<?php echo $isStar; ?>&isScrapSource=<?php echo $isScrapSource; ?>&page=<?php echo $i;?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="alert alert-warning">Clear the broken, duplicated and missing image by clicking the image.</div>
                                    <?php
                                    $no = 1;
                                    $questions_arr = array();
                                    $questions_arr["records"] = array();
                                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                        extract($row);
                                        $imgUrl = $scrap_image;
                                        (empty($scrap_image)) && $imgUrl = '../../assets/img/placeholder-300x200.jpg';

                                        $imgUrlAlt = $scrap_image_question;
                                        (empty($scrap_image_question)) && $imgUrlAlt = '../../assets/img/placeholder-300x200.jpg';
                                    ?>
                                        <div class="img_question">
                                            <span class="img_id">ID: #<?php echo $id;?> | <a href="#" class="openModal" data-id="<?php echo $id;?>">view question</a></span>
                                            <div class="img_thumb" data-id="<?php echo $id;?>">
                                                <a href="<?php echo $imgUrl; ?>" target="_blank">
                                                    <img src="<?php echo $imgUrl; ?>" id="img<?php echo $id;?>" alt="<?php echo $question;?>" class="img-fluid img-thumbnail" />
                                                </a>
                                            </div>
                                            <div class="img_thumb_alt" data-id="<?php echo $id;?>">
                                                <a href="<?php echo $imgUrlAlt; ?>" target="_blank">
                                                    <img src="<?php echo $imgUrlAlt; ?>" id="imgAlt<?php echo $id;?>" alt="<?php echo $question;?>" class="img-fluid img-thumbnail" />
                                                </a>
                                            </div>
                                            <div class="clearfix"></div>

                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                            <div style="overflow-x: scroll; margin-top: 20px">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?></small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                        href="gallery.php?searchCategory=<?php echo $searchCategory; ?>&searchCountry=<?php echo $searchCountry; ?>&searchKeyword=<?php echo $searchKeyword; ?>&searchOrigin=<?php echo $searchOrigin; ?>&isMturk=<?php echo $isMturk; ?>&isDraft=<?php echo $isDraft; ?>&isStar=<?php echo $isStar; ?>&isScrapSource=<?php echo $isScrapSource; ?>&page=<?php echo $i;?>">
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
                                <select name="country_spesific" id="country_spesific" class="input-element form-control country_spesific">
                                    <option value="">This question is specific to...</option>
                                    <option value="AU">Australia</option>
                                    <option value="US">United State</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="question_source">Question source:</label>
                                <input class="form-control" id="question_source" type="text" name="Answer_source" placeholder="URL Source / reference for answer"></input>
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
                        <div class="form-group row mt-4 mb-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Incorrect_answer_3">Incorrect Answer 3:</label>
                                <input class="form-control" id="incorrect_answer_3" type="text" name="Incorrect_answer_3" placeholder="Enter the INCORRECT answer 3 here" required></input>
                            </div>
                        </div>

                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="scrap_answer">Scraped Answer:</label>
                                <input class="form-control" id="scrap_answer" type="text" name="scrap_answer" ></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-3">
                                <label for="scrap_image">Scraped Images:</label>
                                <div class="scrap_image_wrapper" data-id="">
                                    <img src="" id="scrap_image" style="border: 1px solid #dddddd;" class="img-fluid img-rounded selectedImage" alt="" />
                                    <span class="selected_text"></span>
                                </div>
                                <div class="scrap_image_alt_wrapper mt-4" data-id="">
                                    <img src="" id="scrap_image_alt" style="border: 1px solid #dddddd;" class="img-fluid img-rounded" alt="" />
                                    <span class="selected_text"></span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group row justify-content-md-center">
                                    <div class="col-md-12">
                                        <label for="scrap_title">Scraped title:</label>
                                        <input class="form-control" id="scrap_title" type="text" name="scrap_title"></input>
                                    </div>
                                </div>
                                <div class="form-group row mt-4 justify-content-md-center">
                                    <div class="col-md-12">
                                        <label for="answer_info">Scraped Content:</label>
                                        <textarea class="form-control" rows="6" id="answer_info" name="answer_info"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-md-center">
                            <div class="col-md-10">
                                <hr>
                                <input type="hidden" id="question_id"  name="question_id"></input>
                                <button type="submit" id="submitButton" class="btn btn-success">Save Changes</button>
                                <a href="#" id="clearScrapData" class="btn btn-default ml-4">Clear Scraped Data</a>
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

            $('.openModal').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                getQuestion(id);
            });

            $('#submitButton').click(function(e){
                e.preventDefault();
                submitQuestion();
            });

            $('.img_thumb').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you want to delete this image?')) {
                    clearImage(id, 0);
                }
            });

            $('.img_thumb_alt').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you want to delete this image?')) {
                    clearImage(id, 1);
                }
            });

            $('.scrap_image_wrapper').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                setAltImage(id, false);
            });
            $('.scrap_image_alt_wrapper').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                setAltImage(id, true);
            });

            populateCategories();

        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/trivia/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/trivia/api';
        }

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
                        if(item.id != 10) {
                            if(categoryId == item.id) {
                                $('.categoryList').append('<option selected value="' + item.id + '">' + item.name + '</option>');
                            } else {
                                $('.categoryList').append('<option value="' + item.id + '">' + item.name + '</option>');
                            }
                        }
                    });
                },
                error: function(xhr,textStatus,error) {
                    console.log(error);
                }
            });
        }

        function clearImage(id, isAlt) {

            var ajaxUrl = apiUrl + "/question/clear-img.php";
            var data = {
                'id': id,
                'isAlt': isAlt
            }
            var dataJson = JSON.stringify(data);

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response) {

                    // clear the img / alt
                    if(isAlt) {
                        $('#imgAlt'+id).attr('src', '../../assets/img/placeholder-300x200.jpg');
                    } else {
                        $('#img'+id).attr('src', '../../assets/img/placeholder-300x200.jpg');
                    }

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
                    $('#scrap_answer').val(response.scrap_answer);
                    $('#answer_info').val(response.answer_info);
                    $('#scrap_title').val(response.scrap_title);

                    // reset selected
                    $('.scrap_image_alt_wrapper .selected_text').html('');
                    $('.scrap_image_wrapper .selected_text').html('');

                    if(response.scrap_image) {
                        $('#scrap_image').attr('src', response.scrap_image);
                        $('#scrap_image').attr('title', response.scrap_title);
                        $('#scrap_image').attr('alt', response.scrap_title);
                    } else {
                        $('#scrap_image').attr('src', '../../assets/img/placeholder-300x200.jpg');
                        $('#scrap_image').attr('title', response.scrap_title);
                        $('#scrap_image').attr('alt', response.scrap_title);
                    }

                    if(response.scrap_image_question) {
                        $('#scrap_image_alt').attr('src', response.scrap_image_question);
                        $('#scrap_image_alt').attr('title', response.scrap_title);
                        $('#scrap_image_alt').attr('alt', response.scrap_title);
                    } else {
                        $('#scrap_image_alt').attr('src', '../../assets/img/placeholder-300x200.jpg');
                        $('#scrap_image_alt').attr('title', response.scrap_title);
                        $('#scrap_image_alt').attr('alt', response.scrap_title);
                    }

                    if(response.is_alt_img == 1) {
                        $('.scrap_image_alt_wrapper .selected_text').html('Selected image');
                    } else {
                        $('.scrap_image_wrapper .selected_text').html('Selected image');
                    }

                    $('.scrap_image_wrapper').attr('data-id', id);
                    $('.scrap_image_alt_wrapper').attr('data-id', id);

                    $('#updateModal').modal('show');
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
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
            var scrap_answer = $('#scrap_answer').val();
            var answer_info = $('#answer_info').val();
            var scrap_title = $('#scrap_title').val();

            if(category != '' && question != '' && correct_answer != '' && incorrect_answer_1 != '' && incorrect_answer_2 != '' && incorrect_answer_3 != '') {
                // create json to send
                var data = {
                    'id': question_id,
                    'category_id': category,
                    'question_source': (question_source) ? question_source : '',
                    'country_spesific': country_spesific,
                    'question': question,
                    'correct_answer': correct_answer,
                    'incorrect_answer1': incorrect_answer_1,
                    'incorrect_answer2': incorrect_answer_2,
                    'incorrect_answer3': incorrect_answer_3,
                    'answer_info': (answer_info) ? answer_info : '',
                    'scrap_title': (scrap_title) ? scrap_title : '',
                    'scrap_answer': (scrap_answer) ? scrap_answer : '',
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
                        alert('Please select category for this question.');
                    }
                });
            }
            else {
                alert('Please fill all fields to submit the form.');
            }

        }

        function setAltImage(id, isAltImage) {
            // create json to send
            var data = {
                'id': id,
                'isAltImage': isAltImage
            }
            console.log(data);
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl +'/question/set-alt-image.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response){

                    // reset selected
                    $('.scrap_image_alt_wrapper .selected_text').html('');
                    $('.scrap_image_wrapper .selected_text').html('');

                    if(response.status == 1) {
                        // set alt image as selected
                        $('.scrap_image_alt_wrapper .selected_text').html('Selected image');
                    }
                    if(response.status == 0) {
                        // set main image as selected
                        $('.scrap_image_wrapper .selected_text').html('Selected image');
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
