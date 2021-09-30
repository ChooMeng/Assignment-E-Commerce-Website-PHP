<?php
    //Check is client username exist
    function isClientUserNameExist($userName){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $userName = $con->real_escape_string($userName);
            //Select the data
            $sql = "SELECT * FROM client WHERE userName = '$userName'";
            //Retrive the result
            if($result = $con->query($sql)){
                if($result->num_rows > 0){
                    $exist = true;
                }   
            }
            $result->free(); //Clear the result
            $con->close(); //Close the connection
        }
        return $exist;
    }
    //Check is client email exist
    function isClientEmailExist($email){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $email = $con->real_escape_string($email);
            //Select the data
            $sql = "SELECT * FROM client WHERE email = '$email' AND status != 'Guest'";
            //Retrive the result
            if($result = $con->query($sql)){
                if($result->num_rows > 0){
                    $exist = true;
                }   
            }
            $result->free(); //Clear the result
            $con->close(); //Close the connection
        }
        return $exist;
    }
    //Check is client email exist
    function isGuestAccount($email){
        $exist = "";
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $email = $con->real_escape_string($email);
            //Select the data
            $sql = "SELECT clientID FROM client WHERE email = '$email' AND status = 'Guest'";
            //Retrive the result
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    $exist = $row->clientID;
                }   
            }
            $result->free(); //Clear the result
            $con->close(); //Close the connection
        }
        return $exist;
    }
    //Check the address and create a new address id
    function checkAddress ($clientID, $address, $city, $zipCode,$state,$newAddressID){
        $exist = false;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from address WHERE address = '$address' AND city = '$city' AND zipCode = '$zipCode' AND state = '$state'";
            if($result = $con->query($sql)){
                if ($result->num_rows>0){
                    while($row = $result->fetch_object()){
                        $addressID = $row->addressID;
                    }
                    $result->free();
                    $sql = "SELECT * from clientaddress WHERE clientID = '$clientID' AND addressID = '$addressID'";
                    if($result = $con->query($sql)){
                        if ($result->num_rows==1){
                            $exist = true;
                        }
                    }
                }
                if ($exist == false){
                    $addressID = $newAddressID;
                    $sql = "INSERT INTO address(addressID,address,zipCode,city,state) VALUES (?,?,?,?,?)";//Insert address detail into address table
                    $stmt = $con -> prepare ($sql);
                    $stmt ->bind_param('sssss', $addressID,$address,$zipCode,$city,$state);
                    if ($stmt->execute()){
                        $stmt->free_result();
                        $sql = "INSERT INTO clientaddress VALUES (?,?)";
                        $stmt = $con -> prepare ($sql);
                        $stmt ->bind_param('ss', $clientID,$addressID);
                        if ($stmt->execute()){
                           
                            $stmt->free_result();
                            $result->free();
                        }else{
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }
                        $stmt->free_result();
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $stmt->free_result();
                }
            }
        }
        return $addressID;
    }
    //Product ID --> Product Details
    function getProductDetails($productID){
        $details = array();
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            //Select the data
            $sql = "SELECT Category, Name, Image, Status, Stock,Price, Discount FROM productlist WHERE Product_ID = '$productID'";
            //Retrive the result
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    $details['category'] = $row->Category;
                    $details['name'] = $row->Name;
                    $details['image'] = $row->Image;
                    $details['stock'] = $row->Stock;
                    $details['discount'] = $row->Discount;
                    $details['price'] = $row->Price;
                }  
            }
            $result->free(); //Clear the result
            $con->close(); //Close the connection
        }
        return $details;
    }
    function productIDToName($productID){
        $enough = 0;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from productlist WHERE Product_ID = '$productID'";
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    return $row->Name;
                }
                $result->free();
            }
            
        }
    }
    function enoughQuantity($productID,$quantity){
        $enough = 0;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from productlist WHERE Product_ID = '$productID'";
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    return $row->Stock;
                }
                $result->free();
            }
            
        }
    }
    function updateStockQuantity($productID,$quantity){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from productlist WHERE Product_ID = '$productID'";
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    $stockQuantity = $row->Stock;
                }
                $stockQuantity = $stockQuantity + $quantity;
                if ($stockQuantity==0){
                    $sql = "UPDATE productlist SET Status=?, Stock = ? WHERE Product_ID = ?";//Insert product details into order product table
                    $stmt = $con -> prepare ($sql);
                    $status = "OutOfStock";
                    $stmt ->bind_param('sss', $status,$stockQuantity, $productID);
                }else{
                    $sql = "UPDATE productlist SET Stock = ? WHERE Product_ID = ?";//Insert product details into order product table
                    $stmt = $con -> prepare ($sql);
                    $stmt ->bind_param('ss', $stockQuantity, $productID);
                }
                
                if (!$stmt->execute()){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    $errorExist = true;
                }else{
                    $stmt->free_result();
                }
                $result->free();
            }
            
        }
    }