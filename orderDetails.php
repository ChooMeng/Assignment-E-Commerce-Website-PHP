<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/orderDetails.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/orderDetails.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <?php include 'Function/orderDetails.php';?>
        <section>
            <!--show this when the the user click on the cancel order button-->
            <div id="cancelOrderBlock">
                <div id="cancelOrderContent">
                    <div><h1>Cancel this order??</h1></div>
                    <div id="cancelOrderButton">
                        <!--Click this back to main page and delete this row from database-->
                        <button type="button" class="cancelOrderYes" onclick="removeOrder('<?php echo $_GET['orderId'];?>')">Yes</button>
                        <button type="button" class="cancelOrderNo" onclick="closeBox()">No</button>
                    </div>
                </div>
            </div>

            <div id="mainContent">
                <button onclick="location.href='viewOrder.php'" id="back">Back to order list</button>
                <h1 class="mainHeader">Order Details</h1>
                <div id="orderdetail">
                    <!--Display a table of order details include the total price-->
                    <?php displayOrderDetail();?>
                </div>

                <hr>
                <!--Show products that customer have ordered-->
                <div id="itemDetails">
                    <table class="tableSect">
                        <tr id="tableHeader">
                            <th></th>
                            <th>Details</th>
                            <th>Price per unit</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                        <?php displayOrderProducts();?>
                    </table>
                </div>
                <hr>
                <div>
                    <table class="tableSect">
                        <tr>
                            <td valign="top" id="shippingAddress">
                                <p><h2><strong>Shipping Address</strong></h2></p>
                                <p><?php displayAddress();?></p>
                            </td>
                            <td valign="top">
                                <table id="totSummary">
                                    <tr colspan="2"><td><h2>Total Summary</h2></td></tr>
                                    <?php displaySummary();?>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--Cancel order button-->
                <div>
                    <form action="orderDetails.php" method="POST" style="cursor:pointer;">
                        <?php displayCancelOrder();?>
                    </form>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
