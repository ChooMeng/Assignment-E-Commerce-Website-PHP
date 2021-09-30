<?php
    include 'Function/helper.php';
    //Status List
    $status = array("Pending","Shipping","Delivering","Completed","Canceled");
    //Payment List
    $payments = array(
        ""=>"--Selected One--",
        "Paypal"=>"Paypal",
        "CreditCard"=>"CreditCard",
        "TouchNGo" => "TouchNGo"
    );
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
    //Display state list
    function stateList(){
        foreach($GLOBALS['states'] as $key => $value){
            $select ="";
            if (isset($_POST["state"])&&$_POST["state"]==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display payment list
    function paymentList(){
        foreach($GLOBALS['payments'] as $key => $value){
            $select = "";
            if (isset($_POST["payment"])&&$_POST["payment"]==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display status list
    function statusList(){
        foreach($GLOBALS['status'] as $value){
            $select = "";
            if (isset($_POST["status"])&&$_POST["status"]==$value){
                $select = "selected";
            }
            printf("<option value='$value'>$value");
        }
    }
    //Display existing address
    function displayAddress(){
        global $states;
        $clientID = " ";
        if (isset($_POST["clientId"])){
            $clientID = antiExploit($_POST["clientId"]);
        }
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
                    printf("
                        <tr id='a%s'>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td><input id='b%s' name='default' value='%d' type='radio' %s></input></td>
                        </tr>
                     ",$row->addressID,$row->address,$row->zipCode,$row->city,$states[$row->state],$row->addressID,$row->addressID, $row->defaultAddress==$row->addressID?"checked":"");
                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='6'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }
                echo "</table>";
            }
        }

    }
    $error = array();
    //Display error icon
    function displayError(){
        echo "<img src='../Media/error.png'>";
    }
    //Display POST product details 
    function displayProduct(){
        if (isset($_POST['productId'])){
            $productId = $_POST['productId'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            $unitPrice = $_POST['unitPrice'];
            $countQuantity = count($quantity)-1;
            for ($n = 0;$n < $countQuantity;$n++){
                if (!empty($productId[$n])){
                    printf('
                    <tr class="productData">
                        <td><script>document.write(count);count=count+1;</script></td>
                        <td><input list="productList%d" type="text" name="productId[]" id="productId" size="3" maxlength="5" oninput="createRow(this), suggestProduct(this);" autocomplete="off" value="%s"><dataList id="productList%d" name="productList%d"></dataList></td>
                        <td><input type="text" name="description[]" id="description" readonly value="%s"></td>
                        <td><input style="text-align:center;" type="number" name="quantity[]" id="quantity" step="1" min="0" max="999" value="%d" oninput="calculateTotal(this),createRow(this);"></td>
                        <td><input style="text-align:center;" type="number" name="unitPrice[]" id="unitPrice" step="0.05" min="0" max="99999" value="%.2f" readonly></td>
                        <td>%.2f</td>
                    </tr>
                    ',$n,$productId[$n],$n,$n,$description[$n],$quantity[$n],$unitPrice[$n],(empty($quantity[$n])?0:$quantity[$n])*(empty($unitPrice[$n])?0:$unitPrice[$n]));
                }
                
            }
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
                    if (isOrderNoExist($orderNo)){
                        array_push($error,"<b>Order No</b> is unavailable");
                        $errorOrderNo = true;
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
        if (!empty($_POST["addressType"])){
            $addressType = antiExploit($_POST["addressType"]);
        }
        if (empty($_POST["addressType"])||$addressType == "new"){
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
                if (strlen($zipCode)!=5){
                    array_push($error,"<b>Zip Code</b> must 5 characters.");
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
        }
        if (!empty($_POST["addressType"])&&$addressType == "exist"){
            if (empty($_POST["default"])){
                array_push($error,"Please select a <b>Default Address</b>.");
                $errorDefault = true;
            }else{
                $default = antiExploit($_POST["default"]);
                
            }
        }
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
                $longProductId = true; //mean the product id is too short if true
            }
        }
        if (isset($emptyProductId)){
            array_push($error,"<b>Product ID</b> cannot empty.");
        }
        if (isset($longProductId)){ //mean the product id is too short if true
             array_push($error,"<b>Product ID</b> must 5 characters.");
        }
        if (isset($emptyQuantity)){
            array_push($error,"<b>Product Quantity</b> must more than 0.");
        }
        for ($i = 0;$i<count($productId)-1;$i++){
            $stockLeft = enoughQuantity($productId[$i], $quantity[$i]);
            if ((int)$stockLeft<($quantity[$i])){
                array_push($error,"<b>$productId[$i]</b> not enough stock. Stock Left: $stockLeft");
            }
        }
        $discount = 0;
        if (!empty($_POST['discount'])){
            $discount = $_POST['discount'];
            if ($discount < 0|| $discount >100){
                array_push($error,"<b>Discount</b> must 0 percent or above.");
            }
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
                if ($addressType=="new"){
                    $sql = "SELECT * from address";
                    if(!$result = $con->query($sql)){
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }else{
                        $addressID = $result->num_rows+1;
                        $addressID = checkAddress($clientID, $address, $city, $zipCode, $state, $addressID);
                    }
                    
                }else{
                    $addressID = $default;
                }
                $sql = "SELECT * from orders";
                if($result = $con->query($sql)){
                    $orderCount = $result->num_rows+1;
                    $result -> free();
                    $orderID = "O".str_repeat("0",9-strlen($orderCount)).$orderCount;
                    $date = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO orders(orderID,orderNo,clientID,status,payment,purchasedTime,billingAddress,lastUpdate,discount,productAmount,totalPrice) "
                            . "VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $con -> prepare($sql);
                    $stmt ->bind_param('sssssssssss', $orderID,$orderNo,$clientID,$statusA,$payment,$date,$addressID,$date,$discount,$productAmount,$totalPrice);
                    $errorExist = false;
                    if ($stmt->execute()){
                        $stmt->free_result();
                        for ($i = 0;$i<count($productId)-1;$i++){
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
                            createAuditLog("Order","Create new order","Order with orderID $orderID created.");
                            $stmt->free_result();
                            $con->close();
                            header('HTTP/1.1 307 Temporary Redirect');
                            header('Location: orders.php');
                        }
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
            }
        }else{
            //Display error message
            echo "<ul class='error'>";
            foreach($error as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
        }
    }