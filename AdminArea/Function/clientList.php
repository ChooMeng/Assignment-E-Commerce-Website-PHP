<?php
    include "TempData/clients.php";
    //Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['type'])){
            switch ($_POST['type']){
                case "create":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Created client account.");}, 100);</script>';
                    break;
                case "modifyProfile":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved client account profile.");}, 100);</script>';
                    break;
                case "modifyPassword":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved client account new password.");}, 100);</script>';
                    break;
                case "modifyAddress":
                case "modifyExistAddress":
                case "newAddress":
                    echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Saved client account address.");}, 100);</script>';
                    break;
            }
            $_POST = array();
        }
    }
    //Display invalid cliend id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid clientID.");}, 100);</script>';
    }
    //Display succesful terminate account message
    if(isset($_SESSION["terminate"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Terminate the client account.");}, 100);</script>';
        unset($_SESSION["terminate"]);
    }
    $totalAmount = $activeAmount = $inactiveAmount = $suspendedAmount = $guestAmount = $terminatedAmount= 0;
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT status, count(*) as total from client GROUP BY status";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                switch($row->status){
                    case 'Active':
                        $activeAmount = $row->total;
                        break;
                    case 'Inactive':
                        $inactiveAmount = $row->total;
                        break;
                    case 'Suspended':
                        $suspendedAmount = $row->total;
                        break;
                    case 'Terminated':
                        $terminatedAmount= $row->total;
                        break;
                    case 'Guest':
                        $guestAmount= $row->total;
                        break;
                }
                $totalAmount+=$row->total;
            }
        }
        $result->free();
        $con->close();
    }
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        
        if ($totalAmount>0){
            if ($index > ceil($totalAmount/20.00)){
                $index = ceil($totalAmount/20.00);
            }
        }
    }else{
        $index = 1;
    }
    //Client Status Array
    $statusArr=array("Active","Inactive","Suspended","Terminated","Guest");
    //Client data type Array
    $dataArr = array("clientID"=>"ID","name"=>"Name","email"=>"Email","contactNumber"=>"Contact Number","lastLogin"=>"Last Login","status"=>"Status");
    $dataWidthArr = array("clientID"=>"5%","name"=>"18.5%","email"=>"21.5%","contactNumber"=>"18%","lastLogin"=>"14%","status"=>"4");
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
    //Display client list
    function clientList(){
        global $dataArr,$sort,$sortOrder,$index,$status,$dataWidthArr;
        echo "<tr>";
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&status=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$status,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
        echo '<th width="10%" style="cursor:default;">Action</th></tr>';
        //Read all client data
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $statement = $sort=="NA"?"ORDER BY clientID DESC":"ORDER BY $sort $sortOrder";
            $statusStatement = $status == "NA"?"":"WHERE status LIKE '$status'";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT clientID, name, email, contactNumber,lastlogin,status from client $statusStatement $statement LIMIT $min_result,".LIST_PER_PAGE;
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
                                <td>%s</td>
                                <td class='action'>
                                    <button id='details' onclick=\"location.href='detailClient.php?clientId=%s'\">Details</button><br>
                                    <button id='modify' onclick=\"location.href='modifyClient.php?clientId=%s'\">Modify</button><br>
                                    <button id='remove' %s %s>Terminate</button>
                                </td>
                            </tr>
                    ",$row->clientID,$row->clientID,$row->name,$row->email,$row->contactNumber,empty($row->lastlogin)?"No Login Record":$row->lastlogin,$row->status,$row->clientID,$row->clientID,$row->status=="Terminated"?"style='cursor:no-drop;'":"",$row->status=="Terminated"?"":"onclick='openBox(\"$row->clientID\");'");

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
    //Display the page number button
    function displayPageNumber(){
        global $activeAmount,$inactiveAmount,$suspendedAmount,$terminatedAmount,$guestAmount;
        $totalAmount = $GLOBALS['totalAmount'];
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        $totalPage = ceil($totalAmount/20);
        if (isset($_GET['status'])){
            switch($_GET['status']){
                case 'Active':
                    $totalPage = ceil($activeAmount/20);
                    break;
                case 'Inactive':
                    $totalPage = ceil($inactiveAmount/20);
                    break;
                case 'Suspended':
                    $totalPage = ceil($suspendedAmount/20);
                    break;
                case 'Terminated':
                    $totalPage = ceil($terminatedAmount/20);
                    break;
                case 'Guest':
                    $totalPage = ceil($guestAmount/20);
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
?>