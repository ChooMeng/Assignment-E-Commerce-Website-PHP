<?php
    include '../settings.php';
    session_start();
    if (!isset($_SESSION["staffId"])){ //Check it is the login session set if not send back to login page
         header('location: login.php');
    }
    /*Get the username of staff and check the login credential is valid because the staff account maybe get removed
        but the account still not yet logged out
     *      */
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $loginID = $_SESSION["staffId"];
        $sql = "SELECT userName from staff WHERE staffID='$loginID'";
        if($result = $con->query($sql)){
            if (($result->num_rows)>0){
                while($row = $result->fetch_object()){
                    $loginUserName = $row->userName;
                }
            }else{
                unset($_SESSION["staffId"]);
                header('location: login.php');
            }

        }else{
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }
        $result->free();
        $con->close();
    }
    