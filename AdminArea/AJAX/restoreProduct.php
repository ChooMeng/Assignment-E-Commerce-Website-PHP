<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["productID"])||!isset($_GET["reason"])){ //Check it is the login session and it is correct way to connect if not send back to login page
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
    $productID = antiExploit($_GET["productID"]);
    $reason = antiExploit($_GET["reason"]);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productlist WHERE Product_ID = '$productID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $stock = $row->Stock;
            }
            if ($result->num_rows ==0){
                $stock = 0;
            }
        }
        if ($stock>0){
            $status = 'Enabled';
        }else{
            $status = 'OutOfStock';
        }
        $productID = $con->real_escape_string($productID);
        $reason = $con->real_escape_string($reason);
        $sql = "UPDATE productlist SET Status = ? WHERE Product_ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ss',$status ,$productID);
        $stmt->execute();
        createAuditLog("Product","Restored $productID products ","$productID product has been succesful restored. <br>Reason: $reason");
        $_SESSION["restored"] = true; 
    }
