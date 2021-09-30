<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/supports.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/supportRestore.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php';include 'Function/supportRestore.php';?>
        <!--Display the confirmation box while trying to remove order-->
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="restoreForm" name="restoreForm" action="" method="POST" onsubmit="return restoreSupport();">
                    <div class='header'>
                        Are you sure you want restore this support ticket?
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful restore the support ticket.")' value="No">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <section>
            <h1>Removed Supports Ticket List</h1>
            <!-- Display total support amount each status -->
            <div class="supportMenu">
                <p onclick="location.href='supportRestore.php'"><span class="amount"><?php echo $totalCount; ?></span>Removed Support Tickets</p>  
                
            </div>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s',%s,%s",$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalCount); ?>)" placeholder="Filter the removed support list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <!-- Display all support list -->
            <div id="table">
                <table id="supportList">
                    <?php supportLIst(); ?>
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
