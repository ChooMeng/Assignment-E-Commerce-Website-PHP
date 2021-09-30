<?php
    include 'settings.php';
    session_start();
    $fileName = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
    if (!isset($_SESSION["clientId"])){
        $_SESSION["back"] = $fileName;
        header('location: login.php?back=true');
    }
    /*Get the username of client and check the login credential is valid because the client account maybe get removed
        but the account still not yet logged out
     *      */
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $loginID = $_SESSION["clientId"];
        $sql = "SELECT userName from client WHERE clientID='$loginID'";
        if($result = $con->query($sql)){
            if (($result->num_rows)>0){
                while($row = $result->fetch_object()){
                    $loginUserName = $row->userName;
                    if (isset($_SESSION['guestId'])){
                        unset($_SESSION['guestId']);
                    }
                }
            }else{
                unset($_SESSION["clientId"]);
                header('location: login.php');
            }

        }else{
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }
        $result->free();
        $con->close();
    }