<?php

    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: ../login.php");
    }
    else{
        include_once('config/dbconnect.php');

        
    }

    if (isset($_POST['insert'])) {
        if(function_exists('date_default_timezone_set')) {
            date_default_timezone_set("Asia/Kolkata");
        }
    
        $customer_id = $_POST['customer_id'];
            $date = date("Y-m-d");
            $quantity = 1;


            $item = $_POST['item'];
            $itemList = implode(",", $item);
            $itemExplode = explode(",", $itemList);
            $itemCount = count($itemExplode);
            //$product_id = $_POST['product_id'];
            //$customer_name = $_POST['customer_name'];
            $_SESSION['itemcountVal'] = $itemCount;

            /*for fetching the sold price of the selected product*/
            for ($i=0; $i < $itemCount; $i++) { 
                $sqlPriceSelect = "SELECT sold_per_piece FROM product WHERE product_id = '$itemExplode[$i]'";
                $resultP = mysqli_query($conn, $sqlPriceSelect);
                $rowP = mysqli_fetch_object($resultP);
                $priceArr[] = $rowP->sold_per_piece;
            }

            $priceImplode = implode(",", $priceArr);
            $priceExplode = explode(",", $priceImplode);


            $sqlSales = "INSERT INTO `sales`(`customer_id`, `date`) VALUES ('$customer_id','$date')";
            if (mysqli_query($conn, $sqlSales)) 
            {
                
                $sqlSalesID = "SELECT * FROM sales ORDER BY sales_id DESC LIMIT 1";
                $resultSalesID = mysqli_query($conn,$sqlSalesID);
                $lastID = mysqli_fetch_object($resultSalesID);
                $lastSalesIdInserted = $lastID->sales_id;

                $productSalesQuery = "INSERT INTO `product_sales`(`product_id`, `quantity`,`price`, `sales_id`) VALUES";

                for ($i=0; $i < $itemCount; $i++) { 
                    $productSalesQuery = $productSalesQuery."('".$itemExplode[$i]."','".$quantity."','".$priceExplode[$i]."','".$lastSalesIdInserted."'),";
                }
                $productSalesQuery = substr(trim($productSalesQuery), 0, -1);
                $successmsz = 'Sales Details successfully added.';
            }
            else
            {
                $notice = "ERROR : ".mysqli_error($conn);
            }

            if (mysqli_query($conn, $productSalesQuery)) {
                $successofInsertedProduct = 'Product has been entered for the customer';
                header("refresh:0,url=updateForms/productsalesCheck.php");
            }
            else
            {
                echo "ERROR : ".mysqli_error($conn);
            }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" "IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" "width=device-width, initial-scale=1">
    <meta name="description" "">
    <meta name="author" "">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="../assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="../assets/plugins/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/custom/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="../assets/custom/css/colors/blue.css" id="theme" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
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
                    <a class="navbar-brand" href="index.php">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            
                            <!-- Light Logo icon -->
                            <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         
                         <!-- Light Logo text -->    
                         <img src="../assets/images/logo-light-txt.png" class="light-logo" alt="homepage" /></span> </a>
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
                        <li> <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="section/addSection.php" aria-expanded="false"><i class="mdi mdi-folder-plus"></i><span class="hide-menu">ADD Section</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="section/updateSection.php" aria-expanded="false"><i class="mdi mdi-backup-restore"></i><span class="hide-menu">UPDATE-VIEW Section</span></a>
                        </li>
                        <li> <a class="waves-effect waves-dark" href="section/deleteSection.php" aria-expanded="false"><i class="mdi mdi-delete-empty"></i><span class="hide-menu">DELETE Section</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="config/logout.php" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
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
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page -->
                <!-- ============================================================== -->
                <!-- Row -->
                    <!-- Column -->
                    <div class="col-lg-12 col-xlg-9 col-md-12">
                        <div class="card">
                                    <?php
                                            if(isset($successofInsertedProduct))
                                            {
                                              ?>
                                              <div class="alert alert-success">
                                                <span class="glyphicon glyphicon-info-sign"></span><?php echo $successofInsertedProduct; ?>
                                              </div>
                                              <?php
                                            }
                                            else if (isset($notice))
                                            {
                                            ?>
                                                <div class="alert alert-success">
                                                <span class="glyphicon glyphicon-info-sign"><?php echo $notice; ?></span>
                                              </div>
                                              <?php
                                            }

                                       ?>
                            <form class="form-horizontal form-material" method="post" action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
                            <div class="card-block">
                                    <div class="form-group">
                                        <label style="padding-right: 20px;" class="col-md-12"><b>Product Name</b></label>
                                        <div class="col-md-8">
                                        <select tabindex="1" name="item[]"  multiple class="form-control form-control-line chosen-select">
                                                <?php
                                                    $productQuery = "SELECT product.*, stock.product_id FROM `product`,`stock` 
                                                                    WHERE stock.stock_left>0 AND product.product_id = stock.product_id";
                                                    $productResult = mysqli_query($conn, $productQuery);
                                                        foreach ($productResult as $product) 
                                                        {
                                                            ?>
                                                                <option  value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?> : &nbsp;&nbsp;<?php echo $product['sold_per_piece']; ?></option>
                                                            <?php
                                                        }
                                                ?>
                                                   
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><b>Customer Name</b></label>
                                        <div class="col-md-12">
                                            <select tabindex="1" name="customer_id" class="form-control form-control-line">
                                                    <option style="color: grey;">Select Customer..........</option>
                                                <?php
                                                    $customerQuery = "SELECT * FROM Customer";
                                                    $customerResult = mysqli_query($conn, $customerQuery);
                                                        foreach ($customerResult as $Customer) 
                                                        {
                                                            ?>
                                                                <option  value="<?php echo $Customer['customer_id']; ?>"><?php echo $Customer['customer_name']; ?></option>
                                                            <?php
                                                        }
                                                ?>
                                                   
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="insert" class="btn btn-success">Add to cart</button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                

                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge -->
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
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/js/tether.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/custom/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="../assets/custom/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../assets/custom/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/custom/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="../assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
    <script src="../assets/plugins/d3/d3.min.js"></script>
    <script src="../assets/plugins/c3-master/c3.min.js"></script>
    <!-- Chart JS -->
    <script src="../assets/custom/js/dashboard1.js"></script>
</body>

</html>

<script type="text/javascript">
    $(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})
</script>