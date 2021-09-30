<?php
    session_start();
    if (!isset($_SESSION["clientId"])||!isset($_GET["supportID"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../settings.php';
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $supportID = antiExploit($_GET["supportID"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * FROM support WHERE supportID = '$supportID' AND clientID = '".$_SESSION['clientId']."'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            //Verify supportID whether it is valid
            if ($result->num_rows ==0){
                setcookie("error","supportId",time()+1,"../supports.php");
                header('Location: ../supports.php');
            }
        }
        $result->free();
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $supportID = $con->real_escape_string($supportID);
        $statusApply = "Resolved";
        $sql = "UPDATE support SET status = ? WHERE supportID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss',$statusApply ,$supportID);
        $stmt->execute();
        $_SESSION["updated"] = true; 
    }

