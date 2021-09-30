<?php
    $dataArr = array("orderID"=>"Order ID","clientID"=>"Client ID","purchasedTime"=>"Purchased Time","orderNo"=>"Order No.","productAmount"=>"Product Amount"
        ,"totalPrice"=>"Total Price","oldStatus"=>"Old Status");
    $dataWidthArr = array("orderID"=>"10%","clientID"=>"15%","purchasedTime"=>"15%","orderNo"=>"14%","productAmount"=>"10%","totalPrice"=>"10%","oldStatus"=>"10%");
    //Display succesful restore order message
    if(isset($_SESSION["restored"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Restore the order.");}, 100);</script>';
        unset($_SESSION["restored"]);
    }
    //Display invalid order id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid orderID.");}, 100);</script>';
    }
    //Count total order amount of each status
    $totalCount = 0;
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
                    case 'Removed':
                        $totalCount = $row->total;
                        break;
                }
            }
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
        global $dataArr,$sort,$sortOrder,$index,$dataWidthArr;
        echo "<tr>";
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
        echo '<th width="10%" style="cursor:default;">Action</th></tr>';
        //Read all order data
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $statement = $sort=="NA"?"ORDER BY orderID DESC":"ORDER BY $sort $sortOrder";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT orderID, o.clientID, purchasedTime, orderNo,o.status,productAmount,oldStatus,totalPrice,name "
                    . "FROM orders o JOIN client c ON c.clientID = o.clientID WHERE o.status = 'Removed' $statement LIMIT $min_result,".LIST_PER_PAGE;
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
                            <button id='restore' onclick='openBox(\"%s\")'>Restore</button><br>
                       </td>
                       </tr>
                    ",$row->orderID,$row->orderID,$row->clientID,$row->name,$row->purchasedTime,$row->orderNo,$row->productAmount,$row->totalPrice,$row->oldStatus,$row->orderID);
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
        global $totalCount;
        $totalAmount = $GLOBALS['totalCount'];
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        $totalPage = ceil($totalAmount/20);
        
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
    