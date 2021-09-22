<?php
require_once '../../../auth.php';
// get database connection
include_once '../../config/database.php';
  
// instantiate object
include_once '../../objects/category.php';
include_once '../../objects/question.php';
  
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

// // GET
// $page = isset($_GET['page']) ? (int)$_GET["page"] : 1;
// $searchCountry = isset($_GET['searchCountry']) ? $_GET['searchCountry'] : "";
// $searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : "";
// $searchKeyword = isset($_GET['searchKeyword']) ? $_GET['searchKeyword'] : "";

// // SET Param
// $question->perpage = 150;
// $question->offset = ($page>1) ? ($page * $page) - $page : 0;

// ($searchCountry != "") && $question->searchCountry = $searchCountry;
// ($searchCategory != "") && $question->searchCategory = $searchCategory;
// ($searchKeyword != "") && $question->searchKeyword = $searchKeyword;

// $question->getAllQuestionsCount();

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
                                <li class="breadcrumb-item active">All Categories</li>
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
                        <h3 class="card-title">All Categories</h3>

                        <div class="card-tools">
                            
                        </div>
                    </div>
                    <div class="card-body" style="overflow: hidden">
                        <?php if($rowCount > 0){?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Category</th>
                                        <th style="width: 150px">Total Questions</th>
                                        <th style="width: 150px">Starred</th>
                                        <th style="width: 150px"></th>
                                        <th style="width: 10px">#ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $data = array();
                                $data["records"] = array();
                                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    extract($row);
                                    $questionStarred = new Question($db);
                                    $questionStarred->category_id = $id;
                                    $questionStarred->getCountStarredQuestion();

                                    if($id != 10) {
                                    ?>
                                        <tr id="categoryRow<?php echo $id; ?>" data-in="<?php echo $id; ?>">
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $name;?></td>
                                            <td><?php echo number_format($totalQuestion);?></td>
                                            <td><?php echo number_format($questionStarred->totalStarred);?></td>
                                            <td><a href="../question/index.php?searchCategory=<?php echo $id;?>" 
                                                    class="btn btn-sm btn-outline-secondary">show questions</a></td>
                                            <td><?php echo $id;?></td>
                                        </tr>  
                                    <?php }?>

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
    
</body>
</html>
