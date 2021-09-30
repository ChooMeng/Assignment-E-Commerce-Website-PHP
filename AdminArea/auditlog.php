<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/auditlog.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/auditlog.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php';include 'Function/auditlog.php';?>
        <section>
            <h1>Audit Log</h1>
            <!-- Display total order amount each status and add new order button -->
            <div class="auditMenu">
                <p onclick="location.href='auditlog.php?type=Client'"><span class="amount" 
                         style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $countClients; ?></span>Client Logs</p>
                <p onclick="location.href='auditlog.php?type=Product'"><span class="amount" 
                         style="background-color:#5bc0de;border-color: #46b8da;"><?php echo $countProducts; ?></span>Product Logs</p>
                <p onclick="location.href='auditlog.php?type=Order'"><span class="amount" 
                         style="background-color:#337ab7;border-color: #2e6da4;"><?php echo $countOrders; ?></span>Order Logs</p>
                <p onclick="location.href='auditlog.php?type=Staff'"><span class="amount" 
                         style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $countStaffs; ?></span>Staff Logs</p>
                <p onclick="location.href='auditlog.php?type=Support'"><span class="amount" 
                         style="background-color:#d9534f;border-color: #d43f3a;"><?php echo $countSupports; ?></span>Support Logs</p>
                <p onclick="location.href='auditlog.php'"><span class="amount"><?php echo $totalCount; ?></span>All Logs</p>  
                
            </div>
            <!-- Search Bar -->
            <div class="filter">

                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$type,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the audit logs" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all orders list -->
            <div id="table">
                <table id="auditList">
                    
                    <?php auditDetails(); ?>
                    
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
