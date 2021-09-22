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

$level->deleted = 1;
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
                                <li class="breadcrumb-item active">Deleted Levels</li>
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
                        <h3 class="card-title mt-1">Deleted Levels</h3>

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
                                            href="deleted.php?page=<?php echo $i;?>">
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
                                        <th width="10">Width</th>
                                        <th width="10">Height</th>
                                        <th style="width: 10px">ID</th>
                                        <th width="150"></th>
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
                                                    <small>Category: <?php echo $category_name;?></small><br>
                                                    <small>Status:
                                                    <?php
                                                        if($deleted == 1) { echo "Deleted"; }
                                                        else {
                                                            if($status == 1) { echo "Active"; } else { echo 'Hidden'; }
                                                        }
                                                    ?>
                                                    </small>
                                                    <br>
                                                    <small>Created: <?php echo date('d M Y H:i:s', strtotime($created)); ?></small>
                                                </div>
                                                <div class="col">
                                                    <small>Difficulty:
                                                    <?php for($i=1; $i<=$difficulty; $i++){?>
                                                        <i class="fa fa-star" style="color: orange"></i>
                                                    <?php }?>
                                                    </small>
                                                    <br>
                                                    <small>Total Questions: <?php echo $totalQuestion; ?></small>
                                                    <br>
                                                    <small>Last Updated: <?php
                                                        if($modified != '0000-00-00 00:00:00' AND !empty($modified)) { echo date('d M Y H:i:s', strtotime($modified)); } else { echo '-'; } ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span id="width<?php echo $id;?>"><?php echo $width;?></span></td>
                                        <td><span id="height<?php echo $id;?>"><?php echo $height;?></span></td>
                                        <td><?php echo $id;?></td>
                                        <td><a href="view.php?level=<?php echo $id ?>" class="btn btn-success btn-sm btn-block">Manage questions</a></td>
                                        <td><a href="#" class="btnRestore btn btn-default btn-sm" data-id="<?php echo $id;?>">restore</a></td>

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
                                            href="deleted.php?page=<?php echo $i;?>">
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
                                <label for="width">Board width:</label>
                                <input class="form-control" id="width" type="text" name="width" placeholder="Enter the board width" required></input>
                            </div>
                            <div class="col-md-5">
                                <label for="height">Board height:</label>
                                <input class="form-control" id="height" type="text" name="height" placeholder="Enter the board height" required></input>
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

            $('.btnRestore').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                restoreLevel(id);
            });

            populateCategories();
        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/crossword/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/crossword/api';
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
                    $('#levelName').val(response.name);
                    $('#category').val(response.category_id);
                    $('#description').val(response.description);
                    $('#width').val(response.width);
                    $('#height').val(response.height);
                    $('#difficulty').val(response.difficulty);

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
            var width = $('#width').val();
            var height = $('#height').val();
            var difficulty = $('#difficulty').val();

            if(level_id != '' && levelName != '' && description !='' && width != '' && height != '' && difficulty != '') {
                // create json to send
                var data = {
                    'id': level_id,
                    'category_id': category_id,
                    'name': levelName,
                    'description': description,
                    'width': width,
                    'height': height,
                    'difficulty': difficulty
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
                            $('#category'+level_id).html(category_id);
                            $('#description'+level_id).html(description);
                            $('#width'+level_id).html(width);
                            $('#height'+level_id).html(height);

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

        function restoreLevel(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/level/restore.php';

                $('#levelInfo'+id).html("restoring....<hr>");

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
                alert('Level is not found.');
            }

        }
    </script>
</body>
</html>
