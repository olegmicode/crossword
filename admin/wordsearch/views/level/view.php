<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

// instantiate object
include_once '../../objects/level.php';
include_once '../../objects/category.php';
include_once '../../objects/word.php';

$database = new Database();
$db = $database->getConnection();

$level = new Level($db);
$word = new Word($db);
$category = new Category($db);

// GET
$level_id = isset($_GET['level']) ? $_GET['level'] : "";

// SET Param
// ($level_id != "") && $question->level_id = $level_id;
($level_id != "") && $level->id = $level_id;

$level->getById();
if(empty($level->name)) {
    header('Location: index.php');
}
$word->level_id = $level_id;
$statement = $word->getByLevel();
$rowCount = $statement->rowCount();
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
                        <h3 class="card-title mt-1"><?php echo $level->name;?></h3>

                        <div class="card-tools">
                            <a href="https://seniorsdiscountclub.com.au/games/wordsearch/?l=<?php echo $level->permalink;?>" target="_blank" class="btn btn-sm btn-success mr-4">View board</a>    
                            <a href="#" class="btn btn-sm btn-success mr-4 btnAdd">Add new word</a>
                            <a href="../word/index.php?level=<?php echo $level->id;?>" class="btn btn-sm btn-success mr-4">Add word from existing list</a>
                            <a href="index.php" class="btn btn-sm btn-default">Back to list</a>
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <p><strong>Description</strong>: <?php echo $level->description;?></p>
                        <div class="row">
                            <div class="col">
                                <small>Category: </small>
                                <br>
                                <small>Status:
                                <?php
                                    if($level->deleted == 1) { echo "Deleted"; }
                                    else {
                                        if($level->status == 1) { echo "Active"; } else { echo 'Hidden'; }
                                    }
                                ?>
                                </small>
                                <br>
                                <small>Character allowed: <?php echo $level->alphabet; ?></small>
                                <br>
                                <small>Board size: <?php echo $level->size; ?></small>
                                <br>
                                <small>Created: <?php echo date('d m Y h:i:s', strtotime($level->created)); ?></small>
                            </div>
                            <div class="col">
                                <small>Total Words: <?php echo $level->total_words; ?></small>
                                <br>
                                <small>Hint: <?php echo $level->hint; ?></small>
                                <br>
                                <small>Inital score: <?php echo $level->initial_score; ?></small>
                                <br>
                                <small>Every <?php echo $level->time_interval; ?> seconds deduct <?php echo $level->point_deduction; ?> point(s) </small>
                                <br>
                                <small>Difficulty: <?php echo $level->difficulty; ?></small>
                                <br>
                                <small>Modified: <?php echo date('d m Y h:i:s', strtotime($level->modified)); ?></small>
                                <br>
                            </div>
                        </div>
                        <hr>
                        <?php if($rowCount > 0){?>
                            <table class="table table-striped" id="questionData">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">ID</th>
                                        <th>Word</th>
                                        <th width="150"></th>
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
                                            <br>
                                            <small id="description<?php echo $id;?>"><?php echo $description;?></small>
                                            <div class="row">
                                                <div class="col">
                                                    <small>Status:
                                                    <?php
                                                        if($deleted == 1) { echo "Deleted"; }
                                                        else {
                                                            if($status == 1) { echo "Active"; } else { echo 'Hidden'; }
                                                        }
                                                    ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="" class="btnRemoveWord btn btn-danger btn-sm"
                                            data-id="<?php echo $id;?>" data-level-id="<?php echo $level_id;?>">Remove from Lv</a></td>
                                    </tr>

                                <?php $no++; }?>
                                </tbody>
                            </table>


                        <?php } else {?>
                            <div class="alert alert-warning">Data is not found. Please add word to complete the question sets for this level.</div>
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
                                <input type="hidden" name="level_idCreate" id="level_idCreate" value="<?php echo $level->id;?>" />
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

            $('.btnAdd').click(function(e){
                e.preventDefault();
                $("#wordNameCreate").focus();
                $('#createModal').modal('show');
            });

            $('#submitCreateWord').click(function(e){
                e.preventDefault();
                submitCreateWord();
            });

            $('.btnRemoveWord').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                var level_id = $(this).attr('data-level-id');
                removeWord(id, level_id);
            });

        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/wordsearch/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/wordsearch/api';
        }

        function removeWord(id, level_id) {

            if(id != '' && level_id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                console.log(dataJson);

                var ajaxUrl = apiUrl + '/word/remove-from-level.php';

                $('#wordInfo'+id).html("removing....<hr>");

                $.ajax({
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: ajaxUrl,
                    data: dataJson,
                    success: function(response){

                        console.log(response);

                            $('#btnAddWord'+id).html('Removed');
                            setTimeout(function(){
                                $('#wordRow'+id).hide();
                            }, 1000);
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

        function submitCreateWord() {

            var wordName = $('#wordNameCreate').val();
            var description = $('#descriptionCreate').val();
            var level_id = $('#level_idCreate').val();

            if(wordName != '') {
                if(!/^[A-Z]+$/.test(wordName)){
                    alert('all answer MUST alphabet and uppercase');
                    return;
                }

                // create json to send
                var data = {
                    'name': wordName,
                    'description': description,
                    'level_id': level_id
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

    </script>
</body>
</html>
