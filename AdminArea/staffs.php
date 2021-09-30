<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/staffs.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/staff.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';include 'Function/staffList.php'?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <!--Display the confirmation box while trying to terminate staff-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="terminateForm" name="terminateForm" action="" method="POST" onsubmit="return terminateStaff();">
                    <div class='header'>
                        Are you sure you want terminate this staff?
                        
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
            
            <h1>Staff List</h1>
            <!-- Display total staff amount and add new staff button -->
            <div class="staffMenu">
                <p onclick="location.href='staffs.php?status=Active'"><span class="amount" style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $activeAmount;?></span>Active</p>
                <p onclick="location.href='staffs.php?status=Suspended'"><span class="amount" style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $suspendedAmount;?></span>Suspended</p>
                <p onclick="location.href='staffs.php?status=Terminated'"><span class="amount" style="background-color:#d9534f;border-color: #d43f3a;"><?php echo $terminatedAmount;?></span>Terminated</p>
                <p onclick="location.href='staffs.php'"><span class="amount"><?php echo $totalAmount;?></span>Staff Accounts</p>
                <button onclick="location.href='addstaff.php'">Add new staff</button>
                
            </div>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$status,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalAmount); ?>)" placeholder="Filter the staff list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all staffs list -->
            <div id="table">
                <table id="staffList">
                    <?php staffList();?>
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
