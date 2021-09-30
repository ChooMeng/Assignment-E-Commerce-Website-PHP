<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])||!isset($_GET["page"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../../settings.php';
    echo '<table id="supportList">';
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        if ($_GET['total']>0){
            if ($index > ceil($_GET['total']/20.00)){
                $index = ceil($_GET['total']/20.00);
            }
        }
    }else{
        $index = 1;
    }
    //Status list
    $status = array("Open","Pending","Resolved","Closed","Canceled");
    $dataArr = array("supportID"=>"Support ID","clientID"=>"Customer Name","subject"=>"Subject","createdTime"=>"Created Time","responserID"=>"Last Reply"
        ,"status"=>"Status","priority"=>"Priority");
    $dataWidthArr = array("supportID"=>"10%","clientID"=>"15%","subject"=>"15%","createdTime"=>"15%","responserID"=>"15%","status"=>"10%","priority"=>"10%");
    $statusA = "%";
    if (isset($_GET['status'])){
        $statusA = $_GET['status'];
        if (!in_array($statusA, $status)){
            $statusA ="%";
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
    echo "<tr>";
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
    if (empty($search)){
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&status=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$statusA,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
    }else{
        $dataCount = 0;
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="sort(%d)">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$dataCount,$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
            $dataCount++;
        }
    }
    echo '<th width="10%" style="cursor:default;">Action</th></tr>';
    //Read all support data
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $statement = $sort=="NA"?"ORDER BY createdTime DESC":"ORDER BY $sort $sortOrder";
        $statusStatement = $statusA == "NA"?"WHERE s.status != 'Removed'":"WHERE s.status LIKE '$statusA' AND s.status != 'Removed'";
        $min_result = ($index-1)*LIST_PER_PAGE;
        if (empty($search)){
            $sql = "SELECT supportID, s.clientID, name, subject,createdTime,s.status,s.priority,responserID,m.time "
                    . "FROM support s JOIN client c ON c.clientID = s.clientID JOIN message m ON s.lastResponse = m.messageID $statusStatement $statement LIMIT $min_result,".LIST_PER_PAGE;
        }else{
            $sql = "SELECT supportID, s.clientID, name, subject,createdTime,s.status,s.priority,responserID,m.time "
                    . "FROM support s JOIN client c ON c.clientID = s.clientID JOIN message m ON s.lastResponse = m.messageID AND s.status != 'Removed' WHERE s.status LIKE '$statusA' AND"
                    . "(supportID LIKE '%$search%' OR s.clientID LIKE '%$search%' OR name LIKE '%$search%' OR subject LIKE '%$search%' "
                    . "OR createdTime LIKE '%$search%' OR s.status LIKE '%$search%' OR priority LIKE '%$search%' OR responserID LIKE '%$search%' OR time LIKE '%$search%') "
                    . "$statement";
        }
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                printf("
                <tr id='%s' class='supportData'>
               <td>%s</td>
               <td>%s<br>%s</td>
               <td>%s</td>
               <td>%s</td>
               <td>%s<br/><br/>%s</td>
               <td>%s</td>
               <td>%s</td>
               <td>
                    <button id='view' onclick=\"location.href='viewSupport.php?supportId=%s'\">View</button>
                    <button id='update' onclick='openSupportBox(\"%s\")'>Update</button><br>
                    <button id='modify' onclick=\"location.href='modifySupport.php?supportId=%s'\">Modify</button>
                    <button id='remove' onclick='openBox(\"%s\")'>Remove</button><br>
               </td>
               </tr>
            ",$row->supportID,$row->supportID,$row->clientID,$row->name,$row->subject,$row->createdTime,getReplyRecipient($row->responserID),$row->time,$row->status,$row->priority,$row->supportID,$row->supportID,$row->supportID,$row->supportID);
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
        //Get amount of each support status
        //Count total ticket amount of each status
        $countOpen = $countPending = $countResolved = $countClosed = $countCanceled=$countRemoved =$totalCount = 0;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT status, count(*) as total from support GROUP BY status";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    switch($row->status){
                        case 'Open':
                            $countOpen = $row->total;
                            break;
                        case 'Pending':
                            $countPending = $row->total;
                            break;
                        case 'Resolved':
                            $countResolved = $row->total;
                            break;
                        case 'Closed':
                            $countClosed = $row->total;
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
            $lastReply = "$name<br/>Customer";
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
            $lastReply = "$name<br/>$department<br/>$position";
        }
        return $lastReply;
    }
    

