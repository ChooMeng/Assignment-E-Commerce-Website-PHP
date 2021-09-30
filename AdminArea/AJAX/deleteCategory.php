<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["categoryID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $categoryID = antiExploit($_GET["categoryID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * FROM category WHERE category_ID = '$categoryID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }
        
        $result->free();
        $orderID = $con->real_escape_string($categoryID);
        $reason = $con->real_escape_string($reason);
        $sql = "DELETE FROM category WHERE category_ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s',$categoryID);
        if($stmt->execute()){
            createAuditLog("Product","Removed $categoryID category","$categoryID category deleted succesful. <br>Reason: $reason");
            $_SESSION["removed"] = true; 
        }else{
            echo "DEBUG PURPORSE: ".$con->errno;
            if (($con->errno)==1451){
                $_SESSION["errorConstraint"] = true; 
            }
        }
        
    }

