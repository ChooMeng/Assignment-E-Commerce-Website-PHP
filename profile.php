<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
<head>
    <?php include 'Function/default.php';?>
    <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
    <link href="CSS/profile.css" rel="stylesheet" type="text/css"/>
    <script src="JavaScript/default.js" type="text/javascript"></script>
    <script src="JavaScript/profile.js" type="text/javascript"></script>
</head>
<body>
    <?php include 'header.php'; include 'Function/profile.php';?>
    <section id="content">
        <?php include 'topNavBar.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <div id="mainContent">
            <!--The user can view their profile details at here-->
            <h1 class="mainHeader">View My Profile</h1>
            <table id="profileDetails" cellspacing="20px">
                <tr>
                    <td valign="top">
                        <h2>Personal Profile</h2>
                        <!--if the customer is a female then display: block; this
                        <span id="female"><i class="fas fa-venus"></i></span>
                        -->
                        <?php profile(); ?>
                    </td>
                    <td valign="top">
                        <h2>Default Address</h2>
                        <p>
                            <?php displayAddress(); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <br>
            <div class="orders">
                <!--This list will show the item that user has ordered recently-->
                <h2 id="myOrdersH1">My Orders</h2>
                <table id="myOrders" cellspacing="5px" cellpadding="5px">
                    <tr>
                        <th id="orderID" class="myOrdersTH">Order ID</th>
                        <th id="orderDate" class="myOrdersTH">Placed on</th>
                        <th id="orderItem" class="myOrdersTH">Items</th>
                        <th id="totPrice" class="myOrdersTH">Total</th>
                        <th id="orderStatus" class="myOrdersTH">Status</th>
                    </tr>
                    <?php orderDetails();?>
                </table>
                <br><br>
                <div id="orderLink"><a href="viewOrder.php">View more order details</a></div>
            </div>
        </div>
        <br><br><br><br><br>
    </section>
    <?php include 'footer.php';?>
</body>
</html>
