<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/detailsClient.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/detailClient.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';include 'Function/clientDetails.php';?>
        <section>
            <h1>Client Details</h1>
            <!-- Display client details -->
            <div id="details"> 
                <?php details();?>
                
            </div>
            <hr>
            <!-- Display client address -->
            <h2 style='text-align: center'>Client Address's</h2>
            <table>
                <tr>
                    <th>Address</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Default</th>
                </tr>
                
                <?php displayAddress();?>
            </table>
            <br>
            <hr>
            <!-- Display client order details -->
            <h2 style="text-align:center">Client Order Lists</h2>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$clientID,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalAmount); ?>)" placeholder="Filter the staff list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <div id="table">
                <table id="orderList">
                        <?php orderDetails();?>
                </table>
                <!-- Page number -->
                <div id="page">
                    <div id="pageNumber">
                       <?php displayPageNumber(); ?>
                    </div>
                </div>
            </div>
            <hr>
            <!-- Button for modify or back -->
            <div class="buttonMenu">
                <button id='modify' onclick='location.href="modifyClient.php?clientId=<?php echo $clientID;?>"'>Modify</button>
                <button id='back' onclick='location.href ="client.php"'>Back</button>
            </div>
            <br/>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
