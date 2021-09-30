<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/supports.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/supports.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/supportList.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <section>
            <h1>My Tickets</h1><button class="addTicket" onclick="location.href='addsupport.php'">Create new ticket</button>
            <!-- Display total support amount each status and add new order button -->
            <div class="supportMenu">
                <p onclick="location.href='supports.php?status=Open'"><span class="amount" 
                         style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $countOpen; ?></span>Open Tickets</p>
                <p onclick="location.href='supports.php?status=Pending'"><span class="amount" 
                         style="background-color:#5bc0de;border-color: #46b8da;"><?php echo $countPending; ?></span>Pending Tickets</p>
                <p onclick="location.href='supports.php?status=Resolved'"><span class="amount" 
                         style="background-color:#337ab7;border-color: #2e6da4;"><?php echo $countResolved; ?></span>Resolved Tickets</p>
                <p onclick="location.href='supports.php?status=Closed'"><span class="amount" 
                         style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $countClosed; ?></span>Closed Tickets</p>
                <p onclick="location.href='supports.php?status=Canceled'"><span class="amount" 
                         style="background-color:#d9534f;border-color: #d43f3a;"><?php echo $countCanceled; ?></span>Canceled Tickets</p>
                <p onclick="location.href='supports.php'"><span class="amount"><?php echo $totalCount; ?></span>Total Tickets</p>  
                
            </div>
            <!-- Search Bar -->
            <div class="filter">

                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchTicketBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$statusA,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the support tickets list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all support tickets list -->
            <div id="table">
                <table id="supportList">
                    <?php supportList();?>
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
