<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/client.js" type="text/javascript"></script>
    </head>
    <body>
        <?php 
            include 'header.php';
            include 'Function/clientList.php';
            
        ?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <!--Display the confirmation box while trying to terminate client-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="terminateForm" name="terminateForm" action="" method="POST" onsubmit="return terminateClient();">
                    <div class='header'>
                        Are you sure you want terminate this client?
                        <br>
                        After save all the previous details will be override.
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful terminate staff account.")' value="No">
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
        <section>
            <h1>Client List</h1>
            <!-- Display total client amount and add new client button -->
            <div class="clientMenu">
                <p onclick="location.href='client.php?status=Active'"><span class="amount" style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $activeAmount;?></span>Active</p>
                <p onclick="location.href='client.php?status=Inactive'"><span class="amount" style="background-color: #5bc0de;border-color: #46b8da;"><?php echo $inactiveAmount;?></span>Inactive</p>
                <p onclick="location.href='client.php?status=Suspended'"><span class="amount" style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $suspendedAmount;?></span>Suspended</p>
                <p onclick="location.href='client.php?status=Terminated'"><span class="amount" style="background-color:#d9534f;border-color: #d43f3a;"><?php echo $terminatedAmount;?></span>Terminated</p>
                <p onclick="location.href='client.php?status=Guest'"><span class="amount" style="background-color:#337ab7;border-color: #2e6da4;"><?php echo $guestAmount;?></span>Guest</p>
                <p onclick="location.href='client.php'"><span class="amount"><?php echo $totalAmount;?></span>Client Accounts</p>
                <button onclick="location.href='addclient.php'">Add new client</button> <!-- Create new client button -->
            </div>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$status,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalAmount); ?>)" placeholder="Filter the client list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all clients list -->
            <div id="table">
                <table id="clientList">
                    <?php clientList();?>
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