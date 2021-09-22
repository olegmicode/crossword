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
// $searchCountry = isset($_GET['searchCountry']) ? $_GET['searchCountry'] : "";
$searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : "";
// $searchKeyword = isset($_GET['searchKeyword']) ? $_GET['searchKeyword'] : "";
$level = isset($_GET['level']) ? $_GET['level'] : "";

// SET Param
$word->perpage = 10;
$word->offset = ($page>1) ? ($page * $word->perpage) - $word->perpage : 0;

// ($searchCountry != "") && $clue->searchCountry = $searchCountry;
($searchCategory != "") && $word->searchCategory = $searchCategory;
// ($searchKeyword != "") && $clue->searchKeyword = $searchKeyword;

$word->deleted = 1;
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
                                <li class="breadcrumb-item active">Deleted Words</li>
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
                        <h3 class="card-title mt-1">Deleted Words</h3>

                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">

                        <?php if($rowCount > 0){?>
                            <div style="overflow-x: scroll">
                                <small>Page <?php echo $page;?> of <?php echo $pages;?> (<?php echo number_format($word->totalRows);?> Total records filtered)</small>
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
                                        <th style="width: 10px">ID</th>
                                        <th>Word</th>
                                        <th width="170"></th>
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
                                        <td><a href="" class="btnRestoreWord btn btn-default btn-sm" data-id="<?php echo $id;?>">Restore</a></td>
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
                    <h5 class="modal-title">Update Word</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
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

            $('.btnRestoreWord').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                restoreWord(id);
            });
        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/wordsearch/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/wordsearch/api';
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

            if(word_id != '' && wordName != '') {
                // create json to send
                var data = {
                    'id': word_id,
                    'name': wordName,
                    'description': description
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

        function restoreWord(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/word/restore.php';

                $('#wordInfo'+id).html("restoring....<hr>");

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
    </script>
</body>
</html>
