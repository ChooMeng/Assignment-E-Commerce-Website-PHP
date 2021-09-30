<?php
    require 'Function/helper.php';
    //Display invalid cliend id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid auditID.");}, 100);</script>';
    }
    //Count total audit log amount of each type
    $countClients = $countProducts = $countOrders = $countStaffs = $countSupports = $totalCount = 0;
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT type, count(*) as total from auditlog GROUP BY type";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
               switch($row->type){
                   case 'Client':
                        $countClients = $row->total;
                        break;
                    case 'Product':
                        $countProducts = $row->total;
                        break;
                    case 'Order':
                        $countOrders = $row->total;
                        break;
                    case 'Staff':
                        $countStaffs = $row->total;
                        break;
                    case 'Support':
                        $countSupports = $row->total;
                        break;
                }
                $totalCount+=$row->total;
            }

        }
        $result->free_result();
        $con->close();
    }
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        if ($totalCount>0){
            if ($index > ceil($totalCount/20.00)){
                $index = ceil($totalCount/20.00);
            }
        }
        
    }else{
        $index = 1;
    }
    //Audit Type Array
    $typeArr=array("Client","Product","Order","Staff","Support");
    //audit data type Array
    $dataArr = array("logtime"=>"Time","auditID"=>"Audit ID","staffID"=>"Staff","type"=>"Type","changes"=>"Changes");
    $dataWidthArr = array("logtime"=>"15%","auditID"=>"12%","staffID"=>"16%","type"=>"10%","changes"=>"37%");
    $type = "NA";
    if (isset($_GET['type'])){
        $type = $_GET['type'];
        if (!in_array($type, $typeArr)){
            $type ="NA";
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
    function auditDetails(){
        global $dataArr,$sort,$sortOrder,$index,$type,$dataWidthArr;
        echo "<tr>";
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&type=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$type,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
        echo '<th width="10%" style="cursor:default;">Action</th></tr>';
        //Read all audit log
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $statement = $sort=="NA"?"ORDER BY logTime DESC":"ORDER BY LENGTH($sort) $sortOrder, $sort $sortOrder";
            $typeStatement = $type == "NA"?"":"WHERE type LIKE '$type'";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT * from auditlog $typeStatement $statement LIMIT $min_result,".LIST_PER_PAGE;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf("
                    <tr id='%s' class='auditData'>
                         <td>%s</td>
                         <td>%s</td>
                         <td>%s<br>%s</td>
                         <td>%s</td>
                         <td>%s</td>
                         <td>
                             <button id='details' onclick=\"location.href='detailAudit.php?auditId=%s'\">Details</button>
                        </td>
                    </tr>
                 ",$row->auditID,$row->logTime,$row->auditID,$row->staffID, staffIDToName($row->staffID),$row->type,$row->changes,$row->auditID);
                }
                if (($result->num_rows)==0){
                    printf("
                        <tr><td colspan='8'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                    ");
                }
            }
            
            $result->free_result();
            $con->close();
        }
    }
    //Display the page number button
    function displayPageNumber(){
        global $type,$sort,$sortOrder,$countClients,$countOrders,$countProducts,$countStaffs,$countSupports;
        $totalAmount = $GLOBALS['totalCount'];
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        $totalPage = ceil($totalAmount/20);
        if (isset($_GET['type'])){
            switch($_GET['type']){
                case 'Client':
                    $totalPage = ceil($countClients/20);
                    break;
                case 'Order':
                    $totalPage = ceil($countOrders/20);
                    break;
                case 'Product':
                    $totalPage = ceil($countProducts/20);
                    break;
                case 'Staff':
                    $totalPage = ceil($countStaffs/20);
                    break;
                case 'Support':
                    $totalPage = ceil($countSupports/20);
                    break;
            }
        }
        if ($totalPage<$currentPage){
            $currentPage = $totalPage;
        }
        if ($currentPage != 1 &&$currentPage != 0){
            $top = 1;
            echo "<a href='?page=$top&type=$type&sort=$sort&sortOrder=$sortOrder' class='backward'><i class='fas fa-backward'></i></a>";
        }
        for ($i = 1; $i <= $totalPage;$i++){
            if ($i>=$currentPage-2&&$i<=$currentPage+2){
                if ($i == $currentPage){
                    echo "<a class='current'>$i</a>";
                }else{
                    echo "<a href='?page=$i&type=$type&sort=$sort&sortOrder=$sortOrder'>$i</a>";
                }
            }
        }
        if ($currentPage != $totalPage){
            $next = $totalPage;
            echo "<a href='?page=$next&type=$type&sort=$sort&sortOrder=$sortOrder' class='forward'><i class='fas fa-forward'></i></a>";
        }
    }

