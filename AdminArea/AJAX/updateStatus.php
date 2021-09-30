<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["orderID"])||!isset($_GET["statusApply"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $statusApply = antiExploit($_GET["statusApply"]);
    $status = array("Pending","Shipping","Delivering","Completed","Canceled");
    if(!in_array($statusApply, array_values($status))){
        $_SESSION["errorStatus"] = true; 
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $orderID = $con->real_escape_string($orderID);
        $statusApply = $con->real_escape_string($statusApply);
        $sql = "UPDATE orders SET status = ? WHERE orderID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss',$statusApply ,$orderID);
        $stmt->execute();
        createAuditLog("Order","Updated $orderID order status","$orderID order status has been succesful changed to $statusApply.");
        $_SESSION["updated"] = true; 
    }

