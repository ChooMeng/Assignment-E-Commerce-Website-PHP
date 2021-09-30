<?php
    require 'Function/helper.php';
    require 'settings.php';
    if (isset($_SESSION["clientId"])){
        header('location: index.php');
    }
    //Payments List
    $payments = array(
        ""=>"--Selected One--",
        "Paypal"=>"Paypal",
        "CreditCard"=>"CreditCard",
        "TouchNGo" => "TouchNGo"
    );
    //States List
    $states = array(
        ""=>"--Selected One--",
        "JH" => "Johor",
        "KD" => "Kedah",
        "KT" => "Kelantan",
        "KL" => "Kuala Lumpur",
        "LB" => "Labuan",
        "MK" => "Melaka",
        "NS" => "Negeri Sembilan",
        "PH" => "Pahang",
        "PN" => "Penang",
        "PR" => "Perak",
        "PL" => "Perlis",
        "PJ" => "Putrajaya",
        "SB" => "Sabah",
        "SW" => "Sarawak",
        "SG" => "Selangor",
        "TR" => "Terengganu"
    );
    //Gender List
    $genderArr = array(
        ""=>"--Selected One--",
        "F" => "Female",
        "M" => "Male"
    );
    //Display payment list
    function paymentList(){
        foreach($GLOBALS['payments'] as $key => $value){
            $select = "";
            if (isset($_POST["payment"])&&$_POST["payment"]==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display gender list
    function genderList(){
        foreach($GLOBALS['genderArr'] as $key => $value){
            $select = "";
            if (isset($_POST["gender"])&&$_POST["gender"]==$key){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display state list
    function stateList(){
        foreach($GLOBALS['states'] as $key => $value){
            echo "<option value='$key'".(isset($_POST["state"])?($_POST["state"]==$key?"selected":""):"").">$value</option>";
        }
    }
    $error = array();
    //Display error icon
    function displayError(){
        echo "<img src='Media/error.png'>";
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function displayErrorMessage(){
        $error = $GLOBALS['error'];
        if (isset($_POST['register'])){
            if (!empty($error)){
                //Display error message
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
        
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['register'])){
            if (empty($_POST["username"])){
                array_push($error,"<b>Username</b> cannot empty.");
                $errorUserName = true;
            }else{
                $username = antiExploit($_POST["username"]);
                if (strlen($username)>40){
                    array_push($error,"<b>Username</b> cannot more than 40 characters.");
                    $errorUserName = true;
                }else{
                    $pattern = '/^[A-Za-z\s@\'\.-\/]+$/';
                    if(!preg_match($pattern, $username)){
                        array_push($error,"<b>Username</b> can contains only uppercase and lowercase alphabet, space, alias, comma, single-quote, dot, dash and slash.");
                        $errorUserName = true;
                    } else{
                        if (isClientUserNameExist($username)){
                            array_push($error,"<b>Username</b> is unavailable");
                            $errorUserName = true;
                        }
                    }
                }
            }
            if (empty($_POST["name"])){
                array_push($error,"<b>Name</b> cannot empty.");
                $errorName = true;
            }else{
                $name = antiExploit($_POST["name"]);
                if (strlen($name)>40){
                    array_push($error,"<b>Name</b> cannot more than 40 characters.");
                    $errorName = true;
                }else{
                    $pattern = '/^[A-Za-z\s@\'\.-\/]+$/';
                    if(!preg_match($pattern, $name)){
                        array_push($error,"<b>Name</b> can contains only uppercase and lowercase alphabet, space, alias, comma, single-quote, dot, dash and slash.");
                        $errorName = true;
                    } 
                }
            }
            if (empty($_POST["password"])){
                array_push($error,"<b>Password</b> cannot empty.");
                $errorPassword = true;
            }else{
                $password = antiExploit($_POST["password"]);
                if (strlen($password)<8||strlen($password)>32){
                    array_push($error,"<b>Password</b> must between 8 to 32 characters.");
                    $errorPassword = true;
                }else{
                    $pattern = '/^[\w]+$/';
                    if(!preg_match($pattern, $password)){
                        array_push($error,"<b>Password</b> can contains only uppercase and lowercase alphabet, digit and underscore.");
                        $errorPassword = true;
                    }
                }
            }
            if(empty($_POST["confirmpassword"])){
                array_push($error,"<b>Confirm Password</b> cannot empty.");
                $errorConfirmPassword = true;
            }else{
                $confirmpassword = antiExploit($_POST["confirmpassword"]);
                if(isset($password)&&($password!=$confirmpassword)){
                    array_push($error,"<b>Confirm Password</b> must match the password.");
                    $errorConfirmPassword = true;
                }
            }
            if(empty($_POST['gender'])){
                array_push($error,"Please select a <b>Gender</b>.");
                $errorGender = true;
            }else{
                $gender = antiExploit($_POST["gender"]);
                $pattern = '/^[MF]$/';
                if(!in_array($gender, array_keys($genderArr))){
                    array_push($error,"Please select a <b>Gender</b> Must be only either Male or Female.");
                    $errorGender = true;
                }
            }
            if(empty($_POST["email"])){
                array_push($error,"<b>Email Address</b> cannot empty.");
                $errorEmail = true;
            }else{
                $email = antiExploit($_POST["email"]);
                if(strlen($email)>50){
                    array_push($error,"<b>Email</b> cannot more than 50 characters.");
                    $errorEmail = true;
                }else{
                    $pattern = "/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/";
                    if(!preg_match($pattern, $email)){
                        array_push($error,"<b>Email Address</b> is of invalid format.");
                        $errorEmail = true;
                    }else{
                        if (isClientEmailExist($email)){
                            array_push($error,"<b>Email</b> is unavailable");
                            $errorEmail = true;
                        }
                    }
                }
            }
            if(empty($_POST["birthdate"])){
                array_push($error,"<b>Birth Date</b> cannot empty.");
                $errorBirthDate = true;

            }else{
                $birthDate = antiExploit($_POST["birthdate"]);
                $year = substr($birthDate, 0,4);
                $month = substr($birthDate,5,2);
                $day = substr($birthDate,8,2);
                if(!checkdate($month, $day, $year)){
                    array_push($error,"<b>Birth Date</b> invalid date.");
                    $errorBirthDate = true;
                }
            }
            if(empty($_POST["contactNumber"])){
                array_push($error,"<b>Contact Number</b> cannot empty.");
                $errorContactNumber = true;
            }else{
                $contactNumber = antiExploit($_POST["contactNumber"]);
                if (strlen($contactNumber)>12){
                    array_push($error,"<b>Contact Number</b> cannot more than 12 characters.");
                    $errorContactNumber = true;
                }else{
                    $pattern = '/^([0-9]{2}|[0-9]{3})-([0-9]+)$/';
                    if(!preg_match($pattern,$contactNumber)){
                        array_push($error,"<b>Contact Number</b> is of invalid format. Format: xxx-xxxxxxxx");
                        $errorContactNumber = true;
                    }
                }

            }
            if(empty($_POST["address"])){
                array_push($error,"<b>Address</b> cannot empty.");
                $errorAddress = true;
            }else{
                $address = antiExploit($_POST["address"]);
            }
            if(empty($_POST["zipCode"])){
                array_push($error,"<b>Zip Code</b> cannot empty.");
                $errorZipCode = true;
            }else{
                $zipCode = antiExploit($_POST["zipCode"]);
                if (strlen($zipCode)>5){
                    array_push($error,"<b>Zip Code</b> cannot more than 5 characters.");
                    $errorZipCode = true;
                }else{
                    $pattern = '/^[0-9]{5}$/';
                    if(!preg_match($pattern,$zipCode)){
                        array_push($error,"<b>Zip Code</b> is of invalid format.");
                        $errorZipCode = true;
                    } 
                }

            }
            if(empty($_POST["city"])){
                array_push($error,"<b>City</b> cannot empty.");
                $errorCity = true;
            }else{
                $city = antiExploit($_POST["city"]);
                if (strlen($city)>28){
                    array_push($error,"<b>City</b> cannot more than 28 characters.");
                    $errorCity = true;
                }
            }
            if(empty($_POST['state'])){
                array_push($error,"Please select a <b>State</b>.");
                $errorState = true;
            }else{
                $state = antiExploit($_POST["state"]);
                if(!in_array($state, array_keys($states))){
                    array_push($error,"Please select a <b>State</b> Must be a valid state.");
                    $errorState = true;
                }
            }
            if(empty($_POST['payment'])){
                array_push($error,"Please select a <b>Payment Method</b>.");
                $errorPayment = true;
            }else{
                $payment = antiExploit($_POST["payment"]);
                if(!in_array($payment, array_keys($payments))){
                    array_push($error,"Please select a <b>Payment Method</b> Must be a valid payment method.");
                    $errorPayment = true;
                }
            }
            if (empty($error)){
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "SELECT * from client";
                    if($result = $con->query($sql)){
                        $status = "Active";
                        $existClientID = isGuestAccount($email);
                        if (!empty($existClientID)){
                            $result->free();
                            $sql = "SELECT * from address";
                            if($result = $con->query($sql)){
                                $addressID = $result->num_rows+1;
                                $date = date('Y-m-d H:i:s');
                                if (!empty($password)){
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                                }
                                $addressID = checkAddress($existClientID, $address, $city, $zipCode, $state, $addressID);
                                $sql = "UPDATE client SET clientID = ?,userName = ?,name = ?,password = ?,gender = ?,birthDate = ?,contactNumber = ?,paymentMethod = ?,
                                    joinDate = ?,lastUpdate = ?,status = ?,defaultAddress = ? WHERE email = ?"; //Insert client detail into client table
                                $stmt = $con -> prepare($sql);
                                $stmt -> bind_param('sssssssssssss',$existClientID,$username,$name,$password,$gender,$birthDate,$contactNumber,$payment
                                        ,$date,$date,$status,$addressID,$email);
                                if ($stmt->execute()){
                                    $stmt->free_result();
                                    $result->free();
                                    $con->close();
                                    header('HTTP/1.1 307 Temporary Redirect');
                                            header('Location: login.php');
                                }else{
                                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                }
                                $stmt->free_result();
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                            }
                        }else{
                            $clientCount = $result->num_rows+1;
                            $sql = "SELECT * from address";
                            if($result = $con->query($sql)){
                                $clientID = "C".str_repeat("0",6-strlen($clientCount)).$clientCount;
                                $addressID = $result->num_rows+1;
                                $date = date('Y-m-d H:i:s');
                                if (!empty($password)){
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                                }
                                $sql = "INSERT INTO client(clientID,userName,name,password,gender,email,birthDate,contactNumber,paymentMethod,
                            joinDate,lastUpdate,status,defaultAddress) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"; //Insert client detail into client table
                                $stmt = $con -> prepare($sql);
                        $stmt -> bind_param('sssssssssssss',$clientID,$username,$name,$password,$gender,$email,$birthDate,$contactNumber,$payment
                                ,$date,$date,$status,$addressID);
                                if ($stmt->execute()){
                                    $stmt->free_result();
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
                                            $con->close();
                                            header('HTTP/1.1 307 Temporary Redirect');
                                            header('Location: login.php');
                                        }else{
                                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                        }
                                        $stmt->free_result();
                                    }else{
                                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                    }
                                    $stmt->free_result();
                                }else{
                                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                                }
                                $stmt->free_result();
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                            }
                        }
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $result->free();
                    $con->close();
                }
            }
        }
        
        
    }
