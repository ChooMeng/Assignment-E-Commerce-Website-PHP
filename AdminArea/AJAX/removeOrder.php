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
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from orders o, orderproduct op WHERE o.orderID = '$orderID' AND o.orderID = op.orderID";
        $currentProduct = array();
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $oldStatus = $row->status;
                $currentProduct[$row->productID] = $row->quantity;
            }
            if ($result->num_rows ==0){
                $oldStatus = "Pending";
            }
        }
        foreach($currentProduct as $key => $value){
            updateStockQuantity($key, $value);
        }
        $result->free();
        $orderID = $con->real_escape_string($orderID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE orders SET status = ?, oldStatus=? WHERE orderID = ?";
        $stmt = $con->prepare($sql);
        $status = "Removed";
        $stmt->bind_param('sss',$status, $oldStatus ,$orderID);
        $stmt->execute();
        createAuditLog("Order","Removed $orderID order details","$orderID order details has been succesful removed. <br>Reason: $reason");
        $_SESSION["removed"] = true; 
    }
