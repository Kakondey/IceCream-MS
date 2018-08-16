<?php

    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: Admin_Signin.php");
    }
    else{
        include_once('../config/dbconnect.php');
    }


        $product_type = "";
        $product_name = "";
        $cost_per_piece = "";
        $sold_per_piece = "";

        $last_row = "";
        $stock_left = "";
        $total_stock = "";

    if (isset($_POST['insert'])) {
        $product_type = $_POST['product_type'];
        $product_name = $_POST['product_name'];
        $cost_per_piece = $_POST['cost_per_piece'];
        $sold_per_piece = $_POST['sold_per_piece'];
    
        $stock_left = $_POST['stock_left'];
        $total_stock = $_POST['total_stock'];
        
            $sqlProduct = "INSERT INTO product(product_type, product_name, cost_per_piece,  sold_per_piece) VALUES ('$product_type','$product_name','$cost_per_piece','$sold_per_piece')";   

            mysqli_query($conn, $sqlProduct);

            $sqlProID = "SELECT * FROM product ORDER BY product_id DESC LIMIT 1";
            $resultProID = mysqli_query($conn,$sqlProID);
            $last_row = mysqli_fetch_object($resultProID);
            $last_ProID = $last_row->product_id;

            $sqlStock = "INSERT INTO stock(product_id, stock_left, total_stock) VALUES ('$last_ProID','$stock_left','$total_stock')";  


            if (mysqli_query($conn, $sqlStock)) 
            {
                $successmsz = 'New Product Details successfully inserted. ';
                header("refresh:1; url=addProduct.php");
            }
            else
            {
                $errormsz = mysqli_error($conn);
            }    
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
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Add New Product</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="../section/addSection.php">ADD Section</a></li>
                            <li class="breadcrumb-item active">Add new Product</li>
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
                                                <a href="Add_new_employee.php"><?php echo $successmsz; ?><a/> 
                                              </div>
                                              <?php
                                            }
                                       ?>
                            <div class="card-block">
                                <form class="form-horizontal form-material" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                                    <div class="form-group">
                                        <label for="example-email" class="col-md-12">Product Type</label>
                                        <div class="col-md-12">
                                            <input type="text" name="product_type" class="form-control form-control-line" name="example-email" id="example-email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Product Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name="product_name" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Cost Per Piece</label>
                                        <div class="col-md-12">
                                            <input type="number" name="cost_per_piece" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Sold Per Piece</label>
                                        <div class="col-md-12">
                                            <input type="number" name="sold_per_piece" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Stock left</label>
                                        <div class="col-md-12">
                                            <input type="number" name="stock_left" id="stock_left" value="password" class="form-control form-control-line"
                                            onkeyup="populateSecondTextBox();">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Total stock</label>
                                        <div class="col-md-12">
                                            <input type="number" name="total_stock" id="total_stock" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="insert" class="btn btn-success">Insert Data</button>
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
<script type="text/javascript">
    function populateSecondTextBox() {
    document.getElementById('total_stock').value = document.getElementById('stock_left').value;
    }
</script>