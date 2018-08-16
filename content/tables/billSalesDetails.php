<?php 
    session_start();
    if (!isset($_SESSION['Admin_name'])) {
        header("location: Admin_Signin.php");
    }
    else{
        include_once('../config/dbconnect.php');
    }
    

if (isset($_GET['view'])) {

    $sql="SELECT bill.total_paid,bill.total_cost,bill.balance, bill.date_curr, bill.customer_name, product.sold_per_piece, product_sales.product_id, product_sales.pro_salesID, product.product_name, product_sales.price,product_sales.quantity FROM `bill`,`product`,`product_sales` WHERE bill.bill_id='{$_GET['bill_id']}' AND product_sales.product_id = product.product_id AND product_sales.sales_id ='{$_GET['sales_id']}'";
        $query=mysqli_query($conn,$sql);

 ?>
<!DOCTYPE html>
<html>
<head>
    <title>INVOICE</title>
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../invoice/bill.css">
</head>
<body>
    <div class="container">
    <div class="row heading">
        <div class="col-xs-12">
            <div class="invoice-title">
                <center><h2>CREAM</h2><h3 class="pull-right">-Valley</h3></center>
            </div>
            <hr>
        </div>
    </div>
    <div class="float-right"><a href="viewBill.php"><button type="button" class="btn btn-outline-primary">BACK</button></a></div>
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
                                    

                                    if (mysqli_num_rows($query)>0) {
                                        while ($row=mysqli_fetch_object($query))
                                        {

                                            $Cname = $row->customer_name;
                                            $date = $row->date_curr;
                                            $grandTotal = $row->total_cost;
                                            $totalPaid = $row->total_paid;
                                            $bal = $row->balance;
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
        <div class="row heading">
        <div class="col-xs-12">
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                    <strong>Billed To:</strong><br>
                        <?php echo $Cname; ?><br>
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
    </div>
</body>
</html>
<?php } ?>