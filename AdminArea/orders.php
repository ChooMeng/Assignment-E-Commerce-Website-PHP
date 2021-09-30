<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/orders.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/orders.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php';include 'Function/orderList.php';?>
        <!-- Box for update the order status -->
        <div id='orderBox'> 
            <div class='contents'>
                <div class='header'>
                    Change status for an order.
                </div>
                <div class='body'>
                    <form action="orders.php" method="POST" style="text-align:center" onsubmit="return updateStatus();">
                        <label for="status">Status: </label><br/>
                        <select name="status" id="status" required>
                            <?php statusList();?>
                        </select>
                        <br/>
                        <br/>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button"class='no' onclick='closeOrderBox(),addNotification("yellow","<b>Cancelled</b>! Update order status.")' value="Cancel">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Display the confirmation box while trying to remove order-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="removeForm" name="removeForm" action="" method="POST" onsubmit="return removeOrder();">
                    <div class='header'>
                        Are you sure you want remove this order?
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful remove order.")' value="No">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <section>
            <h1>Orders List</h1>
            <div style="text-align:right" class="addOrder">
                    <button onclick="location.href='addorder.php'">Add new order</button>
            </div>
            <!-- Display total order amount each status and add new order button -->
            <div class="orderMenu">
                <p onclick="location.href='orders.php?status=Pending'"><span class="amount" 
                         style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $countPending; ?></span>Pending Orders</p>
                <p onclick="location.href='orders.php?status=Shipping'"><span class="amount" 
                         style="background-color:#5bc0de;border-color: #46b8da;"><?php echo $countShipping; ?></span>Shipping Orders</p>
                <p onclick="location.href='orders.php?status=Delivering'"><span class="amount" 
                         style="background-color:#337ab7;border-color: #2e6da4;"><?php echo $countDelivering; ?></span>Delivering Orders</p>
                <p onclick="location.href='orders.php?status=Completed'"><span class="amount" 
                         style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $countCompleted; ?></span>Completed Orders</p>
                <p onclick="location.href='orders.php?status=Canceled'"><span class="amount" 
                         style="background-color:#d9534f;border-color: #d43f3a;"><?php echo $countCanceled; ?></span>Canceled Orders</p>
                <p onclick="location.href='orders.php'"><span class="amount"><?php echo $totalCount; ?></span>Total Orders</p>  
                
            </div>
            <!-- Search Bar -->
            <div class="filter">

                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$status,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the orders list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all orders list -->
            <div id="table">
                <table id="orderList">
                    <?php orderDetails(); ?>
                    <tr>
                        <td colspan='9'height='60px' style="display:none;" class="emptySlot"><b>NO RESULT FOUND</b></td>
                    </tr>
                </table>
                <!-- Page number -->
                <div id="page">
                     <div id="pageNumber">
                        <?php displayPageNumber(); ?>
                    </div>
                </div>
            </div>
            
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
