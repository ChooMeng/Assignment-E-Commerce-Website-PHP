<?php
    date_default_timezone_set(TIMEZONE);
    //Check is staffID exist
    function isStaffIDExist($staffID){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            //Prevent glitch from hacker
            $id = $con->real_escape_string($id);
            //Select the data
            $sql = "SELECT * FROM staff WHERE staffID = '$id'";
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
    //Check is staff username exist
    function isStaffUserNameExist($userName){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            //Prevent glitch from hacker
            $userName = $con->real_escape_string($userName);
            //Select the data
            $sql = "SELECT * FROM staff WHERE userName = '$userName'";
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
    //Check is clientID exist
    function isClientIDExist($clientID){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $clientID = $con->real_escape_string($clientID);
            //Select the data
            $sql = "SELECT * FROM client WHERE clientID = '$clientID'";
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
            $sql = "SELECT * FROM client WHERE email = '$email'";
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
    function isStaffEmailExist($email){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $email = $con->real_escape_string($email);
            //Select the data
            $sql = "SELECT * FROM staff WHERE email = '$email'";
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
    function isOrderNoExist($orderNo){
        $exist = false;
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
             //Prevent glitch from hacker
            $orderNo = $con->real_escape_string($orderNo);
            //Select the data
            $sql = "SELECT * FROM orders WHERE orderNo = '$orderNo'";
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
    //Convert staffID to name
    function staffIDToName($staffID){
        $name = "";
        //Create the connection
        $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        //Prevent glitch from hacker
        $staffID = $con->real_escape_string($staffID);
        //Select the data
        $sql = "SELECT name FROM staff WHERE staffID = '$staffID'";
        //Retrive the result
        if($result = $con->query($sql)){
            while($row = $result->fetch_object()){
                $name = $row->name;
            }
        }
        $result->free(); //Clear the result
        $con->close(); //Close the connection
        return $name;
    }
    //Create audit log
    function createAuditLog($type,$changes,$details){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from auditlog";
            if($result = $con->query($sql)){
                $count = $result->num_rows+1;
                switch ($type){
                    case "Order":
                        $typeID = "OR";
                        break;
                    case "Client":
                        $typeID = "CL";
                        break;
                    case "Product":
                        $typeID = "PR";
                        break;
                    case "Staff":
                        $typeID = "ST";
                        break;
                    case "Support":
                        $typeID = "SU";
                        break;
                }
                $auditID = "A".$typeID.$count;
                $date = date('Y-m-d H:i:s');
                $staff = $_SESSION['staffId'];
                //public internet ip
                if (!empty($_SERVER['HTTP_CLIENT_IP']))   {
                    $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
                }
                //proxy ip
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{ //remote address ip
                    $ipAddress = getHostByName(getHostName());;
                }
                $sql = "INSERT INTO auditlog VALUES (?,?,?,?,?,?,?)";
                $stmt = $con -> prepare($sql);
                $stmt ->bind_param('sssssss',$auditID,$staff,$ipAddress,$type,$date,$changes,$details);
                $stmt->execute();
                if ($stmt->affected_rows <=0){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$stmt->error."</div>";
                }
                $stmt->free_result(); //clear statement
            }else{
                 echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }
            $result->free(); //clear result
            $con->close(); //close connection

        }
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
                            createAuditLog("Client","Create new client address","New client address with AddressID $addressID for ClientID $clientID created.");
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
    //Check the address is exist or not if yes cancel the insert request
    function checkAddressExist ($clientID, $address, $city, $zipCode,$state){
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
            }
        }
        return $exist;
    }
    //Check the address is exist or not if yes cancel the insert request
    function checkAddressExistAndInsert ($clientID, $address, $city, $zipCode,$state){
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
                    $sql = "SELECT * from address";
                    if(!$result = $con->query($sql)){
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }else{
                        $addressID = $result->num_rows+1;
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
                                //Update the member details updateTime
                               $sql = "UPDATE client SET lastUpdate = ? WHERE clientID=?";
                               $stmt = $con->prepare($sql);
                               $stmt ->bind_param('ss',$date, $clientID);
                               if($stmt->execute()){
                                    createAuditLog("Client","Create new client address","New client address with AddressID $addressID for ClientID $clientID created.");
                                    $stmt->free_result();
                                    $result->free();
                                    $con->close();
                               }else{
                                   echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                }

                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                            }
                            $stmt->free_result();
                        }else{
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }
                    }
                    $stmt->free_result();
                }
            }
        }
        return $exist;
    }
    function isProductExist($productID){
        $exist = false;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from productlist WHERE Product_ID = '$productID'";
            if($result = $con->query($sql)){
                if ($result->num_rows>0){
                    $exist = true;
                }
            }
        }
        return $exist;
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
