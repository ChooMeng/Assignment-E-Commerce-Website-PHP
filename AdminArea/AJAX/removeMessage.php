<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["messageID"])||!isset($_GET["supportID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $messageID = antiExploit($_GET["messageID"]);
    $supportID = antiExploit($_GET["supportID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $messageID = $con->real_escape_string($messageID);
        $reason = $con->real_escape_string($reason);
        $sql = "DELETE FROM supportmessage WHERE messageID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s',$messageID);
        $stmt->execute();
        createAuditLog("Support","Removed $messageID from $supportID support tickets","Message $messageID has been succesful removed from $supportID. <br>Reason: $reason");
        $_SESSION["removed"] = true; 
    }
