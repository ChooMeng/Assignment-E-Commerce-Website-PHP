<?php
    $dataArr = array("supportID"=>"Support ID","clientID"=>"Customer Name","subject"=>"Subject","createdTime"=>"Created Time","responserID"=>"Last Reply"
        ,"status"=>"Status","priority"=>"Priority");
    $dataWidthArr = array("supportID"=>"10%","clientID"=>"15%","subject"=>"15%","createdTime"=>"15%","responserID"=>"15%","status"=>"10%","priority"=>"10%");
    //Display succesful restore support tickets message
    if(isset($_SESSION["restored"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Restore the Support Tickets.");}, 100);</script>';
        unset($_SESSION["restored"]);
    }
    //Display invalid support ticket id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid Support ID.");}, 100);</script>';
    }
     //Count total support amount of each status
    $totalCount = 0;
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
    //Display support list
    function supportList(){
        //Display support list
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
            $statement = $sort=="NA"?"ORDER BY supportID DESC":"ORDER BY $sort $sortOrder";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT supportID, s.clientID, name, subject,createdTime,s.status,s.priority,responserID,m.time "
                        . "FROM support s JOIN client c ON c.clientID = s.clientID JOIN message m ON s.lastResponse = m.messageID WHERE s.status = 'Removed' $statement LIMIT $min_result,".LIST_PER_PAGE;
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
                        <button id='restore' onclick='openBox(\"%s\")'>Restore</button><br>
                   </td>
                   </tr>
                ",$row->supportID,$row->supportID,$row->clientID,$row->name,$row->subject,$row->createdTime,getReplyRecipient($row->responserID),$row->time,$row->status,$row->priority,$row->supportID);
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
    