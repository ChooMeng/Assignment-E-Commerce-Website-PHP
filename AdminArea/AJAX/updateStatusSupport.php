<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["supportID"])||!isset($_GET["statusApply"])||!isset($_GET["priorityApply"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $supportID = antiExploit($_GET["supportID"]);
    $statusApply = antiExploit($_GET["statusApply"]);
    $status = array("Open","Pending","Resolved","Closed","Canceled");
    if(!in_array($statusApply, array_values($status))){
        $_SESSION["errorStatus"] = true; 
    }
    $priority = array("High","Normal","Low");
    $priorityApply = antiExploit($_GET["priorityApply"]);
    if(!in_array($priorityApply, array_values($priority))){
        $_SESSION["errorPriority"] = true; 
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $supportID = $con->real_escape_string($supportID);
        $statusApply = $con->real_escape_string($statusApply);
        $priorityApply = $con->real_escape_string($priorityApply);
        $sql = "UPDATE support SET status = ?, priority = ?  WHERE supportID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sss',$statusApply,$priorityApply ,$supportID);
        $stmt->execute();
        createAuditLog("Support","Updated $supportID support ticket status and priority","$supportID support ticket status has been succesful changed to $statusApply and priority has been succesful changed to $priorityApply.");
        $_SESSION["updated"] = true; 
    }

