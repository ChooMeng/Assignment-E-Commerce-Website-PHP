<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["orderID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../../settings.php';
    include '../Function/helper.php';
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $orderID = antiExploit($_GET["orderID"]);
    $defaultProduct = array();
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        $databaseError = $con->connect_error;
    }else{
        $sql = "SELECT o.productID, quantity from orderproduct o JOIN productlist p ON o.productID = p.Product_ID WHERE o.orderID = '$orderID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $defaultProduct[$row->productID] = $row->quantity;
            }
        }
    }
    $valid = false;
    foreach($defaultProduct as $key => $value){
        $stockLeft = enoughQuantity($key, $value);
        if ((int)$stockLeft>$value){
            $valid = true;
        }
    }
    
    $reason = antiExploit($_GET["reason"]);
    if ($valid){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    $oldStatus = $row->oldStatus;
                }
                if ($result->num_rows ==0){
                    $oldStatus = "Pending";
                }
            }
            $orderID = $con->real_escape_string($orderID);
            $reason = $con->real_escape_string($reason);
            $sql = "UPDATE orders SET status = ? WHERE orderID = ?";
            $stmt = $con->prepare($sql);
            foreach($defaultProduct as $key => $value){
                updateStockQuantity($key, -$value);
            }
            
            $stmt->bind_param('ss',$oldStatus ,$orderID);
            $stmt->execute();
            createAuditLog("Order","Restored $orderID order ","$orderID order has been succesful restored. <br>Reason: $reason");
            $_SESSION["restored"] = true; 
        }
    }
        
