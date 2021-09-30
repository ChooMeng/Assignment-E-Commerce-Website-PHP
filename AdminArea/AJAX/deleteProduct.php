<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["productID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $productID = antiExploit($_GET["productID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $productID = $con->real_escape_string($productID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE productlist SET Status = ? WHERE Product_ID = ?";
        $stmt = $con->prepare($sql);
        $status = "Disabled";
        $stmt->bind_param('ss',$status, $productID);
        if ($stmt->execute()){
            createAuditLog("Product","Removed $productID product details","$productID product details has been deleted. <br>Reason: $reason");
        $_SESSION["removed"] = true; 
        }else{
            echo $con->error;
        }
        
        
    }