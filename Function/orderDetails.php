<?php
    $clientID = $_SESSION["clientId"];
    include 'AdminArea/TempData/clients.php';
    include 'settings.php';
    $cli = $client[$clientID];
    $subtotal = 0;
    $discount = 0;
    $status = "";
    $orderID = $_GET['orderId'];
    //Verify orderID whether it is valid
    if (!isset($_GET['orderId'])){
        setcookie("error","orderId",time()+1,"viewOrder.php");
        header('Location: viewOrder.php');
    }
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
    
    function displayOrderDetail(){
        global $status,$clientID;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $orderID = $_GET['orderId'];
            $sql = "SELECT * from orders WHERE orderID = '$orderID' AND clientID = '$clientID' AND status != 'Removed'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object())
                {
                    printf('
                        <table id="orderInformation" class="tableSect" cellspacing="10" cellpadding="10">
                            <tr>
                                <td id="orderInfo" valign="top">
                                    <table>
                                        <tr><td><p>Order ID</p></td><td><span>%s</span><br/></tr>
                                        <tr><td><p>Order No</p></td><td><span>%s</span></td></tr>
                                        <tr><td><p>Placed on</p></td><td><span>%.10s</span></td></tr>
                                        <tr><td><p>Payment method</p></td><td><span>%s</span></td></tr>
                                        <tr><td><p>Status</p></td><td><span>%s</span></td></tr>
                                    </table>
                                </td>
                                <td valign="top">
                                    <p id="totalPayment"><strong>Total: </strong>RM %.2f</p>
                                </td>
                            </tr>
                        </table>
                    ', $row->orderID, $row->orderNo, $row->purchasedTime, $row->payment, $row->status, $row->totalPrice);
                    
                }
                if ($result->num_rows==0){
                    echo '<script>location.href="viewOrder.php"</script>';
                }
            }
            $result->free();
        }
        $con->close();
    }

    function displayOrderProducts(){
        global $clientID, $orderID, $subtotal;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * FROM orders o, orderproduct op, productlist p 
                    WHERE o.orderID = '$orderID' AND op.orderID = o.orderID AND p.product_ID = op.productID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object())
                {
                    printf('
                    <tr>
                        <td style="text-align:center;"><img src="uploads/%s" class="productImg"></td>
                        <td valign="top">%s</td>
                        <td valign="top">RM %.2f</td>
                        <td valign="top">%d</td>
                        <td valign="top">RM %.2f</td>
                    </tr>
                    ', $row->Image, $row->Name, $row->Price, $row->quantity, $subtotal += ($row->quantity*$row->Price));
                }
            }
        }
    }

    function displayAddress(){
        global $clientID, $states;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $orderID = $_GET['orderId'];
            $sql = "SELECT * FROM orders o, address a, clientAddress ca, client c
                    WHERE o.orderID = '$orderID' AND o.billingAddress = a.addressID AND ca.addressID = a.addressID AND c.clientID = ca.clientID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object())
                {
                    printf('
                    %s<br>%s, %s<br>%s<br><br>%s',
                    $row->address, $row->zipCode, $row->city, $states[$row->state], $row->contactNumber);
                }
                
            }
        }
        $result->free();
        $con->close();
    }

    function displaySummary(){ //Display client order details
        global $status,$clientID, $subtotal, $orderID;
        
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    $discount = $row->discount;
                    $total = $subtotal - ($discount / 100);
                    printf('
                        <tr><td>SubTotal</td><td>RM %.2f</td></tr>
                        <tr><td>Discount %d%%</td><td>RM %.2f</td></tr>
                        <tr><td>Total</td><td>RM %.2f</td></tr>
                    ',$subtotal, $discount, $discount/100, $total);
                }
            }
        }
        $result->free();
        $con->close();
    }

    function displayCancelOrder(){
        global $status,$clientID, $subtotal, $orderID;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    if($row->status == "Pending")
                    {
                        printf("<button type='button' id='cancelOrderBtn' onclick=\"openBox(%s,%s)\">CANCEL ORDER</button>", "'".$orderID."'", "'".$clientID."'");
                    }
                }
            }
            $result->free();
        }
        $con->close();
    }