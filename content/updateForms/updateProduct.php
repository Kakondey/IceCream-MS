<?php
    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: Admin_Signin.php");
    }
    else{
        include_once('../config/dbconnect.php');
    }

        $product_id = "";
        $product_type = "";
        $product_name = "";
        $cost_per_piece = "";
        $sold_per_piece = "";

        $stock_id = "";
        $stock_left = "";
        $total_stock = "";


    if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $product_type = $_POST['product_type'];
        $product_name = $_POST['product_name'];
        $cost_per_piece = $_POST['cost_per_piece'];
        $sold_per_piece = $_POST['sold_per_piece'];

        $stock_left = $_POST['stock_left'];
        $total_stock = $_POST['total_stock'];

         $sqluP = "UPDATE product SET product_type='$_POST[product_type]',product_name='$_POST[product_name]',cost_per_piece='$_POST[cost_per_piece]'
                  ,sold_per_piece='$_POST[sold_per_piece]'
                  WHERE product_id='$_POST[product_id]'";

        $sqluS = "UPDATE stock SET stock_left='$_POST[stock_left]',total_stock='$_POST[total_stock]'
                  WHERE product_id='$product_id'";

            mysqli_query($conn, $sqluP);
        if (mysqli_query($conn, $sqluS)) 
        {
            $successmsz = 'Product details successfully Updated';
            header("refresh:0; url=../tables/viewProduct.php");
        }
        else
        {
            $errormsz = mysqli_error($conn);
        }

    }

    if (isset($_GET['edit'])) {
        $sqlPro = "SELECT * FROM product WHERE product_id='{$_GET['product_id']}'";
        $eQuery = mysqli_query($conn,$sqlPro);
        $row = mysqli_fetch_object($eQuery);

        $sqlSto = "SELECT * FROM stock WHERE product_id = '{$_GET['product_id']}'";
        $QueryS = mysqli_query($conn,$sqlSto);

        $rowS = mysqli_fetch_object($QueryS);

        $product_id = $row->product_id;
        $product_type = $row->product_type;
        $product_name = $row->product_name;
        $cost_per_piece = $row->cost_per_piece;
        $sold_per_piece = $row->sold_per_piece;
        $stock_left = $rowS->stock_left;
        $total_stock = $rowS->total_stock;

    }

    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
    <title>Add-Product</title>
    <!-- Bootstrap Core CSS -->
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../assets/custom/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="../../assets/custom/css/colors/blue.css" id="theme" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            
                            <!-- Light Logo icon -->
                            <img src="../../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         
                         <!-- Light Logo text -->    
                         <img src="../../assets/images/logo-light-txt.png" class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['Admin_name']; ?></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li> <a class="waves-effect waves-dark" href="../index.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="../section/addSection.php" aria-expanded="false"><i class="mdi mdi-folder-plus"></i><span class="hide-menu">ADD Section</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="../section/updateSection.php" aria-expanded="false"><i class="mdi mdi-backup-restore"></i><span class="hide-menu">UPDATE-VIEW Section</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="../section/deleteSection.php" aria-expanded="false"><i class="mdi mdi-delete-empty"></i><span class="hide-menu">DELETE Section</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-12 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Update Customer</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="../section/updateSection.php">UPDATE Section</a></li>
                            <li class="breadcrumb-item"><a href="../tables/viewProduct.php">view product</a></li>
                            <li class="breadcrumb-item active">update product</li>
                        </ol>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-12 col-xlg-9 col-md-12">
                        <div class="card">
                                        <?php
                                            if(isset($successmsz))
                                            {
                                              ?>
                                              <div class="alert alert-success">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <a href="#"><?php echo $successmsz; ?><a/> 
                                              </div>
                                              <?php
                                            }
                                       ?>
                            <div class="card-block">
                                <form class="form-horizontal form-material" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" class="form-control form-control-line">

                                    <div class="form-group">
                                        <label for="name" class="col-md-12">Product Type</label>
                                        <div class="col-md-12">
                                            <input type="text" name="product_type" value="<?php echo $product_type; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Product Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="product_name" value="<?php echo $product_name; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Cost per piece</label>
                                        <div class="col-md-12">
                                            <input type="number" name="cost_per_piece" value="<?php echo $cost_per_piece; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Sold per piece</label>
                                        <div class="col-md-12">
                                            <input type="number" name="sold_per_piece" value="<?php echo $sold_per_piece; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Stock left</label>
                                        <div class="col-md-12">
                                            <input type="number" name="stock_left" value="<?php echo $stock_left; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Total Stock</label>
                                        <div class="col-md-12">
                                            <input type="number" name="total_stock" value="<?php echo $total_stock; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="update" class="btn btn-success">Update Data</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2018 Designed By Kakon Dey | kakondey701@gmail.com | 8761869428 </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/plugins/bootstrap/js/tether.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../../assets/custom/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="../../assets/custom/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../assets/custom/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../../assets/custom/js/custom.min.js"></script>
</body>

</html>
