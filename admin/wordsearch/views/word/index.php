<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

// instantiate object
include_once '../../objects/word.php';

$database = new Database();
$db = $database->getConnection();

$word = new Word($db);

// GET
$page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
$searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : "";
$searchKeyword = isset($_GET['searchKeyword']) ? $_GET['searchKeyword'] : "";
$level = isset($_GET['level']) ? $_GET['level'] : "";
$isDraft = isset($_GET['isDraft']) ? $_GET['isDraft'] : "";

// SET Param
$word->perpage = 50;
$word->offset = ($page>1) ? ($page * $word->perpage) - $word->perpage : 0;

($searchCategory != "") && $word->searchCategory = $searchCategory;
($searchKeyword != "") && $word->searchKeyword = $searchKeyword;
($isDraft != "") && $word->isDraft = 1;

$word->getAllWordsCount();

$statement = $word->getWords();
$rowCount = $statement->rowCount();
$pages = ceil($word->totalRows / $word->perpage);

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
                            <h1>Words</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">All Words</li>
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
                        <h3 class="card-title mt-1">All Words</h3>

                        <div class="card-tools">
                            <a href="" id="btnAdd" class="btn btn-sm btn-success btnAdd">Add Word</a>
                            <?php if(!empty($level)){?>
                                <a href="../level/view.php?level=<?php echo $level;?>" id="#" class="btn btn-sm btn-default ml-4">Back to Level</a>
                            <?php }?>
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
                                <div class="col-md-4">
                                    <label for="searchKeyword">Keyword:</label>
                                    <input class="form-control" id="searchKeyword" type="text" name="searchKeyword"
                                        placeholder="Search question / answer / question references"
                                        <?php if($searchKeyword != ''){?>value="<?php echo $searchKeyword ?>"<?php }?> />
                                </div>
                                <div class="col-md-2">
                                    <label for="">&nbsp;</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" <?php if($isDraft == "on"){ echo 'checked';}?> class="form-check-input" name="isDraft" id="isDraft">
                                        <label class="form-check-label" for="isDraft">DRAFT</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="">&nbsp;</label>
                                    <button type="submit" id="btnSearch" class="btn btn-success btn-block">Search</button>
                                    <input type="hidden" name="level" value="<?php echo $level;?>" />
                                </div>
                            </div>
                        </form>
                        <hr>
                        <?php if($rowCount > 0){?>
                            <div style="overflow-x: scroll">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo number_format($word->totalRows);?> Total records filtered)</small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                            href="index.php?level=<?php echo $level;?>&page=<?php echo $i;?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">ID</th>
                                        <th>Word</th>
                                        <th>Category</th>
                                        <th width="150">Status</th>
                                        <th width="170"></th>
                                        <th width="100"></th>
                                        <th width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    ?>
                                    <tr id="wordRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $no;?></td>
                                        <td><?php echo $id;?></td>
                                        <td><div id="wordInfo<?php echo $id;?>"></div>
                                            <a href="#" class="btnEdit" data-id="<?php echo $id;?>">
                                                <span id="word<?php echo $id;?>"><?php echo $name; ?></span>
                                            </a>
                                            <?php if(!empty($description)){?>
                                                <br>
                                                <small id="description<?php echo $id;?>"><?php echo $description;?></small>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <span id="category<?php echo $id;?>">
                                                <?php echo (empty($category_name)) ? '-' : $category_name; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                if($deleted == 1) {
                                                    echo '<span class="badge badge-secondary">DELETED</span>';
                                                }
                                                else
                                                {
                                                    if($status == 1) {
                                                        echo '<span class="badge badge-success spanStatus'.$id.'">ACTIVE</span>';
                                                    }
                                                    else {
                                                        echo '<span class="badge badge-info spanStatus'.$id.'">DRAFT</span>';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>
                                        <?php if(!empty($level)) {?>
                                            <?php if(empty($level_id)) {?>
                                                <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top"
                                                    class="btnAddWord btn btn-success btn-block btn-sm" id="btnAddWord<?php echo $id;?>"
                                                    data-id="<?php echo $id;?>" data-level-id="<?php echo $level;?>">Add to Lv</a>
                                            <?php } else {?>
                                                <?php if($level_id == $level) {?>
                                                    <a href="#" class="btnAddWord btn btn-secondary btn-block btn-sm" id="btnAddWord<?php echo $id;?>"
                                                    data-id="<?php echo $id;?>" data-level-id="<?php echo $level;?>">Remove from Lv</a>
                                                <?php } else {?>
                                                    <a href="#" class="btn btn-secondary disabled btn-block btn-sm" id="btnAddWord<?php echo $id;?>"
                                                    data-id="<?php echo $id;?>" data-level-id="<?php echo $level;?>">Belong to other level</a>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($status == 1) {?>
                                                <a href="" class="btnDraftToggle btn btn-secondary btn-sm" data-id="<?php echo $id;?>">
                                                    <span class="spanStatusBtn<?php echo $id;?>">set draft</span>
                                                </a>
                                            <?php } else {?>
                                                <a href="" class="btnDraftToggle btn btn-secondary btn-sm" data-id="<?php echo $id;?>">
                                                    <span class="spanStatusBtn<?php echo $id;?>">activate</span>
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td><a href="" class="btnDelete btn btn-danger btn-sm" data-id="<?php echo $id;?>">delete</a></td>
                                    </tr>
                                <?php $no++; }?>
                                </tbody>
                            </table>

                            <div style="overflow-x: scroll" class="mt-4">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?></small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?>
                                            href="index.php?level=<?php echo $level;?>&page=<?php echo $i;?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>
                        <?php } else {?>
                            <div class="alert alert-warning">Data is not found. Please update your filter or clear up your filter to see all data.</div>
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
                    <h5 class="modal-title">Update Word</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Category">Category (optional):</label>
                                <select name="Category" id="category" class="input-element form-control categoryList">
                                    <option value="">Select a category</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="wordName">Word:</label>
                                <input class="form-control" id="wordName" type="text" name="wordName" placeholder="Enter word" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="description">Description (optional):</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <input type="hidden" id="word_id"  name="word_id"></input>
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
                    <h5 class="modal-title">Create New Word</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="Category">Category (optional):</label>
                                <select name="Category" id="categoryCreate" class="input-element form-control categoryList">
                                    <option value="">Select a category</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="levelName">Word:</label>
                                <input class="form-control" id="wordNameCreate" type="text" name="wordNameCreate" placeholder="Enter word name" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="description">Description (optional):</label>
                                <textarea class="form-control" id="descriptionCreate" name="descriptionCreate" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <button type="submit" id="submitCreateWord" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <div class="alert alert-success" id="success-alert-create" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Data successfully submitted!</strong>
                                </div>
                                <div class="alert alert-danger" id="error-alert-create" style="display: none">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>Data is already exist! Try another word.</strong>
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
        $(document).ready(function() {

            $('.btnEdit').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                getWord(id);
            });

            $('#submitButton').click(function(e){
                e.preventDefault();
                submitWord();
            });

            $('.btnAdd').click(function(e){
                e.preventDefault();
                $('#createModal').modal('show');
            });

            $('#submitCreateWord').click(function(e){
                e.preventDefault();
                submitCreateWord();
            });

            $('.btnAddWord').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                var level_id = $(this).attr('data-level-id');
                AddWordToLevel(id, level_id);
            });

            $('.btnDelete').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you want to delete this word?')) {
                    deleteWord(id);
                }
            });

            $('.btnDraftToggle').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                draftToggleWord(id);
            });

            populateCategories();
        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/wordsearch/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/wordsearch/api';
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

        function getWord(id) {

            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/word/get-by-id.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response){

                    console.log(response);

                    $('#word_id').val(response.id);
                    $('#wordName').val(response.name);
                    $('#description').val(response.description);
                    $('#category').val(response.category_id);

                    $('#updateModal').modal('show');
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
                }
            });
        }

        function submitWord() {

            var word_id = $('#word_id').val();
            var wordName = $('#wordName').val();
            var description = $('#description').val();
            var category_id = $('#category').val();

            if(word_id != '' && wordName != '') {
                if(!/^[A-Z]+$/.test(wordName)){
                    alert('all answer MUST alphabet and uppercase');
                    return;
                }
                // create json to send
                var data = {
                    'id': word_id,
                    'name': wordName,
                    'description': description,
                    'category_id': (category_id) ? category_id : 0
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/word/update.php';

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
                            $('#word'+word_id).html(wordName);
                            $('#description'+word_id).html(description);
                            $('#category'+word_id).html(response.category_name);

                            $('#updateModal').modal('hide');
                        });
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

        function submitCreateWord() {

            var wordName = $('#wordNameCreate').val();
            var description = $('#descriptionCreate').val();
            var category_id = $('#categoryCreate').val();

            if(wordName != '') {
                if(!/^[A-Z]+$/.test(wordName)){
                    alert('all answer MUST alphabet and uppercase');
                    return;
                }
                
                // create json to send
                var data = {
                    'name': wordName,
                    'description': description,
                    'category_id':  (category_id) ? category_id : 0
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/word/submit-word.php';

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
                            $("#success-alert-create").fadeTo(1000, 500).slideUp(500, function(){
                                $("#success-alert-create").slideUp(500);

                                $('#wordNameCreate').val('');
                                $('#descriptionCreate').val('');

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

        function deleteWord(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/word/delete.php';

                $('#wordInfo'+id).html("deleting....<hr>");

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
                            $('#wordRow'+id).hide();
                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }
            else {
                alert('Word is not found.');
            }

        }

        function AddWordToLevel(id, level_id) {

            if(id != '' && level_id != '') {
                // create json to send
                var data = {
                    'id': id,
                    'level_id': level_id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/word/add-to-level.php';

                $('#btnAddWord'+id).html('<div class="spinner-border spinner-border-sm" role="status"></div>');

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        console.log(response);
                        if(response.toggleStatus == 'add') {
                            $('#btnAddWord'+id).html('Added');
                            setTimeout(function(){
                                $('#btnAddWord'+id).html('Remove from Lv');
                                $('#btnAddWord'+id).addClass('btn-secondary');
                                $('#btnAddWord'+id).removeClass('btn-success');
                            }, 1000);
                        } else {
                            $('#btnAddWord'+id).html('Removed');
                            setTimeout(function(){
                                $('#btnAddWord'+id).html('Add to Lv');
                                $('#btnAddWord'+id).addClass('btn-success');
                                $('#btnAddWord'+id).removeClass('btn-secondary');
                            }, 1000);
                        }
                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }
            else {
                alert('Word cannot be added.');
            }

        }

        function draftToggleWord(id) {

            // create json to send
            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data)

            var ajaxUrl = apiUrl + '/word/toggle-status.php';

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

                    if(response.status == 1) {
                        $('.spanStatusBtn'+id).html('set draft');
                        $('.spanStatus'+id).html('ACTIVE');
                    }
                    if(response.status == 0) {
                        $('.spanStatusBtn'+id).html('activate');
                        $('.spanStatus'+id).html('DRAFT');
                    }

                },
                error: function(xhr,textStatus,error){
                    alert(error);
                }
            });

        }
    </script>
</body>
</html>
