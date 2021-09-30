<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/supports.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/supports.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php';include 'Function/supportList.php';?>
        <!-- Box for update the support status and priority -->
        <div id='supportBox'> 
            <div class='contents'>
                <div class='header'>
                    Change status and priority for a ticket.
                </div>
                <div class='body'>
                    <form action="supports.php" method="POST" style="text-align:center" onsubmit="return updateStatus();">
                        <label for="status">Status: </label><br/>
                        <select name="status" id="status">
                            <?php statusList();?>
                        </select><br/><br/>
                        <label for="priority">Priority: </label><br/>
                        <select name="priority" id="priority">
                            <?php priorityList();?>
                        </select> 
                        <br/>
                        <br/>
                        <div id='button'>
                            <input type="submit" class='yes' value="Update">
                            <input type="button"class='no' onclick='closeSupportBox(),addNotification("yellow","<b>Cancelled</b>! Update ticket status and priority.")' value="Cancel">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Display the confirmation box while trying to remove ticket-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="removeForm" name="removeForm" action="" method="POST" onsubmit="return removeSupport();">
                    <div class='header'>
                        Are you sure you want remove this ticket?
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful remove support tickets.")' value="No">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <section>
            <h1>Support Tickets List</h1>
            <div style="text-align:right" class="addTicket">
                    <button onclick="location.href='addsupport.php'">Add new ticket</button>
            </div>
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
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$statusA,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the supports list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
             <!-- Display all support tickets list -->
            <div id="table">
                <table id="supportList">
                    <?php supportList();?>
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
