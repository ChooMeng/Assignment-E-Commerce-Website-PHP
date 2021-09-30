<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["clientID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $clientID = antiExploit($_GET["clientID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $clientID = $con->real_escape_string($clientID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE client SET status = ? WHERE clientID = ?";
        $stmt = $con->prepare($sql);
        $status = "Terminated";
        $stmt->bind_param('ss',$status ,$clientID);
        $stmt->execute();
        createAuditLog("Client","Terminated $clientID account","$clientID account has been succesful terminated. <br>Reason: $reason");
        $_SESSION["terminate"] = true; 
    }
