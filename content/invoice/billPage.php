<?php 
    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: Admin_Signin.php");
    }
    else{
        include_once('../config/dbconnect.php');
    }
    
    $lastSalesIdInserted =  $_SESSION['lastSalesId'];

    if(function_exists('date_default_timezone_set')) {
            date_default_timezone_set("Asia/Kolkata");
        }

    $sqlBill = "SELECT * FROM bill ORDER BY bill_id DESC LIMIT 1";
    $resultBill = mysqli_query($conn,$sqlBill);
    $lastBillID = mysqli_fetch_object($resultBill);
    $customerName = $lastBillID->customer_name;
    $date = $lastBillID->date_curr;
    $grandTotal = $lastBillID->total_cost;
    $totalPaid = $lastBillID->total_paid;
    $bal = $lastBillID->balance;


    header("refresh:0; url=../index.php");

 ?>
<!DOCTYPE html>
<html>
<head>
    <title>INVOICE</title>
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bill.css">
</head>
<body>
    <div class="container">
    <div class="row heading">
        <div class="col-xs-12">
            <div class="invoice-title">
                <center><h2>CREAM</h2><h3 class="pull-right">-Valley</h3></center>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                    <strong>Billed To:</strong><br>
                        <?php echo $customerName; ?><br>
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>Payment Method:</strong><br>
                        By CASH<br>
                        Ajayprasad7520@email.com
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Order Date:</strong><br>
                        <?php echo $date;  ?><br><br>
                    </address>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Item</strong></td>
                                    <td class="text-center"><strong>Price</strong></td>
                                    <td class="text-center"><strong>Quantity</strong></td>
                                    <td class="text-right"><strong>Totals</strong></td>
                                </tr>
                            </thead>
                                <?php
                                    $sql="SELECT product.sold_per_piece, product_sales.product_id, product_sales.pro_salesID, product.product_name, product_sales.price,product_sales.quantity FROM `product`,`product_sales` 
                                        WHERE product_sales.product_id = product.product_id AND product_sales.sales_id = ".$lastSalesIdInserted;
                                            $query=mysqli_query($conn,$sql);

                                    if (mysqli_num_rows($query)>0) {
                                        while ($row=mysqli_fetch_object($query))
                                        {
                                ?>
                                <tbody>
                                    <tr>
                                        <td><?php echo $row->product_name; ?></td>
                                        <td class="text-center"><?php echo $row->sold_per_piece; ?></td>
                                        <td class="text-center"><?php echo $row->quantity; ?></td>
                                        <td class="text-right"><?php echo $row->price; ?></td>
                                    </tr>
                                    <?php 

                                            }
                                        }
                                     ?>
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                        <td class="thick-line text-right"><?php echo $grandTotal; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Total Paid</strong></td>
                                        <td class="no-line text-right"><?php echo $totalPaid; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center"><strong>Balance</strong></td>
                                        <td class="no-line text-right"><?php echo $bal; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
