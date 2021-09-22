<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';

// instantiate object
include_once '../../objects/category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$category->deleted = 1;
$statement = $category->getCategories();
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
                            <h1>Categories</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Deleted Categories</li>
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
                        <h3 class="card-title mt-1">Deleted Categories</h3>

                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <?php if($rowCount > 0){?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">#ID</th>
                                        <th>Category</th>
                                        <th style="width: 150px">Total Levels</th>
                                        <th style="width: 150px">Total Words</th>
                                        <th style="width: 150px"></th>
                                        <th style="width: 10px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $data = array();
                                $data["records"] = array();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    ?>
                                    <tr id="categoryRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                        <td><?php echo $no;?></td>
                                        <td><?php echo $id;?></td>
                                        <td><div id="categoryInfo<?php echo $id;?>"></div>
                                            <a href="#" class="btnEdit" data-id="<?php echo $id;?>">
                                                <span id="category<?php echo $id;?>"><?php echo $name; ?></span>
                                            </a>
                                        </td>
                                        <td><?php echo number_format($totalLevels);?></td>
                                        <td><?php echo number_format($totalWords);?></td>
                                        <td><a href="../word/index.php?searchCategory=<?php echo $id;?>"
                                                class="btn btn-sm btn-outline-secondary">show words</a></td>
                                        <td><a href="#" class="btnRestore btn btn-default btn-sm" data-id="<?php echo $id;?>">restore</a></td>
                                    </tr>
                                <?php $no++; }?>
                                </tbody>
                            </table>
                        <?php } else {?>
                            <div class="alert alert-warning">Data is not found.</div>
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

    <?php include('../elements/footer-js.php'); ?>

    <div class="modal fade" tabindex="-1" id="updateModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <label for="width">Category Name:</label>
                                <input class="form-control" id="categoryName" type="text" name="categoryName" placeholder="Enter the category name" required></input>
                            </div>
                        </div>

                        <div class="form-group row mt-4 justify-content-md-center">
                            <div class="col-md-10">
                                <input type="hidden" name="category_id" id="category_id" />
                                <button type="submit" id="submitButton" class="btn btn-success">Save Changes</button>
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
                                    <strong>Cannot update data, its already exist in database.</strong>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.btnEdit').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                getCategory(id);
            });

            $('#submitButton').click(function(e){
                e.preventDefault();
                submitCategory();
            });

            $('.btnRestore').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                restoreCategory(id);
            });

        });

        var apiUrl = '';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            var apiUrl = 'http://localhost/ylc-games/wordsearch/api';
        } else {
            var apiUrl = 'https://seniorsdiscountclub.com.au/games/wordsearch/api';
        }

        function getCategory(id) {

            var data = {
                'id': id
            }
            var dataJson = JSON.stringify(data);

            var ajaxUrl = apiUrl + '/category/get-by-id.php';

            $.ajax({
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: ajaxUrl,
                data: dataJson,
                success: function(response){

                    console.log(response);

                    $('#category_id').val(response.id);
                    $('#categoryName').val(response.name);

                    $('#updateModal').modal('show');
                },
                error: function(xhr,textStatus,error){
                    console.log(error);
                }
            });
        }

        function submitCategory() {

            var category_id = $('#category_id').val();
            var categoryName = $('#categoryName').val();

            if(category_id != '' && categoryName) {
                // create json to send
                var data = {
                    'id': category_id,
                    'name': categoryName
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/category/update.php';

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

                                // update the row
                                $('#category'+category_id).html(categoryName);

                                $('#updateModal').modal('hide');
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

        function restoreCategory(id) {
            if(id != '') {
                // create json to send
                var data = {
                    'id': id
                }
                var dataJson = JSON.stringify(data)

                var ajaxUrl = apiUrl + '/category/restore.php';

                $('#categoryInfo'+id).html("restoring....<hr>");

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
                            $('#categoryRow'+id).hide();
                        }, 1500);

                    },
                    error: function(xhr,textStatus,error){
                        console.log(error);
                    }
                });
            }
            else {
                alert('Category is not found.');
            }

        }
    </script>

</body>
</html>
