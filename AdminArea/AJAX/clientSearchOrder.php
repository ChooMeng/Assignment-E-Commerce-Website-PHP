<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])||!isset($_GET["page"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../../settings.php';
    echo '<table id="orderList">';
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        if ($_GET['total']!=0){
            if ($index > ceil($_GET['total']/20.00)){
                $index = ceil($_GET['total']/20.00);
            }
        }else{
            $index = 1;
        }
    }else{
        $index = 1;
    }
    $clientID = $_GET['clientId'];
    //Client data type Array
    $dataArr = array("orderID"=>"Order ID","purchasedTime"=>"Purchased Time","orderNo"=>"Order No","productAmount"=>"Product Amount","totalAmount"=>"Total","status"=>"Status");
    //Sorting the table column
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
    echo "<tr>";
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
    if (empty($search)){
        foreach($dataArr as $key => $value){
            printf('
                <th onclick="location.href=\'?page=%s&clientId=%s&sort=%s&sortOrder=%s#orderList\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$index,$clientID,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
    }else{
        $dataCount = 0;
        foreach($dataArr as $key => $value){
            printf('
                <th onclick="sort(%d)">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataCount,$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
            $dataCount++;
        }
    }
    echo '<th width="10%" style="cursor:default;">Action</th></tr>';
    //Read all order data
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $statement = $sort=="NA"?"ORDER BY clientID DESC":"ORDER BY $sort $sortOrder";
        $min_result = ($index-1)*LIST_PER_PAGE;
        if (empty($search)){
            $sql = "SELECT * from orders WHERE clientID = '$clientID' AND status != 'Removed' $statement LIMIT $min_result,".LIST_PER_PAGE;
        }else{
            $sql = "SELECT * from orders WHERE clientID = '$clientID' AND status != 'Removed' AND "
                    . "(orderID LIKE '%$search%' OR purchasedTime LIKE '%$search%' "
                    . "OR orderNo LIKE '%$search%' OR status LIKE '%$search%' OR productAmount LIKE '%$search%' "
                    . "OR totalPrice LIKE '%$search%') $statement";
        }
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                printf("
                    <tr class='orderData'>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td>%d</td>
                        <td>%s</td>
                        <td><button onclick=\"location.href='detailOrder.php?orderId=%s'\">Details</button></td>
                    </tr>
                     ",$row->orderID,$row->purchasedTime,$row->orderNo,$row->productAmount,$row->totalPrice,$row->status,$row->orderID);
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
    echo '</table>';
    if (empty($search)){
        echo'<!-- Page number -->
                <div id="page">
                     <div id="pageNumber">
                        ';
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        
        $totalPage = ceil($_GET['total']/20);
        
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
