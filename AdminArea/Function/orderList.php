<?php
    //Status list
    $statusArr = array("Pending","Shipping","Delivering","Completed","Canceled");
    $dataArr = array("orderID"=>"Order ID","clientID"=>"Client ID","purchasedTime"=>"Purchased Time","orderNo"=>"Order No.","productAmount"=>"Product Amount"
        ,"totalPrice"=>"Total Price","status"=>"Status");
    $dataWidthArr = array("orderID"=>"10%","clientID"=>"15%","purchasedTime"=>"15%","orderNo"=>"14%","productAmount"=>"10%","totalPrice"=>"10%","status"=>"10%");
    //Display statusArr list
    function statusList(){
        foreach($GLOBALS['statusArr'] as $value){
            printf("<option value='$value'>$value");
        }
    }
    //Display succesful remove order message
    if(isset($_SESSION["removed"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Remove the order.");}, 100);</script>';
        unset($_SESSION["removed"]);
    }
    //Display succesful update order status message
    if(isset($_SESSION["updated"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Update the order status.");}, 100);</script>';
        unset($_SESSION["updated"]);
    }
    //Display error message for invalid status
    if(isset($_SESSION["errorStatus"])){
         echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid order status.");}, 100);</script>';
         unset($_SESSION["errorStatus"]);
    }
    //Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['type'])){
            switch ($_POST['type']){
                case "order":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Created new order.");}, 100);</script>';
                    break;
                case "modifyInformation":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved order information.");}, 100);</script>';
                    break;
                case "modifyProducts":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved order products list.");}, 100);</script>';
                    break;
                case "modifyAddress":
                case "modifyExistAddress":
                case "newAddress":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved order address.");}, 100);</script>';
                    break;
            }
            $_POST = array();
        }
    }
    //Display invalid order id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid orderID.");}, 100);</script>';
    }
    //Count total order amount of each status
    $countPending = $countShipping = $countDelivering = $countCompleted = $countCanceled = $countRemoved = $totalCount = 0;
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT status, count(*) as total from orders GROUP BY status";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                switch($row->status){
                    case 'Pending':
                        $countPending = $row->total;
                        break;
                    case 'Shipping':
                        $countShipping = $row->total;
                        break;
                    case 'Delivering':
                        $countDelivering = $row->total;
                        break;
                    case 'Completed':
                        $countCompleted = $row->total;
                        break;
                    case 'Canceled':
                        $countCanceled = $row->total;
                        break;
                    case 'Removed':
                        $countRemoved = $row->total;
                        break;
                }
                $totalCount += $row->total;
            }
            $totalCount -= $countRemoved;
        }
        $result->free();
        $con->close();
    }
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        
        if ($totalCount>0){
            if ($index > ceil($GLOBALS['totalCount']/20.00)){
                $index = ceil($GLOBALS['totalCount']/20.00);
            }
        }
    }else{
        $index = 1;
    }
    //Filter the table with status
    $status = "NA";
    if (isset($_GET['status'])){
        $status = $_GET['status'];
        if (!in_array($status, $statusArr)){
            $status ="NA";
        }
    }
    //Sorting the table column
    $sort = "NA";
    $sortOrder = "ASC";
    if (isset($_GET['sort'])){
        $sort=$_GET['sort'];
        if (!array_key_exists($sort, $dataArr)){
            $sort="NA";
        }
        $sortOrder = "ASC";
        if (isset($_GET['sortOrder'])){
            $sortOrder = $_GET['sortOrder'];
            if ($sortOrder != "DESC"&&$sortOrder!="ASC"){
                $sortOrder = "ASC";
            }
        }
    }
    //Display order list
    function orderDetails(){
        global $dataArr,$sort,$sortOrder,$index,$status,$dataWidthArr;
        echo "<tr>";
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&status=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$status,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
        echo '<th width="10%" style="cursor:default;">Action</th></tr>';
        //Read all order data
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $statement = $sort=="NA"?"ORDER BY orderID DESC":"ORDER BY $sort $sortOrder";
            $statusStatement = $status == "NA"?"WHERE o.status != 'Removed'":"WHERE o.status LIKE '$status' AND o.status != 'Removed'";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT orderID, o.clientID, purchasedTime, orderNo,o.status,productAmount,totalPrice,name "
                    . "FROM orders o JOIN client c ON c.clientID = o.clientID $statusStatement $statement LIMIT $min_result,".LIST_PER_PAGE;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf("
                        <tr id='%s' class='orderData'>
                       <td>%s</td>
                       <td>%s<br>%s</td>
                       <td>%s</td>
                       <td>%s</td>
                       <td>%d</td>
                       <td>%.2f</td>
                       <td>%s</td>
                       <td>
                            <button id='details' onclick=\"location.href='detailOrder.php?orderId=%s'\">Details</button>
                            <button id='update' onclick='openOrderBox(\"%s\")'>Update</button><br>
                            <button id='modify' onclick=\"location.href='modifyOrder.php?orderId=%s'\">Modify</button>
                            <button id='remove' onclick='openBox(\"%s\")'>Remove</button><br>
                       </td>
                       </tr>
                    ",$row->orderID,$row->orderID,$row->clientID,$row->name,$row->purchasedTime,$row->orderNo,$row->productAmount,$row->totalPrice,$row->status,$row->orderID,$row->orderID,$row->orderID,$row->orderID);
                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='8'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }
            }
            $result->free();
            $con->close();
        }
        
    }
    //Display the page number button
    function displayPageNumber(){
        global $countPending, $countShipping, $countDelivering, $countCompleted, $countCanceled, $totalCount;
        $totalAmount = $GLOBALS['totalCount'];
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        $totalPage = ceil($totalAmount/20);
        if (isset($_GET['status'])){
            switch($_GET['status']){
                case 'Pending':
                    $totalPage = ceil($countPending/20);
                    break;
                case 'Shipping':
                    $totalPage = ceil($countShipping/20);
                    break;
                case 'Delivering':
                    $totalPage = ceil($countDelivering/20);
                    break;
                case 'Completed':
                    $totalPage = ceil($countCompleted/20);
                    break;
                case 'Canceled':
                    $totalPage = ceil($countCanceled/20);
                    break;
            }
        }
        
        if ($totalPage<$currentPage){
            $currentPage = $totalPage;
        }
        if ($currentPage != 1&&$currentPage != 0){
            $top = 1;
            echo "<a href='?page=$top' class='backward'><i class='fas fa-backward'></i></a>";
        }
        for ($i = 1; $i <= $totalPage;$i++){
            if ($i>=$currentPage-2&&$i<=$currentPage+2){
                if ($i == $currentPage){
                    echo "<a class='current'>$i</a>";
                }else{
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
        }
        if ($currentPage != $totalPage){
            $next = $totalPage;
            echo "<a href='?page=$next' class='forward'><i class='fas fa-forward'></i></a>";
        }
    }
    