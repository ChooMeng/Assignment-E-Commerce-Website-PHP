<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["supportID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from support WHERE supportID = '$supportID'";
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
        $supportID = $con->real_escape_string($supportID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE support SET status = ? WHERE supportID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss',$oldStatus ,$supportID);
        $stmt->execute();
        createAuditLog("Support","Restored $supportID support tickets ","$supportID support tickets has been succesful restored. <br>Reason: $reason");
        $_SESSION["restored"] = true; 
    }
