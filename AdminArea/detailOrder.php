<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/detailsOrder.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include 'header.php';include 'Function/orderDetails.php';?>
        <section>
            <h1>Order Details</h1>
            <div id="details">
                <?php details(); ?>
            </div>
            <h2 style='text-align: center'>Products</h2>
                <?php orderProduct(); ?>
            <br>
            <div class="buttonMenu">
                <button id='modify' onclick='location.href="modifyOrder.php?orderId=<?php echo $orderID;?>"'>Modify</button>
                <button id='back' onclick='location.href ="orders.php"'>Back</button>
            </div>
            <br/>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
