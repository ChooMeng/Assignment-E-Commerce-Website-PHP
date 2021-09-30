<?php
    session_start();

    if (!isset($_GET["productID"])||!isset($_SESSION["clientId"])){ 
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
        $prodID = antiExploit($_GET["productID"]); 
        $clientID = antiExploit($_SESSION["clientId"]);
        
        $sql = "DELETE FROM wishlist WHERE Product_ID = ? AND clientID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss', $prodID, $clientID);
        if($stmt->execute()){
            $stmt->free_result();
        }else{
            echo $con->connect_error;
        }
        
    }
    $con->close();
    $_SESSION['removed'] = true;
