<?php
    if (!isset($_GET['productID'])){
        setcookie("error","type",time()+1,"productList.php");
        header('Location: productList.php');
    }
    $productID = $_GET['productID'];
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productList p, category c WHERE Product_ID = '$productID' AND Status != 'Disabled' AND category=category_id";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                $category = $row->Category;
                $categoryName = $row->category_name;
                $name = $row->Name;
                $image = $row->Image;
                $productStatus = $row->Status;
                $discount = $row->Discount;
                $productDesc = $row->Product_Desc;
                $stock = $row->Stock;
                $price = $row->Price;
                $price = number_format($price, 2);
            }
            if ($result->num_rows ==0){
                setcookie("error","type",time()+1,"productList.php");
                header('Location: productList.php');
            }
        }
    }
