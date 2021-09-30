<?php
    require 'TempData/clients.php';
    require 'TempData/orders.php';
    //Verify clientID whether it is valid
    if (!isset($_GET['clientId'])){
        setcookie("error","clientId",time()+1,"client.php");
        header('Location: client.php');
    }
    //States List
    $states = array(
        ""=>"--Selected One--",
        "JH" => "Johor",
        "KD" => "Kedah",
        "KT" => "Kelantan",
        "KL" => "Kuala Lumpur",
        "LB" => "Labuan",
        "MK" => "Melaka",
        "NS" => "Negeri Sembilan",
        "PH" => "Pahang",
        "PN" => "Penang",
        "PR" => "Perak",
        "PL" => "Perlis",
        "PJ" => "Putrajaya",
        "SB" => "Sabah",
        "SW" => "Sarawak",
        "SG" => "Selangor",
        "TR" => "Terengganu"
    );
    $clientID = $_GET['clientId'];
    function details(){ //Display client details
        global $clientID;
        //Read client details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from client WHERE clientID = '$clientID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                        <p id="lastUpdate">Last update: %s</p>
                        <p id="clientId">CLIENT ID: %s</p>
                        <div class="rightSide">
                            <p class="field">Username</p>
                            <p class="data">%s</p>
                            <p class="field">Contact Number</p>
                            <p class="data">%s</p>
                            <p class="field">Birth Date</p>
                            <p class="data">%s</p>
                            <p class="field">Last Login</p>
                            <p class="data">%s</p>
                            <p class="field">IP Address</p>
                            <p class="data">%s</p>
                        </div>
                        <div class="leftSide">
                            <p class="field">Name</p>
                            <p class="data">%s</p>
                            <p class="field">Gender</p>
                            <p class="data">%s</p>
                            <p class="field">Email</p>
                            <p class="data">%s</p>
                            <p class="field">Payment Method</p>
                            <p class="data">%s</p>
                            <p class="field">Join Date</p>
                            <p class="data">%s</p>
                            <p class="field">Account Status</p>
                            <p class="data">%s</p>
                            <br>
                            <br>
                        </div>  
                    ',$row->lastUpdate,$clientID,$row->userName,$row->contactNumber,$row->birthDate,empty($row->lastLogin)?"Not logged in":$row->lastLogin,
                            empty($row->ipAddress)?"Not logged in":$row->ipAddress,$row->name,$row->gender=="M"?"Male":"Female",$row->email,$row->paymentMethod,$row->joinDate,$row->status);
                }
                if ($result->num_rows ==0){
                    setcookie("error","clientId",time()+1,"client.php");
                    header('Location: client.php');
                }
            }
        }
    }
    function displayAddress(){ //Display client addresses
        global $clientID, $states;
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from client c, clientaddress ca, address a WHERE c.clientID = ca.clientID AND ca.clientID = '$clientID' AND ca.addressID = a.addressID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf("
                       <tr>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td><input type='radio' %s disabled ></input></td>
                       </tr>
                    ",$row->address,$row->zipCode,$row->city,$states[$row->state], $row->defaultAddress==$row->addressID?"checked":"");
                }
            }
        }
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from orders WHERE clientID = '$clientID' AND status != 'Removed'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $totalAmount=$result->num_rows;
        }
        $result->free();
        $con->close();
    }
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        if ($index > ceil($totalAmount/20.00)){
            $index = ceil($totalAmount/20.00);
        }
    }else{
        $index = 1;
    }
    //Client data type Array
    $dataArr = array("orderID"=>"Order ID","purchasedTime"=>"Purchased Time","orderNo"=>"Order No","productAmount"=>"Product Amount","totalAmount"=>"Total","status"=>"Status");
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
    function orderDetails(){ //Display client order details
        global $dataArr,$sort,$sortOrder,$index,$status,$clientID;
        echo "<tr>";
        foreach($dataArr as $key => $value){
            printf('
                <th onclick="location.href=\'?page=%s&clientId=%s&sort=%s&sortOrder=%s#orderList\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$index,$clientID,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
        echo '<th width="10%" style="cursor:default;">Action</th></tr>';
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $statement = $sort=="NA"?"ORDER BY orderID DESC":"ORDER BY $sort $sortOrder";
            $min_result = ($index-1)*LIST_PER_PAGE;
            $sql = "SELECT * from orders WHERE clientID = '$clientID' AND status != 'Removed' $statement LIMIT $min_result,".LIST_PER_PAGE;
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
        }

    }
    //Display the page number button
    function displayPageNumber(){
        $totalAmount = $GLOBALS['totalAmount'];
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

