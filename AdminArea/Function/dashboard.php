<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
    //Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['type'])){
            switch ($_POST['type']){
                case "modifyProfile":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved client account profile.");}, 100);</script>';
                    break;
                case "modifyPassword":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved client account new password.");}, 100);</script>';
                    break;
            }
            $_POST = array();
        }
    }
    //Display invalid staff id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid staffID.");}, 100);</script>';
    }
    //Get current year and month
    $currentYear = date("Y");
    $currentMonth = date("m");
    
    
    
    
    //Display latest clients records
    function clientList(){
        //Read all client data
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT clientID, name, email, contactNumber,joinDate from client ORDER BY clientID DESC LIMIT 0,".MAX_RECENT_RECORD;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                   printf("
                        <tr id='%s' class='clientData'>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                         </tr>
                 ",$row->clientID,$row->clientID,$row->name,$row->email,$row->contactNumber,$row->joinDate);
                }
                if ($result->num_rows ==0){
                    printf("
                <tr><td colspan='5'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                    ");
                }

            }
            $result->free();
            $con->close();
        }
    }
    //Display latest 5 orders
    function orderDetails(){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from orders o,client c WHERE o.clientID = c.clientID AND o.status != 'Removed' ORDER BY orderID DESC LIMIT 0,".MAX_RECENT_RECORD;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                   printf("
                    <tr id='%s' class='orderData'>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
                        <td>%s</td>
                   </tr>
                ",$row->orderID,$row->orderID,$row->name,$row->purchasedTime,$row->orderNo,$row->productAmount,$row->totalPrice,$row->status);
                }
                if ($result->num_rows ==0){
                    printf("
                <tr><td colspan='7'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                    ");
                }

            }
            $result->free();
            $con->close();
        }
        
    }
    //Display latest 5 supports
    function supportDetails(){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from support s,client c, message m WHERE s.clientID = c.clientID AND s.lastResponse = m.messageID AND s.status != 'Removed' ORDER BY supportID DESC LIMIT 0,".MAX_RECENT_RECORD;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf("
                      <tr id='%s' class='supportData'>
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s<br/><br/>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     </tr>
                  ",$row->supportID,$row->supportID,$row->name,$row->subject,$row->createdTime,getReplyRecipient($row->responserID),$row->time,$row->status,$row->priority);
                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='7'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }

            }
            $result->free();
            $con->close();
        }
        
    }
    //Get the person name,department and position of last reply
    function getReplyRecipient($id){
        $check= substr($id, 0,1); //Get first letter of the string as firsts letter represent data type
        if ($check=="C"){ //Customer
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name FROM client WHERE clientID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                    }
                }
            }
            $result->free();
            $con->close();
            $lastReply = "$id<br>$name<br/>Customer";
        }
        if ($check=="S"){ //Staff
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name,department, position FROM staff WHERE staffID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                        $department = $row->department;
                        $position = $row->position;
                    }
                }
            }
            $lastReply = "$id<br>$name<br/>$department<br/>$position";
        }
        return $lastReply;
    }
    $orderCount = $orderCountPending = $orderCountShipping = $orderCountDelivering = $orderCountCompleted = $orderCountCanceled = 0;
    //Display progress bar of order according order status
    function displayOrderStatus(){
        global $orderCount, $orderCountPending, $orderCountShipping, $orderCountDelivering, $orderCountCompleted, $orderCountCanceled;
        $percentPending = $orderCountPending/$orderCount*100;
        $percentShipping = $orderCountShipping/$orderCount*100;
        $percentDelivering = $orderCountDelivering/$orderCount*100;
        $percentCompleted = $orderCountCompleted/$orderCount*100;
        $percentCanceled = $orderCountCanceled/$orderCount*100;
        if ($orderCountPending>0){
            printf('<div id="bar" style="background-color: #f0ad4e;width:%d%%"><span class="tooltip">Pending</span>%.2f%%</div>',$percentPending,$percentPending);
        }
        if ($orderCountShipping>0){
            printf('<div id="bar" style="background-color: #5bc0de;width:%d%%"><span class="tooltip">Shipping</span>%.2f%%</div>',$percentShipping,$percentShipping);
        }
        if ($orderCountDelivering>0){
            printf('<div id="bar" style="background-color: #337ab7;width:%d%%"><span class="tooltip">Delivering</span>%.2f%%</div>',$percentDelivering,$percentDelivering);
        }
        if ($orderCountCompleted>0){
            printf('<div id="bar" style="background-color: #5cb85c;width:%d%%"><span class="tooltip">Completed</span>%.2f%%</div>',$percentCompleted,$percentCompleted);
        }
        if ($orderCountCanceled>0){
            printf('<div id="bar" style="background-color: #d9534f;width:%d%%"><span class="tooltip">Canceled</span>%.2f%%</div>',$percentCanceled,$percentCanceled);
        }
    }
    $supportCount=$supportCountOpen = $supportCountPending = $supportCountResolved = $supportCountClosed = $supportCountCanceled = 0;
    //Display progress bar of support according support status
    function displaySupportStatus(){
        global $supportCount,$supportCountOpen,$supportCountPending,$supportCountResolved,$supportCountClosed,$supportCountCanceled;
        $percentOpen = $supportCountOpen/$supportCount*100;
        $percentPending = $supportCountPending/$supportCount*100;
        $percentResolved = $supportCountResolved/$supportCount*100;
        $percentClosed = $supportCountClosed/$supportCount*100;
        $percentCanceled = $supportCountCanceled/$supportCount*100;
        if ($supportCountOpen>0){
            printf('<div id="bar" style="background-color: #f0ad4e;width:%d%%"><span class="tooltip">Open</span>%.2f%%</div>',$percentOpen,$percentOpen);
        }
        if ($supportCountPending>0){
            printf('<div id="bar" style="background-color: #5bc0de;width:%d%%"><span class="tooltip">Pending</span>%.2f%%</div>',$percentPending,$percentPending);
        }
        if ($supportCountResolved>0){
            printf('<div id="bar" style="background-color: #337ab7;width:%d%%"><span class="tooltip">Resolved</span>%.2f%%</div>',$percentResolved,$percentResolved);
        }
        if ($supportCountClosed>0){
            printf('<div id="bar" style="background-color: #5cb85c;width:%d%%"><span class="tooltip">Completed</span>%.2f%%</div>',$percentClosed,$percentClosed);
        }
        if ($supportCountCanceled>0){
            printf('<div id="bar" style="background-color: #d9534f;width:%d%%"><span class="tooltip">Canceled</span>%.2f%%</div>',$percentCanceled,$percentCanceled);
        }
    }
    $monthList = array(0,0,0,0,0,0);
    $monthList[0] = date("Y-m"); //Get current year and month
    $avenueMonth = array(0,0,0,0,0,0);
    $soldMonth = array(0,0,0,0,0,0);
    $orderMonth = array(0,0,0,0,0,0);
    $clientMonth = array(0,0,0,0,0,0);
    $staffMonth = array(0,0,0,0,0,0);
    $supportMonth = array(0,0,0,0,0,0);
    for ($i = 1;$i < 6;$i++){ //Get previous month
        $monthList[$i] = previousMonth($i);
    }
    //Get total client
    $countClient = 0;
    $countNewClients = 0; 
    $countOldClients = 0;
    //Get new client that joined in current month
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from client";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $joinDate = $row->joinDate;
                $year = substr($joinDate, 0,4);
                $month = substr($joinDate,5,2);
                $date = substr($joinDate, 0,7);
                if ($year == $currentYear&&$month ==$currentMonth){
                    $countNewClients++;
                }
                for($k=0;$k<6;$k++){
                    if ($monthList[$k]==$date){
                        $clientMonth[$k]++;
                    }
                }
                if ($year == $currentYear&&$month ==$currentMonth-1){
                    if (($currentMonth-1)==0){
                        $currentMonth = 12;
                        $currentYear = $currentYear-1;
                    }
                    $countOldClients++; //Get last month client
                }
                $countClient++;
            }
        }
        $result->free();
        $con->close();
    }
    //Return total client
    function numClient(){
        echo $GLOBALS['countClient'];
    }
    //Return new client that joined in current month
    function numNewClient(){
        
        $result = $GLOBALS['countNewClients'];
        if ($result >= 0){
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-up'></i> $result</span>";
        }else{
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-down'></i> $result</span>";
        }
        
    }
    //Return total clients of last month
    function numOldClient(){
        echo $GLOBALS['countOldClients'];
    }
    //Calculate percentage increased in current month
    function clientPercentage(){
        if ($GLOBALS["countOldClients"]!=0){
            $result = $GLOBALS["countNewClients"]/$GLOBALS["countOldClients"]*100;
            $result = number_format($result, 2);
            if ($result >= 0){
                echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
            }else{
                echo "<span id='percentageIncrease' style='color: #d9534f'>$result%</span>";
            }
        }else{
            $result = $GLOBALS["countNewClients"]*100;
            $result = number_format($result, 2);
            echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
        }
        
    }
    //Get order,items sold and avenue of total, previous month and current month
    $countOrders = 0;
    $countNewOrders = 0; 
    $countOldOrders = 0;
    $countSold = 0;
    $countNewSold = 0;
    $countOldSold = 0;
    $countAvenue = 0;
    $countNewAvenue = 0;
    $countOldAvenue=0;
    //Get order of previous month, current month and last 6 month
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from orders o WHERE status != 'Removed'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $purchasedTime = $row->purchasedTime;
                $year = substr($purchasedTime, 0,4);
                $month = substr($purchasedTime,5,2);
                $purchasedDate = substr($purchasedTime, 0,7);
                if ($year == $currentYear&&$month ==$currentMonth){
                    $countNewOrders++;
                }
                if ($year == $currentYear&&$month ==$currentMonth-1){
                    if (($currentMonth-1)==0){
                        $currentMonth = 12;
                        $currentYear = $currentYear-1;
                    }
                    $countOldOrders++;
                }
                for($k=0;$k<6;$k++){
                    if ($monthList[$k]==$purchasedDate){
                        $orderMonth[$k]++;
                    }
                }
                switch ($row->status){
                    case 'Pending':
                        $orderCountPending++;
                        break;
                    case 'Shipping':
                        $orderCountShipping++;
                        break;
                    case 'Delivering':
                        $orderCountDelivering++;
                        break;
                    case 'Completed':
                        $orderCountCompleted++;
                        break;
                    case 'Canceled':
                        $orderCountCanceled++;
                        break;
                }
                $countOrders++;
            }
        }
        $orderCount = $countOrders;
        $result->free();
        $con->close();
    }
    
    //Return total orders
    function numOrders(){
        echo $GLOBALS['countOrders'];
    }
    //Return new orders of current month
    function numNewOrders(){
        
        $result = $GLOBALS['countNewOrders'];
        if ($result >= 0){
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-up'></i> $result</span>";
        }else{
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-down'></i> $result</span>";
        }
        
    }
    //Return total orders of last month
    function numOldOrders(){
        echo $GLOBALS['countOldOrders'];
    }
    //Calculate percentage increased in current month
    function orderPercentage(){
        if ($GLOBALS["countOldOrders"]!=0){
            $result = $GLOBALS["countNewOrders"]/$GLOBALS["countOldOrders"]*100;
            $result = number_format($result, 2);
            if ($result >= 0){
                echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
            }else{
                echo "<span id='percentageIncrease' style='color: #d9534f'>$result%</span>";
            }
        }else{
            $result = $GLOBALS["countNewOrders"]*100;
            $result = number_format($result, 2);
            echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
        }
        
    }
    //Get revenue and sold of previous month, current month and last 6 month
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from orders o, orderproduct op, productlist p WHERE o.orderID = op.orderID AND op.productID = p.Product_ID AND o.status != 'Removed'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $purchasedTime = $row->purchasedTime;
                $year = substr($purchasedTime, 0,4);
                $month = substr($purchasedTime,5,2);
                $purchasedDate = substr($purchasedTime, 0,7);
                if ($year == $currentYear&&$month ==$currentMonth){
                    $countNewSold++;
                    $countNewAvenue += $row->quantity*$row->Price;
                }
                if ($year == $currentYear&&$month ==$currentMonth-1){
                    if (($currentMonth-1)==0){
                        $currentMonth = 12;
                        $currentYear = $currentYear-1;
                    }
                    $countOldSold++;
                    $countOldAvenue += $row->quantity*$row->Price;
                }
                $countAvenue += $row->quantity*$row->Price;
                $countSold++;
                for($k=0;$k<6;$k++){
                    if ($monthList[$k]==$purchasedDate){
                        $avenueMonth[$k] += $row->quantity*$row->Price;
                        $soldMonth[$k]++;
                    }
                }
            }
        }
        $result->free();
        $con->close();
    }
    //Return total item sold
    function numSold(){
        echo $GLOBALS['countSold'];
    }
    //Return item sold in current month
    function numNewSold(){
        
        $result = $GLOBALS['countNewSold'];
        if ($result >= 0){
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-up'></i> $result</span>";
        }else{
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-down'></i> $result</span>";
        }
        
    }
    //Return total item sold in last month
    function numOldSold(){
        echo $GLOBALS['countOldSold'];
    }
    //Calculate percentage increased in current month
    function soldPercentage(){
        if ($GLOBALS["countOldSold"]!=0){
            $result = $GLOBALS["countNewSold"]/$GLOBALS["countOldSold"]*100;
            $result = number_format($result, 2);
            if ($result >= 0){
                echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
            }else{
                echo "<span id='percentageIncrease' style='color: #d9534f'>$result%</span>";
            }
        }else{
            $result = $GLOBALS["countNewSold"]*100;
            $result = number_format($result, 2);
            echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
        }
    }
    //Return total avenue
    function numAvenue(){
        echo number_format($GLOBALS['countAvenue'],2);
    }
    //Return avenue in current month
    function numNewAvenue(){
        
        $result = number_format($GLOBALS['countNewAvenue'],2);
        if ($result >= 0){
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-up'></i> RM$result</span>";
        }else{
            echo "<span id='amountIncrease' style='color: white; font-weight: bold;'><i class='fas fa-long-arrow-alt-down'></i> RM$result</span>";
        }
        
    }
    //Return total avenue in last month
    function numOldAvenue(){
        echo number_format($GLOBALS['countOldAvenue'],2);
    }
    //Calculate percentage increased in current month
    function avenuePercentage(){
        if ($GLOBALS["countOldAvenue"]!=0){
            $result = $GLOBALS["countNewAvenue"]/$GLOBALS["countOldAvenue"]*100;
            $result = number_format($result, 2);
            if ($result >= 0){
                echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
            }else{
                echo "<span id='percentageIncrease' style='color: #d9534f'>$result%</span>";
            }
        }else{
            $result = $GLOBALS["countNewAvenue"]*100;
            $result = number_format($result, 2);
            echo "<span id='percentageIncrease' style='color: #5cb85c'>+$result%</span>";
        }
    }
    //Get staff count of last 6 month
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from staff";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $staffjoinDate = $row->joinDate;
                $staffdate = substr($staffjoinDate, 0,7);
                for($k=0;$k<6;$k++){
                    if ($monthList[$k]==$staffdate){
                        $staffMonth[$k]++;
                    }
                }
            }
        }
        $result->free();
        $con->close();
    }
    //Get support count of last 6 month
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from support WHERE status != 'Removed'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $supportjoinDate = $row->createdTime;
                $supportdate = substr($supportjoinDate, 0,7);
                for($k=0;$k<6;$k++){
                    if ($monthList[$k]==$supportdate){
                        $supportMonth[$k]++;
                    }
                }
                switch($row->status){
                    case 'Open':
                        $supportCountOpen++;
                        break;
                    case 'Pending':
                        $supportCountPending++;
                        break;
                    case 'Resolved':
                        $supportCountResolved++;
                        break;
                    case 'Closed':
                        $supportCountClosed++;
                        break;
                    case 'Canceled':
                        $supportCountCanceled++;
                        break;
                }
                $supportCount++;
            }
        }
        $result->free();
        $con->close();
    }
    function othersSummaryGraph(){ //Display graph for order count, staff count, client count, item sold count and support count
        $monthList = $GLOBALS['monthList'];
        $soldMonth = $GLOBALS['soldMonth'];
        $orderMonth = $GLOBALS['orderMonth'];
        $staffMonth = $GLOBALS['staffMonth'];
        $clientMonth = $GLOBALS['clientMonth'];
        $supportMonth = $GLOBALS['supportMonth'];
        printf("
            <script>
                google.charts.load('current',{'packages':['corechart']});
                google.charts.setOnLoadCallback(othersGraph);
                function othersGraph(){
                    var data = google.visualization.arrayToDataTable([
                        ['string','Client','Staff','Order','Sold','Support'],
                        ['%s',$clientMonth[5],$staffMonth[5],$orderMonth[5],$soldMonth[5],$supportMonth[5]],
                            ['%s',$clientMonth[4],$staffMonth[4],$orderMonth[4],$soldMonth[4],$supportMonth[4]],
                                ['%s',$clientMonth[3],$staffMonth[3],$orderMonth[3],$soldMonth[3],$supportMonth[3]],
                                    ['%s',$clientMonth[2],$staffMonth[2],$orderMonth[2],$soldMonth[2],$supportMonth[2]],
                                        ['%s',$clientMonth[1],$staffMonth[1],$orderMonth[1],$soldMonth[1],$supportMonth[1]],
                                            ['%s',$clientMonth[0],$staffMonth[0],$orderMonth[0],$soldMonth[0],$supportMonth[0]]
                    ]);
                    var settings = {
                        
                        vAxis: {minValue: 0},
                        chartArea: {
                            width: '80%%',
                            height: '50%%'
                        },
                        legend: {
                            position: 'bottom'
                        },
                        width: '50%%',
                        backgroundColor: 'white',
                        hAxis: {title:'Year-Month'},
                    };
                    var chart = new google.visualization.AreaChart(document.getElementById('othersSummary'));
                    chart.draw(data, settings);
                }
            </script>
                ",$monthList[5],$monthList[4],$monthList[3],$monthList[2],$monthList[1],$monthList[0]);
    }
    function avenueSummaryGraph(){ //Display avenue graph
        
        $monthList = $GLOBALS['monthList'];
        $avenueMonth = $GLOBALS['avenueMonth'];
        printf("
            <script>
                google.charts.load('current',{'packages':['corechart']});
                google.charts.setOnLoadCallback(avenueGraph);
                function avenueGraph(){
                    var data = google.visualization.arrayToDataTable([
                        ['string','Avenue'],
                        ['%s',$avenueMonth[5]],
                            ['%s',$avenueMonth[4]],
                                ['%s',$avenueMonth[3]],
                                    ['%s',$avenueMonth[2]],
                                        ['%s',$avenueMonth[1]],
                                            ['%s',$avenueMonth[0]]
                    ]);
                    var settings = {
                        
                        vAxis: {minValue: 0},
                        chartArea: {
                            width: '90%%',
                            height: '50%%'
                        },
                        legend: {
                            position: 'bottom'
                        },
                        width: '45%%',
                        hAxis: {title:'Year-Month'}
                    };
                    var chart = new google.visualization.AreaChart(document.getElementById('avenueSummary'));
                    chart.draw(data, settings);
                }
            </script>
                ",$monthList[5],$monthList[4],$monthList[3],$monthList[2],$monthList[1],$monthList[0]);
    }
    
    function previousMonth($n){ //Return previous month
        $previousMonth = date("Y-m", strtotime("-".$n." months"));
        return $previousMonth;
    }