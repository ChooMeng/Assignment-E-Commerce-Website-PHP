<?php
    require 'Function/helper.php';
    //Verify categoryID
    if (!isset($_GET['id'])){
        setcookie("error","productId",time()+1,"products.php");
        header('Location: products.php');
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $productID = antiExploit($_GET['id']);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        $databaseError = $con->connect_error;
    }else{
        $sql = "SELECT * FROM productlist p, category c WHERE p.Product_ID = '$productID' AND p.Category = c.category_ID AND p.Status != 'Deleted'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $productID = $row->Product_ID;
                $categoryName = $row->category_name;
                $name = $row->Name;
                $save_as = $row->Image;
                $status = $row->Status;
                $stock = $row->Stock;
                $discount = $row->Discount;
                $price = $row->Price;
                $productDescribe = $row->Product_Desc;

            }
            if ($result->num_rows ==0){
                setcookie("error","productId",time()+1,"products.php");
                header('Location: products.php');
            }
        }
        $result->free();
    }
    $con->close();

