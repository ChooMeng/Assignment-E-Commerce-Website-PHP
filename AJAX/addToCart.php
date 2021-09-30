<?php
    session_start();
    if (!isset($_SESSION["clientId"])||!isset($_GET["productID"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../settings.php';
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $productID = antiExploit($_GET["productID"]);
    $clientID = antiExploit($_SESSION["clientId"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * FROM productList WHERE Product_ID = '$productID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            //Verify supportID whether it is valid
            if ($result->num_rows ==0){
                setcookie("error","productID",time()+1,"../wishList.php");
                header('Location: ../wishList.php');
            }
        }
        $result->free();
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * FROM shoppingcarttable WHERE productID = '$productID' AND clientID = '".$_SESSION['clientId']."'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            if ($result->num_rows ==0){
                $result->free();
                $productID = $con->real_escape_string($productID);
                $quantity = 1;
                $sql = "INSERT INTO shoppingcarttable (clientID, productID, quantity) VALUES (?,?,?)";
                $stmt = $con->prepare($sql);
                $stmt -> bind_param('sss', $clientID, $productID, $quantity);
                if($stmt->execute())
                {
                    $stmt->free_result();
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
            }
        }
        
    }
    $con->close();