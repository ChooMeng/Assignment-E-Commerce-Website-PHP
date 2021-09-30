<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])||!isset($_GET["page"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../../settings.php';
    //Department List
    $departmentArr = array(""=>"--Selected One--","IT"=>"Information Technology","Acc"=>"Accounting","Admin"=>"Administration","CS"=>"Customer Service","Finance"=>"Finance","HR"=>"Human Resources","MA"=>"Marketing & Advertising","Production"=>"Production","Sales"=>"Sales","Shipping"=>"Shipping");
    echo '<table id="staffList">';
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
    //Staff Status Array
    $statusArr=array("Active","Suspended","Terminated");
    //Staff data type Array
    $dataArr = array("staffID"=>"ID","name"=>"Name","email"=>"Email","position"=>"Position","lastLogin"=>"Last Login","status"=>"Status");
    $dataWidthArr = array("staffID"=>"5%","name"=>"18.5%","email"=>"21.5%","position"=>"18%","lastLogin"=>"14%","status"=>"4");
    $status = "%";
    if (isset($_GET['status'])){
        $status = $_GET['status'];
        if (!in_array($status, $statusArr)){
            $status ="%";
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
            ',$dataWidthArr[$key],$index,$status,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
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
    //Read all staff data
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $statement = $sort=="NA"?"ORDER BY staffID DESC":"ORDER BY $sort $sortOrder";
        $statusStatement = $status == "NA"?"":"WHERE status LIKE '$status'";
        $min_result = ($index-1)*LIST_PER_PAGE;
        if (empty($search)){
            $sql = "SELECT staffID, name, email, department,position,lastlogin,status from staff $statusStatement $statement LIMIT $min_result,".LIST_PER_PAGE;
        }else{
            $sql = "SELECT * from staff WHERE status LIKE '$status' AND (staffID LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%' OR position LIKE '%$search%' OR lastLogin LIKE '%$search%' OR status LIKE '%$search%' OR department LIKE '%$search%') $statement";
        }
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $department = $GLOBALS['departmentArr'];
            while($row = $result->fetch_object()){
                printf("
                       <tr id='%s' class='staffData'>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s<br>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td class='action'>
                                <button id='details' onclick=\"location.href='detailStaff.php?staffId=%s'\">Details</button><br>
                                <button id='modify' onclick=\"location.href='modifyStaff.php?staffId=%s'\">Modify</button><br>
                                <button id='remove' onclick='openBox(\"%s\")'>Terminate</button>
                            </td>
                        </tr>
                ",$row->staffID,$row->staffID,$row->name,$row->email,$department[$row->department],$row->position,empty($row->lastlogin)?"No Login Record":$row->lastlogin,$row->status,$row->staffID,$row->staffID,$row->staffID);

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
    echo '</table>';
    if (empty($search)){
        //Get amount of each client status
        $totalAmount = $activeAmount = $suspendedAmount = $terminatedAmount= 0;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT status, count(*) as total from staff GROUP BY status";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    switch($row->status){
                       case 'Active':
                            $activeAmount = $row->total;
                            break;
                        case 'Suspended':
                            $suspendedAmount = $row->total;
                            break;
                        case 'Terminated':
                            $terminatedAmount= $row->total;
                            break;
                    }
                    $totalAmount+=$row->total;
                }
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
                case 'Active':
                    $totalPage = ceil($activeAmount/20);
                    break;
                case 'Suspended':
                    $totalPage = ceil($suspendedAmount/20);
                    break;
                case 'Terminated':
                    $totalPage = ceil($terminatedAmount/20);
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
