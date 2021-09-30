<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/viewOrder.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/viewOrder.php';?>
        <section>
            <div id="mainContent">
                <h1 class="mainHeader">My Orders</h1>
                <table id="myOrders" cellspacing="5px" cellpadding="5px">
                    <tr>
                        <th id="orderID" class="myOrdersTH">Order ID</th>
                        <th id="orderDate" class="myOrdersTH">Placed on</th>
                        <th id="orderItem" class="myOrdersTH">Items</th>
                        <th id="totPrice" class="myOrdersTH">Total</th>
                        <th id="orderstatus" class="myOrdersTH">Status</th>
                        <th id="orderDetails" class="myOrdersTH"></th>
                    </tr>
                    <tr>
                    </tr>
                    <?php orderDetails(); ?>
                </table>
                <br><br>
            </div>
        
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
