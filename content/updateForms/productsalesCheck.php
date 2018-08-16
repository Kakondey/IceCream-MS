<?php

   
    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: Admin_Signin.php");
    }
    else{
        include_once('../config/dbconnect.php');
    }
    error_reporting(0);
    

             /*sales update portion*/
    //echo $lastSalesIdInserted;
    /*get the numbers of last rows inserted*/
    $itemCount = $_SESSION['itemcountVal'];

    $sqlSalesID = "SELECT * FROM sales ORDER BY sales_id DESC LIMIT 1";
    $resultSalesID = mysqli_query($conn,$sqlSalesID);
    $lastID = mysqli_fetch_object($resultSalesID);
    $lastSalesIdInserted = $lastID->sales_id;


    $lastNrowsQuery = "SELECT * FROM product_sales ORDER BY pro_salesID DESC LIMIT ".$itemCount;
    $nrowresult = mysqli_query($conn,$lastNrowsQuery);
    $noOfRows = mysqli_num_rows($nrowresult);
    //echo $noOfRows;
    /*get the name of customer of current sales.*/
    $getCustomerNameQuery = "SELECT customer.customer_name FROM `customer`,`sales` WHERE sales.customer_id = customer.customer_id AND sales.sales_id = ".$lastSalesIdInserted;
    $cusNameResult = mysqli_query($conn,$getCustomerNameQuery);
    $getCName = mysqli_fetch_object($cusNameResult);
    $customerNameValue =  $getCName->customer_name;
   

    if (isset($_POST['update'])) {

        $product_idList = $_POST['product_id'];
        $product_idImplode = implode(",", $product_idList);
        $product_idExplode = explode(",", $product_idImplode);
        //$product_idcount = count($product_idExplode);

        $itemPriceList = $_POST['price'];
        $itemPriceImplode = implode(",", $itemPriceList);
        $itemPriceExplode = explode(",", $itemPriceImplode);

        $salesProdid = $_POST['pro_salesID'];
        $salesProdidList = implode(",", $salesProdid);
        $salesProExplode = explode(",", $salesProdidList);
        $salesProIdCount = count($salesProExplode);

        $quantityItemList = $_POST['itemQuantity'];
        $quantityImplode = implode(",", $quantityItemList);
        $quantityExplode = explode(",", $quantityImplode);
/*
        $productNameList = $_POST['product_name'];
        $productNameImplode = implode(",", $productNameList);
        $productNameExplode = explode(",", $productNameImplode);*/

        /*updating the stock.*/
        for ($i=0; $i < $salesProIdCount; $i++) { 
            $sqlTotStock = "SELECT stock_left, stock_id FROM stock WHERE product_id = '$product_idExplode[$i]'";
            $resultTotSto = mysqli_query($conn, $sqlTotStock);
            $rowS = mysqli_fetch_object($resultTotSto);
            $totStockArr[] = $rowS->stock_left;
            $stockIDArr[] = $rowS->stock_id;

        }

        $totStockImplode = implode(",", $totStockArr);
        $totStockExplode = explode(",", $totStockImplode);
        

        $stockIdImoplode = implode(",", $stockIDArr);
        $stockIDExplode = explode(",", $stockIdImoplode);

       /* for ($i=0 ; $i < $salesProIdCount; $i++) { 
            $updatedStock = $totStockExplode[$i]-$quantityExplode[$i];
                $sqlStockUpdate = "UPDATE stock SET stock_left = '$updatedStock'  WHERE stock_id = '$stockIDExplode[$i]' AND product_id = '$product_idExplode[$i]'";
                mysqli_query($conn, $sqlStockUpdate);
            
        }*/

        
        for ($i=0 ; $i < $salesProIdCount; $i++) { 
            $itemsPrice = 0;
            $updatedStock = 0;

            $updatedStock = $totStockExplode[$i]-$quantityExplode[$i];
            $itemsPrice = $itemPriceExplode[$i]*$quantityExplode[$i];

            $sqlupdate = "UPDATE product_sales SET quantity = '$quantityExplode[$i]',price = '$itemsPrice'  WHERE pro_salesID = '$salesProExplode[$i]' ";
            $sqlStockUpdate = "UPDATE stock SET stock_left = '$updatedStock'  WHERE stock_id = '$stockIDExplode[$i]' AND product_id = '$product_idExplode[$i]'";

            mysqli_query($conn, $sqlStockUpdate);
            mysqli_query($conn, $sqlupdate);
           
        }

         $totalpriceSQL = "SELECT SUM(price) AS totalPrice FROM product_sales WHERE product_sales.sales_id = ".$lastSalesIdInserted;
         $resultTP = mysqli_query($conn,$totalpriceSQL);
         $rowTP = mysqli_fetch_object($resultTP);
         $totalCost = $rowTP->totalPrice;

    }
    else
    {
        $notice = "Error : ".mysqli_error($conn);
    }

    if (isset($_POST['print'])) {

        if(function_exists('date_default_timezone_set')) {
            date_default_timezone_set("Asia/Kolkata");
        }
        
        $_SESSION['customerNameValue'] = $getCName->customer_name;
        $_SESSION['lastSalesId'] = $lastSalesIdInserted;

        $totalpricesSQL = "SELECT SUM(price) AS totalPrice FROM product_sales WHERE product_sales.sales_id = ".$lastSalesIdInserted;
         $resultTPs = mysqli_query($conn,$totalpricesSQL);
         $rowTPs = mysqli_fetch_object($resultTPs);
         $totalCosts = $rowTPs->totalPrice;
        $_SESSION['totalamountValue'] = $totalCosts;
        $totalCusstomerPaid = $_POST['totalPaidMoney'];

        $date = date("Y-m-d");
        
        $_SESSION['totalAmountPaidValue'] = $_POST['totalPaidMoney'];
        $balance = $totalCosts-$totalCusstomerPaid;

        $billSQl = "INSERT INTO bill(sales_id, customer_name, date_curr,  total_cost, total_paid, balance) VALUES ('$lastSalesIdInserted','$customerNameValue','$date','$totalCosts','$totalCusstomerPaid','$balance')";
        mysqli_query($conn,$billSQl);


        header("refresh:0,url=../invoice/billPage.php");

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
                    <a class="navbar-brand" href="../index.php">
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
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-16 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">check for sales details : </h3>
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
                    <div class="col-lg-12">
                        <div class="card">
                            <form class="form-horizontal form-material" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="card-block">
                                <h4 class="card-title">SALES DETAILS</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>PRODUCT NAME</th>
                                                <th>QUANTITY<input class="btn btn-primary btn-xs" value="UPDATE" type="submit" name="update"></th>
                                                <th>PRICE</th>
                                                <th>STOCK LEFT</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            $sql="SELECT stock.stock_left, product_sales.product_id, product_sales.pro_salesID, product.product_name, product_sales.price,product_sales.quantity FROM `product`,`product_sales`,`stock` 
                                                WHERE product_sales.product_id = product.product_id AND stock.product_id = product.product_id AND product_sales.sales_id = ".$lastSalesIdInserted;
                                            $query=mysqli_query($conn,$sql);

                                            if (mysqli_num_rows($query)>0) {
                                                while ($row=mysqli_fetch_object($query)) {
                                                    ?>
                                                    <input type="hidden" name="product_id[]" value="<?php echo $row->product_id; ?>">
                                                    <input type="hidden" name="pro_salesID[]" value="<?php echo $row->pro_salesID; ?>">
                                                    <input type="hidden" name="price[]" value="<?php echo $row->price; ?>">
                                                    <input type="hidden" name="product_name[]" value="<?php $row->product_name; ?>">

                                            <tbody>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $row->product_name; ?></td>
                                                    <td><input type="number" name="itemQuantity[]" value="<?php echo $row->quantity; ?>"></td>
                                                    <td><?php echo $row->price; ?></td>
                                                    <td><?php echo $row->stock_left; ?></td>
                                                </tr>
                                            </tbody>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </table>
                                </div>
                            </div>
                            </div>
                    </div>
                    <div class="col-lg-4 col-md-2">
                        <div class="card">
                            <div class="card-block">
                                <h3 class="m-b-0">NAME : <?php echo $getCName->customer_name; ?></h3>
                                <h3 class="m-b-0">TOTAL PRICE : <?php echo $totalCost; ?></h3>
                                <br><br>
                                <div class="form-group">
                                        <label class="col-md-12"><h3>Total Paid : </h3></label>
                                        <div class="col-md-12">
                                            <input type="number" name="totalPaidMoney" class="form-control form-control-line" ><br><br>
                                            <input class="btn btn-primary btn-mid" value="PRINT" type="submit" name="print">
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
                <!-- End PAge Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
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
<?php 

    
 ?>