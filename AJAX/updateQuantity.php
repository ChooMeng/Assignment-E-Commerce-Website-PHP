<?php
    session_start();
    if (!isset($_GET["productID"])||!isset($_GET["quantity"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../productList.php');
    }
    include '../settings.php';
    
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $productID = antiExploit($_GET["productID"]);
    $quantity = $_GET["quantity"];
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
                header('Location: ../productList.php');
            }
        }
        $result->free();
    }
    if (isset($_SESSION['clientId'])){
        clientMode($productID,$quantity);
    }else{
        if (isset($_SESSION['shoppingCart'])){
            $shoppingCart = $_SESSION['shoppingCart'];
            $shoppingCart[$productID] = $quantity;
            echo "<script>alert($quantity)</script>";
            $_SESSION['shoppingCart'] = $shoppingCart;
        }else{
            $shoppingCart = array();
            $shoppingCart[$productID] = $quantity;
            $_SESSION['shoppingCart'] = $shoppingCart;
        }
        
    }
    function clientMode($productID,$quantity){
        $clientID = $_SESSION['clientId'];
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $productID = $con->real_escape_string($productID);
            $sql = "UPDATE shoppingcarttable SET quantity = ? WHERE clientID = ? AND productID = ?";
            $stmt = $con->prepare($sql);
            $stmt -> bind_param('sss', $quantity, $clientID, $productID);
            if($stmt->execute())
            {
                $stmt->free_result();
            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }

        }
        $con->close();
    }