<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <li class="nav-item">
        <span style="color: #555; font-size: 14px;">
        <i class="nav-icon fas fa-user"></i>&nbsp; 
        <?= $_SESSION['user_email'] ?></span>
      </li>
    </ul>
</nav>
<!-- /.navbar -->