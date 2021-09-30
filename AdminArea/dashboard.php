<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link href="CSS/dashboard.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/dashboard.js" type="text/javascript"></script>
    </head>
    <body onresize="othersGraph();avenueGraph();">
        <?php include 'header.php'; include 'Function/dashboard.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <?php avenueSummaryGraph(); othersSummaryGraph(); ?>
        <section>
            <h1>Dashboard</h1>
            <!-- Display the count of current month and last month-->
            <div class="row-1">
                <div class="box" id="clients">
                    <div id="boxHeader"><p class="boxTitle">CLIENTS</p><p class="currentMonth"><?php numClient();?></p></div>
                    <p id="boxContent"><?php clientPercentage(); numNewClient();?> </p>
                    <p id="boxFooter">Last month: <span id="lastMonth"><?php numOldClient(); ?></span></p>
                </div>
                <div class="box" id="orders">
                    <div id="boxHeader"><p class="boxTitle">ORDERS</p><p class="currentMonth"><?php numOrders();?></p></div>
                    <p id="boxContent"><?php orderPercentage(); numNewOrders();?> </p>
                    <p id="boxFooter">Last month: <span id="lastMonth"><?php numOldOrders(); ?></span></p>
                </div>
                <div class="box" id="sold">
                    <div id="boxHeader"><p class="boxTitle">SOLD</p><p class="currentMonth"><?php numSold();?></p></div>
                    <p id="boxContent"><?php soldPercentage(); numNewSold();?> </p>
                    <p id="boxFooter">Last month: <span id="lastMonth"><?php numOldSold(); ?></span></p>
                </div>
                <div class="box" id="revenue">
                    <div id="boxHeader"><p class="boxTitle">REVENUE</p><p class="currentMonth">RM<?php numAvenue();?></p></div>
                    <p id="boxContent"><?php avenuePercentage(); numNewAvenue();?> </p>
                    <p id="boxFooter">Last month: <span id="lastMonth">RM<?php numOldAvenue(); ?></span></p>
                </div>
            </div>
            <!-- Show the avenue,client,order,item purchased,support summary -->
            <div class="row-2">
                <div>
                    <div class="summaryCollapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                    <h3>Avenue Summary</h3><br>
                    <div id="avenueSummary" ></div>
                </div>
                <div>
                    <div class="summaryCollapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                    <h3>Others Summary</h3><br>
                    <div id="othersSummary" ></div>
                </div>
                
            </div>
            <!-- Show the order status and support status summary -->
            <div class="row-3">
                <div class="orderStatus">
                    <div class="statusCollapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                    <h3>Order Status</h3>
                    <div class="progressBar">
                        <?php displayOrderStatus(); ?>
                    </div>
                    <div class="list">
                        <div class="type"><span style="color:#f0ad4e;"><i class="fas fa-circle"></i></span>Pending</div>
                        <div class="type"><span style="color:#5bc0de;"><i class="fas fa-circle"></i></span>Shipping</div>
                        <div class="type"><span style="color:#337ab7;"><i class="fas fa-circle"></i></span>Delivering</div>
                        <div class="type"><span style="color:#5cb85c;"><i class="fas fa-circle"></i></span>Completed</div>
                        <div class="type"><span style="color:#d9534f;"><i class="fas fa-circle"></i></span>Cancelled</div>
                    </div>
                </div>
                <div class="supportStatus">
                    <div class="statusCollapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                    <h3>Support Status</h3>
                    <div class="progressBar">
                        <?php displaySupportStatus(); ?>
                    </div>
                    <div class="list">
                        <div class="type"><span style="color:#f0ad4e;"><i class="fas fa-circle"></i></span>Open</div>
                        <div class="type"><span style="color:#5bc0de;"><i class="fas fa-circle"></i></span>Pending</div>
                        <div class="type"><span style="color:#337ab7;"><i class="fas fa-circle"></i></span>Resolved</div>
                        <div class="type"><span style="color:#5cb85c;"><i class="fas fa-circle"></i></span>Closed</div>
                        <div class="type"><span style="color:#d9534f;"><i class="fas fa-circle"></i></span>Cancelled</div>
                    </div>
                </div>
            </div>
            <!-- Show the 5 latest clients -->
            <div id="table">
                <h2>Recent Clients</h2>
                <div class="filter">
                    
                    <div class="bar">
                        <div class="collapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                        <input type="text" class="searchBar" id="searchBarClient" onkeyup="searchClient()" placeholder="Filter the client list" title="Type in any word that you want to search">
                        <i id="searchIcon" class="fas fa-search"></i>
                    </div>
                </div>
                
                <table id="clientList">
                    <tr>
                        <th width="5%" onclick="sortClient(0);">ID<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="18.5%" onclick="sortClient(1);">Name<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="21.5%" onclick="sortClient(2);">Email<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="20%" onclick="sortClient(3);">Contact Number<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortClient(4);">Join Date<span id="sort"><i class="fas fa-sort"></i></span></th>
                    </tr>
                    <?php clientList();?>
                    <tr>
                            <td colspan='9'height='60px' style="display:none;" class="emptySlot"><b>NO RESULT FOUND</b></td>
                    </tr>
                </table>
                <div id="more">
                    <a href="client.php" id="moreButton">MORE</a>
                </div>
               
            </div>
            <!-- Show the 5 latest orders -->
            <div id="table">
                <h2>Recent Orders</h2>
                <div class="filter">
                    <div class="bar">
                        <div class="collapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                        <input type="text" class="searchBar" id="searchBarOrder" onkeyup="searchOrder()" placeholder="Filter the orders list" title="Type in any word that you want to search">
                        <i id="searchIcon" class="fas fa-search"></i>
                    </div>
                </div>
                <table id="orderList">
                    <tr>
                        <th width="10%" onclick="sortOrder(0);">Order ID<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortOrder(1);">Customer Name<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortOrder(2);">Purchased Time<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="14%" onclick="sortOrder(3);">Order No.<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortOrder(4);">Product Amount<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="10%" onclick="sortOrder(5);">Total<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="10%" onclick="sortOrder(6);">Status<span id="sort"><i class="fas fa-sort"></i></span></th>
                    </tr>
                    <?php orderDetails(); ?>
                    <tr>
                        <td colspan='9'height='60px' style="display:none;" class="emptySlot"><b>NO RESULT FOUND</b></td>
                    </tr>
                </table>
                <div id="more" style="margin-bottom: 20px;">
                    <a href="orders.php" id="moreButton">MORE</a>
                </div>
            </div>
            <!-- Show the 5 latest tickets -->
            <div id="table">
                <h2>Recent Support Tickets</h2>
                <div class="filter">
                    <div class="bar">
                        <div class="collapse"><i class="fas fa-minus-square"></i><i class="fas fa-plus-square" style="display:none;"></i></div>
                        <input type="text" class="searchBar" id="searchBarSupport" onkeyup="searchSupport()" placeholder="Filter the tickets list" title="Type in any word that you want to search">
                        <i id="searchIcon" class="fas fa-search"></i>
                    </div>
                </div>
                <table id="supportList">
                    <tr>
                        <th width="10%" onclick="sortSupport(0);">Support ID<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortSupport(1);">Customer Name<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortSupport(2);">Subject<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortSupport(3);">Created Time<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" onclick="sortSupport(4);">Last Reply<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="10%" onclick="sortSupport(5);">Status<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="10%" onclick="sortSupport(6);">Priority<span id="sort"><i class="fas fa-sort"></i></span></th>
                    </tr>
                    <?php supportDetails(); ?>
                    <tr>
                        <td colspan='9'height='60px' style="display:none;" class="emptySlot"><b>NO RESULT FOUND</b></td>
                    </tr>
                </table>
                <div id="more" style="margin-bottom: 20px;">
                    <a href="supports.php" id="moreButton">MORE</a>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
