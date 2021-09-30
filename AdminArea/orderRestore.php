<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/orders.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/orderRestore.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php';include 'Function/orderRestore.php';?>
        <!--Display the confirmation box while trying to remove order-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="restoreForm" name="restoreForm" action="" method="POST" onsubmit="return restoreOrder();">
                    <div class='header'>
                        Are you sure you want restore this order?
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful restore the order.")' value="No">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <section>
            <h1>Removed orders List</h1>
            <!-- Display total order amount each status-->
            <div class="orderMenu">
                <p onclick="location.href='orderRestore.php'"><span class="amount"><?php echo $totalCount; ?></span>Removed Orders</p>  
                
            </div>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s',%s,%s",$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the removed order list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all orders list -->
            <div id="table">
                <table id="orderList">
                    <?php orderDetails(); ?>
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
