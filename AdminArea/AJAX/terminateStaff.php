<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["staffID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $staffID = antiExploit($_GET["staffID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $staffID = $con->real_escape_string($staffID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE staff SET status = ? WHERE staffID = ?";
        $stmt = $con->prepare($sql);
        $status = "Terminated";
        $stmt->bind_param('ss',$status ,$staffID);
        $stmt->execute();
        createAuditLog("Staff","Terminated $staffID account","$staffID account has been succesful terminated. <br>Reason: $reason");
        $_SESSION["terminate"] = true; 
    }