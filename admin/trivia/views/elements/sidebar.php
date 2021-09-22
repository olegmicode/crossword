<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" style="background: #fff">
        <span class="brand-text font-weight-light">
            <img src="../../../assets/img/logo.svg" />
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Questions <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../question/index.php" class="nav-link">
                                <p>All Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../question/random.php" class="nav-link">
                                <p>Random Questions</p>
                            </a>
                        </li>
                        <?php /* <li class="nav-item">
                            <a href="../question/incomplete.php" class="nav-link">
                                <p>Incomplete Questions</p>
                            </a>
                        </li> */ ?>
                        <li class="nav-item">
                            <a href="../question/deleted.php" class="nav-link">
                                <p>Deleted Questions</p>
                            </a>
                        </li>
                        <?php /* <li class="nav-item">
                            <a href="../question/maintenance.php" class="nav-link">
                                <p>Maintenance</p>
                            </a>
                        </li> */ ?>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Categories <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../category/index.php" class="nav-link">
                                <p>All Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Statistics <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../question/statistic.php" class="nav-link">
                                <p>Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../score/index.php" class="nav-link">
                                <p>Scores</p>
                            </a>
                        </li>
                    </ul>


                </li>

                <li class="nav-item">
                    <a href="../../../logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>