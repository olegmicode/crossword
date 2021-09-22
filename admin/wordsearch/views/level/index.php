<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';
  
// instantiate object
include_once '../../objects/level.php';
  
$database = new Database();
$db = $database->getConnection();
  
$level = new Level($db);

// GET
$page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
$searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : "";
$searchKeyword = isset($_GET['searchKeyword']) ? $_GET['searchKeyword'] : "";

// SET Param
$level->perpage = 10;
$level->offset = ($page>1) ? ($page * $level->perpage) - $level->perpage : 0;

($searchCategory != "") && $level->searchCategory = $searchCategory;
($searchKeyword != "") && $level->searchKeyword = $searchKeyword;

$level->getAllLevelsCount();

$statement = $level->getLevels();
$rowCount = $statement->rowCount();
$pages = ceil($level->totalRows / $level->perpage);

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
                                <li class="breadcrumb-item active">All Levels</li>
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
                        <h3 class="card-title mt-1">All Levels</h3>

                        <div class="card-tools">
                            <a href="../level/create-quick-level.php" class="btn btn-sm btn-success">Create Quick Level</a>
                            <a href="" id="btnAdd" class="btn btn-sm btn-success btnAdd">Add Level</a>
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
                                <div class="col-md-6">
                                    <label for="searchKeyword">Keyword:</label> 
                                    <input class="form-control" id="searchKeyword" type="text" name="searchKeyword" 
                                        placeholder="Search level"
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
                                <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo number_format($level->totalRows);?> Total records filtered)</small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?> 
                                            href="index.php?page=<?php echo $i;?>">
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
                                        <th>Level</th>
                                        <th style="width: 10px">ID</th>
                                        <th width="140"></th>
                                        <th width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $clues_arr = array();
                                $clues_arr["records"] = array();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    ?>
                                    <tr id="levelRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $no;?></td>
                                        <td><div id="levelInfo<?php echo $id;?>"></div>
                                            <a href="#" class="btnEdit" data-id="<?php echo $id;?>">
                                                <span id="level<?php echo $id;?>"><?php echo $name; ?></span>
                                            </a>
                                            <br>
                                            <small id="description<?php echo $id;?>"><?php echo $description;?></small>
                                            <div class="row">
                                                <div class="col">
                                                    <small>Category: 
                                                        <span id="category<?php echo $id;?>">
                                                            <?php echo (empty($category_name)) ? '-' : $category_name; ?>
                                                        </span>
                                                    </small>
                                                    <br>
                                                    <small>Status: 
                                                    <?php 
                                                        if($deleted == 1) { echo "Deleted"; } 
                                                        else { 
                                                            if($status == 1) { echo "Active"; } else { echo 'Hidden'; } 
                                                        } 
                                                    ?>
                                                    </small>
                                                    <br>
                                                    <small>Created: <?php echo date('d m Y h:i:s', strtotime($created));?></small>
                                                </div>
                                                <div class="col">
                                                    <small>Total Words: <?php echo $total_words; ?></small>
                                                    <br>
                                                    <small>Difficulty: 
                                                    <?php for($i=1; $i<=$difficulty; $i++){?>
                                                        <i class="fa fa-star" style="color: orange"></i>
                                                    <?php }?> 
                                                    </small>
                                                    <br>
                                                    <small>Last Updated: <?php 
                                                        if($modified != '0000-00-00 00:00:00' AND !empty($modified)) { echo date('d M Y H:i:s', strtotime($modified)); } else { echo '-'; } ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $id;?></td>
                                        <td><a href="view.php?level=<?php echo $id ?>" class="btn btn-success btn-sm">Manage words</a></td>
                                        <td><a href="" class="btnDelete btn btn-danger btn-sm" data-id="<?php echo $id;?>">delete</a></td>
                                    </tr>  
                                <?php $no++; }?>
                                </tbody>
                            </table>

                            <div style="overflow-x: scroll" class="mt-4">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo number_format($level->totalRows);?> Total records filtered)</small>
                                <div class="clear mt-2"></div>
                                <ul class="pagination pagination-sm">
                                <?php for($i=1; $i<=$pages; $i++){?>
                                    <li class="page-item" style="clear: both !important">
                                        <a class="page-link" <?php if($i == $page) {?>style="background: #ccc;"<?php }?> 
                                            href="index.php?page=<?php echo $i;?>">
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
                    <h5 class="modal-title">Update Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-7">
                                <label for="Category">Category:</label> 
                                <select name="Category" id="category" class="input-element form-control categoryList" required>
                                    <option value="">Select a category</option>                                    
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="width">Difficulty (1-5):</label> 
                                <select name="difficulty" id="difficulty" class="input-element form-control" required>
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
                                <input class="form-control" id="levelName" type="text" name="levelName" placeholder="Enter level name" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="description">Description:</label> 
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-5">
                                <label for="width">Alphabet:</label> 
                                <input class="form-control" id="alphabet" type="text" name="alphabet" placeholder="Enter the alphabet that can be used in game" required></input>
                            </div>
                            <div class="col-md-5">
                                <label for="width">Initial Score:</label> 
                                <input class="form-control" id="initial_score" type="text" name="initial_score" placeholder="How many score to put in start" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-5">
                                <label for="width">Board Size:</label> 
                                <input class="form-control" id="size" type="text" name="size" placeholder="Enter the board size" required></input>
                            </div>
                            <div class="col-md-5">
                                <label for="height">Hint:</label> 
                                <input class="form-control" id="hint" type="text" name="hint" placeholder="How many hint available" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-5">
                                <label for="width">Time Interval:</label> 
                                <input class="form-control" id="time_interval" type="text" name="time_interval" placeholder="Time interval used to deduct points" required></input>
                            </div>
                            <div class="col-md-5">
                                <label for="height">Point Deduction:</label> 
                                <input class="form-control" id="point_deduction" type="text" name="point_deduction" placeholder="How many point will be deducted in time interval" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                            <label for="permalink">Permalink:</label> 
                                <input class="form-control" id="permalink" type="text" name="permalink" placeholder="Enter permalink" required></input>
                            </div>
                        </div>
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <input type="hidden" id="level_id"  name="level_id"></input>
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
                    <h5 class="modal-title">Create New Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                        
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <button type="submit" id="submitCreateLevel" class="btn btn-success">Save Changes</button>
                                <button type="button" id="setDefaultValue" class="btn btn-default ml-4">Set default value</button>
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
                                    <strong>Data is already exist.</strong>
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
                getLevel(id);
            });

            $('#submitButton').click(function(e){
                e.preventDefault();
                submitLevel();
            });

            $('.btnAdd').click(function(e){
                e.preventDefault();
                $('#createModal').modal('show');
            });

            $('#submitCreateLevel').click(function(e){
                e.preventDefault();
                submitCreateLevel();
            });

            $('.btnDelete').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                if (confirm('Are you sure you want to delete this level?')) {
                    deleteLevel(id);
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
        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/yourlifechoices-games/games/wordsearch/api';
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

        function getLevel(id) {

            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/level/get-by-id.php';

            $.ajax({ 
                type: 'POST', 
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl, 
                data: dataJson,
                success: function(response){  

                    console.log(response);
                    
                    $('#level_id').val(response.id);
                    $('#category').val(response.category_id);
                    $('#levelName').val(response.name);
                    $('#description').val(response.description);
                    $('#difficulty').val(response.difficulty);
                    $('#alphabet').val(response.alphabet);
                    $('#initial_score').val(response.initial_score);
                    $('#size').val(response.size);
                    $('#hint').val(response.hint);
                    $('#time_interval').val(response.time_interval);
                    $('#point_deduction').val(response.point_deduction);
                    $('#permalink').val(response.permalink);
                    $('#updateModal').modal('show');
                }, 
                error: function(xhr,textStatus,error){ 
                    console.log(error); 
                } 
            });
        }

        function submitLevel() {
            
            var level_id = $('#level_id').val();
            var category_id = $('#category').val();
            var levelName = $('#levelName').val();
            var description = $('#description').val();
            var alphabet = $('#alphabet').val();
            var size = $('#size').val();
            var hint = $('#hint').val();
            var time_interval = $('#time_interval').val();
            var point_deduction = $('#point_deduction').val();
            var initial_score = $('#initial_score').val();
            var difficulty = $('#difficulty').val();
            var permalink = $('#permalink').val();
            
            if(level_id != '' && levelName != '' && description !='' && permalink != '') {
                // create json to send
                var data = {
                    'id': level_id,
                    'category_id': category_id,
                    'name': levelName,
                    'description': description,
                    'alphabet': alphabet,
                    'size': size,
                    'hint': hint,
                    'time_interval': time_interval,
                    'point_deduction': point_deduction,
                    'initial_score': initial_score,
                    'difficulty': difficulty,
                    'permalink': permalink,
                }
                var dataJson = JSON.stringify(data)
                
                var ajaxUrl = apiUrl + '/level/update.php';

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
                            $('#level'+level_id).html(levelName);
                            $('#category'+level_id).html(response.category_name);
                            $('#description'+level_id).html(description);
                            $('#alphabet'+level_id).html(alphabet);
                            $('#size'+level_id).html(size);
                            $('#hint'+level_id).html(hint);
                            $('#time_interval'+level_id).html(time_interval);
                            $('#point_deduction'+level_id).html(point_deduction);
                            $('#initial_score'+level_id).html(initial_score);
                            $('#difficulty'+level_id).html(difficulty);
                            
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

        function submitCreateLevel() {

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
                    'difficulty': difficulty
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

        function deleteLevel(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)
                
                var ajaxUrl = apiUrl + '/level/delete.php';
                
                $('#levelInfo'+id).html("deleting....<hr>");

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
                            $('#levelRow'+id).hide();
                        }, 1500);
                        
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
    </script>
</body>
</html>
