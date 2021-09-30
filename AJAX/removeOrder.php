<?php
    session_start();
    if (!isset($_GET["orderID"]) || !isset($_SESSION["clientID"])){ 
        //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../settings.php';
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $orderID = antiExploit($_GET["orderID"]);
        $sql = "SELECT * FROM orders WHERE orderID = '$orderID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                //The oldstatus will be saved into the database
                $oldStatus = $row->status;
            }
            if ($result->num_rows ==0){
                $oldStatus = "Pending";
            }
        }
        $result->free();
        $orderID = $con->real_escape_string($orderID);
        $sql = "UPDATE orders SET status = ?, oldStatus=? WHERE orderID = ?";
        $stmt = $con->prepare($sql);
        $status = "Canceled";
        $stmt->bind_param('sss',$status, $oldStatus ,$orderID);
        $stmt->execute();
    }
