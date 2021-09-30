<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["addressID"])||!isset($_GET["clientID"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $addressID = antiExploit($_GET["addressID"]);
    $clientID = antiExploit($_GET["clientID"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $addressID = $con->real_escape_string($addressID);
        $clientID = $con->real_escape_string($clientID);
        $sql = "SELECT * from orders WHERE clientID = '$clientID' AND status != 'Canceled' AND status != 'Completed' AND status != 'Removed' AND billingAddress = '$addressID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            if ($result->num_rows ==0){
                $result->free();
                $sql = "SELECT * from client WHERE clientID = '$clientID' AND defaultAddress = '$addressID'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    if ($result->num_rows ==0){
                        $result->free();
                        $sql = "DELETE from clientaddress WHERE clientID = ? AND addressID = ?";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('ss',$clientID ,$addressID);
                        if ($stmt->execute()){
                            createAuditLog("Client","Removed client address.","Address of $clientID with ID $addressID has been succesful removed.");
                            $_SESSION["clientAddress"] = true; //true succesful & false fail remove address
                        }else{
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$stmt->error."</div>";
                        }
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: Cannot remove active billing address</div>";
                        $_SESSION["clientAddress"] = false; //true succesful & false fail remove address
                    }
                }
                
                
            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: Cannot remove active billing address</div>";
                $_SESSION["clientAddress"] = false; //true succesful & false fail remove address
                return;
                
            }
        }
            
        
    }

