<?php
    include 'Function/helper.php';
    //Verify orderID
    if (!isset($_GET['orderId'])){
        setcookie("error","orderId",time()+1,"orders.php");
        header('Location: orders.php');
    }
    //Display succesful terminate account message
    if(isset($_SESSION["clientAddress"])){
        if ($_SESSION["clientAddress"]){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Removed the client address.");}, 100);</script>';
            unset($_SESSION["clientAddress"]);
        }else{
            echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Cannot remove the client address.<br>Reason: Currently using on active orders <br>OR it is default address");}, 100);</script>';
            unset($_SESSION["clientAddress"]);
        }
    }
    //Status list
    $status = array("Pending","Shipping","Delivering","Completed","Canceled");
    $orderID = $_GET['orderId'];
    if ((isset($_GET['type'])&&$_GET['type']=="information")||!isset($_GET['type'])){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    if ($row->status == "Removed"){
                        setcookie("error","orderId",time()+1,"orders.php");
                        header('Location: orders.php');
                        break;
                    }
                   $orderNo = $row->orderNo;
                   $clientID = $row->clientID;
                   $payment = $row->payment;
                   $billingAddress = $row->billingAddress;
                   $statusA = $row->status;
                   $discount = $row->discount;
                   $productAmount = $row->productAmount;
                   $totalPrice = $row->totalPrice;
                }
                if ($result->num_rows ==0){
                    setcookie("error","orderId",time()+1,"orders.php");
                    header('Location: orders.php');
                }
            }
            $result->free();
        }
        $con->close();
    }else{
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    if ($row->status == "Removed"){
                        setcookie("error","orderId",time()+1,"orders.php");
                        header('Location: orders.php');
                        break;
                    }
                   $statusA = $row->status;
                   $discount = $row->discount;
                   $clientID = $row->clientID;
                   $billingAddress = $row->billingAddress;
                }
                if ($result->num_rows ==0){
                    setcookie("error","orderId",time()+1,"orders.php");
                    header('Location: orders.php');
                }
            }
            $result->free();
        }
        $con->close();
    }
    if (isset($_GET['type'])&&$_GET['type']=="address"&&isset($_GET['address'])){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        $addressID = $_GET['address'];
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $sql = "SELECT * from client c, address a,clientaddress ca WHERE c.clientID = ca.clientID AND a.addressID = '$addressID' AND a.addressID = ca.addressID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                   $address = $row->address;
                   $zipCode = $row->zipCode;
                   $city = $row->city;
                   $state = $row->state;
                   $defaultAddress = $row->defaultAddress;
                }
                if ($result->num_rows ==0){
                    setcookie("error","orderId",time()+1,"orders.php");
                    header('Location: orders.php');
                }
            }
            $result->free();
        }
        $con->close();
    }
    //Check section requested and switch the section
    function check(){
        if (isset($_GET['type'])){
            $type = $_GET['type'];
            if($type=="information"){
                echo "<script>switchType('information')</script>";
            }else if($type=="products"){
                echo "<script>switchType('products')</script>";
            }else if($type=="address"){
                if (isset($_GET['address'])){
                    echo "<script>switchType('existaddress')</script>";
                }else{
                    echo "<script>switchType('address')</script>";
                }
            }else{
                echo "<script>switchType('newaddress')</script>";
            }
        }else{
            echo "<script>switchType('information')</script>";
        }
    }
    
    //Payment list
    $payments = array(
        ""=>"--Selected One--",
        "Paypal"=>"Paypal",
        "CreditCard"=>"CreditCard",
        "TouchNGo" => "TouchNGo"
    );
    //States list
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
    //Display state list
    function stateList(){
        global $clientID, $state;
        foreach($GLOBALS['states'] as $key => $value){
            $select ="";
            if (isset($_POST["state"])){
                if ($_POST["state"]==$key){
                    $select = "selected";
                }
            }else if($state==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display payment list
    function paymentList(){
        global $payment;
        foreach($GLOBALS['payments'] as $key => $value){
            $select = "";
            if (isset($_POST["payment"])){
                if ($_POST["payment"]==$key){
                    $select = "selected";
                }
            }else if ($payment==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display status list
    function statusList(){
        global $statusA;
        foreach($GLOBALS['status'] as $value){
            $select = "";
            if (isset($_POST["status"])){
                if ($_POST["status"]==$value){
                    $select = "selected";
                }
            }else if ($statusA==$value){
                $select = "selected";
            }
            printf("<option value='$value'>$value");
        }
    }
    $defaultProduct = array();
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        $databaseError = $con->connect_error;
    }else{
        $sql = "SELECT o.productID, Name, quantity,Price from orderproduct o JOIN productlist p ON o.productID = p.Product_ID WHERE o.orderID = '$orderID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $defaultProduct[$row->productID] = $row->quantity;
            }
        }
    }
    //Display product list
    function displayProduct(){
        global $subtotal,$orderID;
        $subtotal = 0;
        // Get result from post
        if (isset($_POST['productId'])){
            $productId = $_POST['productId'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            $unitPrice = $_POST['unitPrice'];
            $countQuantity = count($quantity)-1;
            for ($n = 0;$n < $countQuantity;$n++){
                $defaultProduct[$productId[$n]] = $quantity[$n];
                printf('
                    <tr class="productData">
                        <td><script>document.write(count);count=count+1;</script></td>
                        <td><input list="productList%d" type="text" name="productId[]" id="productId" size="3" maxlength="5" oninput="createRow(this); suggestProduct(this);" autocomplete="off" value="%s"><dataList id="productList%d" name="productList%d"></dataList></td>
                        <td><input type="text" name="description[]" id="description" readonly value="%s"></td>
                        <td><input style="text-align:center;" type="number" name="quantity[]" id="quantity" step="1" min="0" max="999" value="%d" oninput="calculateTotal(this),createRow(this);"></td>
                        <td><input style="text-align:center;" type="number" name="unitPrice[]" id="unitPrice" step="0.05" min="0" max="99999" value="%.2f" readonly></td>
                        <td>%.2f</td>
                    </tr>
                ',$n,$productId[$n],$n,$n,$description[$n],$quantity[$n],$unitPrice[$n],(empty($quantity[$n])?0:$quantity[$n])*(empty($unitPrice[$n])?0:$unitPrice[$n]));
            }
            //Get result from database
        }else{
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                $databaseError = $con->connect_error;
            }else{
                $sql = "SELECT o.productID, Name, quantity,Price from orderproduct o JOIN productlist p ON o.productID = p.Product_ID WHERE o.orderID = '$orderID'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $count = 0;
                        printf('
                            <tr class="productData">
                                <td><script>document.write(count);count=count+1;</script></td>
                                <td><input list="productList%d" type="text" name="productId[]" id="productId" size="3" maxlength="5" oninput="createRow(this); suggestProduct(this);" autocomplete="off" value="%s"><dataList id="productList%d" name="productList%d"></dataList></td>
                                <td><input type="text" name="description[]" id="description" readonly value="%s"></td>
                                <td><input style="text-align:center;" type="number" name="quantity[]" id="quantity" step="1" min="0" max="999" value="%d" oninput="calculateTotal(this),createRow(this);"></td>
                                <td><input style="text-align:center;" type="number" name="unitPrice[]" id="unitPrice" step="0.05" min="0" max="99999" value="%.2f" readonly></td>
                                <td>%.2f</td>
                            </tr>
                        ',$count,$row->productID,$count,$count,$row->Name,$row->quantity,$row->Price,$row->quantity*$row->Price);
                        $subtotal += ($row->quantity*$row->Price);
                        $count++;
                    }
                }
            }
                
            
            
        }
        
    }
    //Display address list
    function displayAddress(){
        global $clientID,$billingAddress;
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{
            $sql = "SELECT * from client c, clientaddress ca, address a WHERE ca.clientID = '$clientID' AND c.clientID = ca.clientID AND ca.addressID = a.addressID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    $check = "";
                    if ($row->addressID==$billingAddress){
                        $check = "checked";
                    }
                    printf("
                        <tr id='a%s'>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td><input id='b%d' name='selected' value='%d' type='radio' %s></input></td>
                            <td class='action'><button id='modify' type='button' onclick=\"location.href='modifyOrder.php?orderId=%s&type=address&address=%d'\">Modify</button><br>
                            <button type='button' id='remove' onclick=\"openBox(%s,%s)\">Remove</button>
                                    </td>
                       </tr>
                    ",$row->addressID,$row->address,$row->zipCode,$row->city,$row->state,$row->addressID,$row->addressID,$check ,$_GET['orderId'],$row->addressID,"'".$row->addressID."'","'".$row->clientID."'");
                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='6'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }
                $result->free();
                $con->close();
            }
        }
        
    }
    function checkAvailability(){
        echo "addNotification('red','SYSTEM NOT YET IMPLEMENTED')";
        
    }
    $error = array();
    //Display error icon
    function displayError(){
        echo "<img src='../Media/error.png'>";
    }
    //Display error  message
    function displayErrorMessage(){
        global $error;
        if (!empty($error)){
            echo "<ul class='error'>";
            foreach($error as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
        }
    }
    //Display database error message
    $databaseError = "";
    function displayDatabaseErrorMessage(){
        global $databaseError;
        if (!empty($databaseError)){
             echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: $databaseError</div>";
        }
       
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Check order information section
        if ($_POST["type"]=="modifyInformation"){
            if (empty($_POST["orderNo"])){
                array_push($error,"<b>Order No</b> cannot empty.");
                $errorOrderNo = true;
            }else{
                $orderNo = antiExploit($_POST["orderNo"]);
                if(strlen($orderNo)!=12){
                    array_push($error,"<b>Order No</b> must 12 character.");
                    $errorOrderNo = true;
                }else{
                    $format = '/^[A-Za-z0-9]{12}$/';
                    if(!preg_match($format, $orderNo)){
                        array_push($error,"<b>Order No</b> invalid order no.");
                        $errorOrderNo = true;
                    }else{
                        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                        if ($con -> connect_errno) { //Check it is the connection succesful
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                        }else{
                            $sql = "SELECT * from orders WHERE orderNo = '$orderNo'";
                            if ($result=$con->query($sql)){
                                if ($result->num_rows !=0){
                                    while($row = $result->fetch_object()){
                                        if ($row->orderID != $orderID){
                                            array_push($error,"<b>Order No</b> is unavailable.");
                                            $errorOrderNo = true;
                                        }
                                    }
                                    
                                }
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                            }
                            $result->free();
                            $con->close();
                        }
                    }
                }
            }
            if(empty($_POST["clientId"])){
                array_push($error,"<b>ClientID</b> cannot empty.");
                $errorClientId = true;
            }else{

                $clientID = antiExploit($_POST["clientId"]);
                if (strlen($clientID)!=7){
                    array_push($error,"<b>ClientID</b> client id must 7 character.");
                    $errorClientId = true;
                }else{
                    $pattern = '/^[A-Z][0-9]{6}$/';
                    if (!preg_match($pattern, $clientID)){
                        array_push($error,"<b>ClientID</b> invalid client id.");
                        $errorClientId = true;
                    }else if (!isClientIDExist($clientID)){
                        array_push($error,"<b>ClientID</b> invalid client id.");
                        $errorClientId = true;
                    }
                }

            }
            if(empty($_POST['status'])){
                array_push($error,"Please select a <b>Status</b>.");
                $errorStatus = true;
            }else{
                $statusA = antiExploit($_POST["status"]);
                if(!in_array($statusA, array_values($status))){
                    array_push($error,"Please select a <b>Status</b> Must be a valid status.");
                    $errorStatus = true;
                }
            }
            if(empty($_POST['payment'])){
                array_push($error,"Please select a <b>Payment Method</b>.");
                $errorPayment = true;
            }else{
                $payment = antiExploit($_POST["payment"]);
                if(!in_array($payment, array_keys($payments))){
                    array_push($error,"Please select a <b>Payment Method</b> Must be a valid payment method.");
                    $errorPayment = true;
                }
            }
            if (empty($error)){
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "UPDATE orders SET orderNo=?, clientID =?,status=?,payment=?,lastUpdate=? where orderID = ?";
                    $date = date('Y-m-d H:i:s');
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('ssssss',$orderNo, $clientID, $statusA, $payment,$date,$orderID);
                    $id = $_SESSION["staffId"];
                    if($stmt->execute()){
                        $id = $_SESSION["staffId"];
                        createAuditLog("Order", "Modified $orderID order information", "$id has changed $orderID order information");
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: orders.php');
                       
                    }else{
                        $databaseError = $stmt->error;
                    }
                    $stmt->free_result();
                }
            }
        }else if ($_POST["type"]=="modifyProducts"){
            //Check product list section
            $productId = $_POST['productId'];
            $quantity = $_POST['quantity'];
            $countQuantity = count($quantity)-1;
            if ($countQuantity==0){
                $errorProductId = true;
                $emptyProductId = true;
            }
            $currentProduct = array();
            for ($n = 0;$n < $countQuantity;$n++){
                if ($quantity[$n]>0){
                    if (empty($productId[$n])){
                        $errorProductId = true;
                        $emptyProductId = true;
                    }else{
                        if (isProductExist($productId[$n])){
                            if (in_array($productId[$n], $currentProduct)){
                                array_push($error,"Cannot contains two or more same <b>Product ID</b>");
                                $errorProductId = true;
                                break;
                            }else{
                                array_push($currentProduct, $productId[$n]);
                            }
                        }else{
                            array_push($error,"<b>Product ID</b> not existing.");
                            $errorProductId = true;
                            break;
                        }
                    }
                }else{
                    if (!empty($productId[$n])){
                        $errorQuantity = true;
                        $emptyQuantity = true;

                    }

                }
                if (!empty($productId[$n])&&strlen($productId[$n])!=5){
                    $errorProductId = true;
                    $longProductId = true;
                }
            }
            if (isset($emptyProductId)){
                array_push($error,"<b>Product ID</b> cannot empty.");
            }
            if (isset($emptyQuantity)){
                array_push($error,"<b>Product Quantity</b> must more than 0.");
            }
            if (isset($longProductId)){
                 array_push($error,"<b>Product ID</b> must 5 characters.");
            }
            $discount = 0;
            if (!empty($_POST['discount'])){
                $discount = $_POST['discount'];
                if ($discount < 0|| $discount >100){
                    array_push($error,"<b>Discount</b> must 0 percent or above.");
                }
            }
            foreach($defaultProduct as $key => $value){
                updateStockQuantity($key, $value);
            }
            for ($n = 0;$n<count($productId);$n++){
                $stockLeft = enoughQuantity($productId[$n], $quantity[$n]);
                if ((int)$stockLeft<($quantity[$n])){
                    array_push($error,"<b>$productId[$n]</b> not enough stock. Stock Left: $stockLeft");
                }
            }
            foreach($defaultProduct as $key => $value){
                updateStockQuantity($key, -$value);
            }
            if (empty($error)){
                $productAmount = 0;
                $totalPrice = 0;
                for ($n = 0;$n < count($productId);$n++){
                    if (!empty($productId[$n])){
                        $productAmount++;
                        $totalPrice += ($_POST['unitPrice'][$n]*$quantity[$n]);
                    }
                }
                $totalPrice = $totalPrice-($totalPrice * ($discount/100));
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "UPDATE orders SET lastUpdate=?,productAmount = ?,discount = ?, totalPrice = ?  where orderID = ?";
                    $date = date('Y-m-d H:i:s');
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('sssss',$date,$productAmount,$discount,$totalPrice,$orderID);
                    $errorExist = false;
                    if($stmt->execute()){
                        $stmt->free_result();
                        $sql = "DELETE FROM orderproduct WHERE orderID = ?";//Insert product details into order product table
                        $stmt = $con -> prepare ($sql);
                        $stmt ->bind_param('s', $orderID);
                        if(!$stmt->execute()){
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }else{
                            foreach($defaultProduct as $key => $value){
                                updateStockQuantity($key, (int)$value);
                            }
                            for ($i = 0;$i<count($productId)-1;$i++){
                                $stmt->free_result();
                                $sql = "INSERT INTO orderproduct(orderID, productID, quantity) VALUES (?,?,?)";//Insert product details into order product table
                                $stmt = $con -> prepare ($sql);
                                $stmt ->bind_param('sss', $orderID,$productId[$i],$quantity[$i]);
                                if (!$stmt->execute()){
                                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                    $errorExist = true;
                                    break;
                                }else{
                                    $stmt->free_result();
                                    updateStockQuantity($productId[$i], -$quantity[$i]);
                                }
                            }
                            if (!$errorExist){
                                $id = $_SESSION['staffId'];
                                createAuditLog("Order", "Modified $orderID order information", "$id has changed $orderID order information");
                                $stmt->free_result();
                                $con->close();
                                header('HTTP/1.1 307 Temporary Redirect');
                                header('Location: orders.php');
                            }
                        }
                    }else{
                        $databaseError = $stmt->error;
                    }
                    $stmt->free_result();
                }
            }
        }else if ($_POST["type"]=="modifyAddress"){
            //Check set default address section
            if(!isset($_POST['selected'])){
                array_push($error,"Please select an <b>Address</b>.");
                $errorSelected = true;
            }else{
                $billingAddress = antiExploit($_POST['selected']);
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    $databaseError = $con->connect_error;
                    $errorSelected = true;
                }else{
                    //Valid it is correct address and is address of that client 
                    $sql = "SELECT * from clientaddress WHERE addressID = '$billingAddress' AND clientID = '$clientID'";
                    if(!$result = $con->query($sql)){
                        $databaseError = $con->error;
                        $errorSelected = true;
                    }else{
                        if ($result->num_rows ==0){
                            array_push($error,"Please select a valid <b>Address</b>.");
                            $errorSelected = true;
                        }
                    }
                }
            }
            if (empty($error)){
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    $databaseError = $con->connect_error;
                }else{
                    //Update the default address
                    $sql = "UPDATE orders SET billingAddress=?,lastUpdate = ? WHERE orderID=?";
                    $date = date('Y-m-d H:i:s');
                    $stmt = $con->prepare($sql);
                    $stmt ->bind_param('sss', $billingAddress,$date, $orderID);
                    if($stmt->execute()){
                        $id = $_SESSION["staffId"];
                        createAuditLog("Order", "Changed $orderID billing address", "$id has changed $orderID billing address");
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: orders.php');
                       
                    }else{
                        $databaseError = $stmt->error;
                    }
                    $stmt->free_result();
                }
                $con->close();
            }
        }else if ($_POST["type"]=="modifyExistAddress"){
            //Check existing address section
            if(empty($_POST["address"])){
                array_push($error,"<b>Address</b> cannot empty.");
                $errorAddress = true;
            }else{
                $address = antiExploit($_POST["address"]);
            }
            if(empty($_POST["zipCode"])){
                array_push($error,"<b>Zip Code</b> cannot empty.");
                $errorZipCode = true;
            }else{
                $zipCode = $_POST["zipCode"];
                if (strlen($zipCode)>5){
                    array_push($error,"<b>Zip Code</b> cannot more than 5 characters.");
                    $errorZipCode = true;
                }else{
                    $pattern = '/^[0-9]{5}$/';
                    if(!preg_match($pattern,$zipCode)){
                        array_push($error,"<b>Zip Code</b> is of invalid format.");
                        $errorZipCode = true;
                    } 
                }

            }
            if(empty($_POST["city"])){
                array_push($error,"<b>City</b> cannot empty.");
                $errorCity = true;
            }else{
                $city = antiExploit($_POST["city"]);
                if (strlen($city)>28){
                    array_push($error,"<b>City</b> cannot more than 28 characters.");
                    $errorCity = true;
                }
            }
            if(empty($_POST['state'])){
                array_push($error,"Please select a <b>State</b>.");
                $errorState = true;
            }else{
                $state = antiExploit($_POST["state"]);
                if(!in_array($state, array_keys($states))){
                    array_push($error,"Please select a <b>State</b> Must be a valid state.");
                    $errorState = true;
                }
            }
            if (empty($error)){
                if (checkAddressExist($clientID, $address, $city, $zipCode, $state)){
                    array_push($error,"Contains same <b>client address</b>.");
                }else{
                    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                    if ($con -> connect_errno) { //Check it is the connection succesful
                        $databaseError = $con->connect_error;
                    }else{
                        $addressID = $_GET['address'];
                        $date = date('Y-m-d H:i:s');
                        //Update the member details updateTime
                        $sql = "UPDATE client SET lastUpdate = ? WHERE clientID=?";
                        $stmt = $con->prepare($sql);
                        $stmt ->bind_param('ss',$date, $clientID);
                        if($stmt->execute()){
                            $stmt->free_result();
                            $sql = "UPDATE address set address = ?,zipCode = ?,city = ?,state=? WHERE addressID = ?";//Insert address detail into address table
                            $stmt = $con -> prepare ($sql);
                            $stmt ->bind_param('sssss', $address,$zipCode,$city,$state,$addressID);
                            if ($stmt->execute()){
                                $stmt->free_result();
                                createAuditLog("Client","Changed $clientID exist client address","Client address of addressID $addressID for ClientID $clientID changed.");
                                $result->free();
                                $con->close();
                                header('HTTP/1.1 307 Temporary Redirect');
                                header('Location: orders.php');
                            }else{
                                $databaseError = $stmt->error;
                            }
                            $stmt->free_result();
                        }else{
                            $databaseError = $stmt->error;
                        }
                        $stmt->free_result();
                        $con->close();
                    }
                }
            }
        }else{
            //Check new address section
            if(empty($_POST["address"])){
                array_push($error,"<b>Address</b> cannot empty.");
                $errorAddress = true;
            }else{
                $address = antiExploit($_POST["address"]);
            }
            if(empty($_POST["zipCode"])){
                array_push($error,"<b>Zip Code</b> cannot empty.");
                $errorZipCode = true;
            }else{
                $zipCode = $_POST["zipCode"];
                if (strlen($zipCode)>5){
                    array_push($error,"<b>Zip Code</b> cannot more than 5 characters.");
                    $errorZipCode = true;
                }else{
                    $pattern = '/^[0-9]{5}$/';
                    if(!preg_match($pattern,$zipCode)){
                        array_push($error,"<b>Zip Code</b> is of invalid format.");
                        $errorZipCode = true;
                    } 
                }
            }
            if(empty($_POST["city"])){
                array_push($error,"<b>City</b> cannot empty.");
                $errorCity = true;
            }else{
                $city = antiExploit($_POST["city"]);
                if (strlen($city)>28){
                    array_push($error,"<b>City</b> cannot more than 28 characters.");
                    $errorCity = true;
                }
            }
            if(empty($_POST['state'])){
                array_push($error,"Please select a <b>State</b>.");
                $errorState = true;
            }else{
                $state = antiExploit($_POST["state"]);
                if(!in_array($state, array_keys($states))){
                    array_push($error,"Please select a <b>State</b> Must be a valid state.");
                    $errorState = true;
                }
            }
            
            if (empty($error)){
                if (checkAddressExistAndInsert($clientID, $address, $city, $zipCode, $state)){
                    array_push($error,"Contains same <b>client address</b>.");
                }else{
                    header('HTTP/1.1 307 Temporary Redirect');
                    header('Location: orders.php');
                }
                
            }
        }
    }