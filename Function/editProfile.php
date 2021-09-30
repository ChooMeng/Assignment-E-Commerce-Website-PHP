<?php
    $clientID = $_SESSION["clientId"];
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
    //Display succesful remove address message
    if(isset($_SESSION["clientAddress"])){
        if ($_SESSION["clientAddress"]){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Removed the client address.");}, 100);</script>';
            unset($_SESSION["clientAddress"]);
        }else{
            echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Cannot remove the client address.<br>Reason: Currently using on active orders <br>OR it is default address");}, 100);</script>';
            unset($_SESSION["clientAddress"]);
        }
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from client WHERE clientID = '$clientID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result-> fetch_object())
            {
                $gender = $row->gender;
                $username = $row->userName;
                $name = $row->name;
                $email = $row->email;
                $contactNumber = $row->contactNumber;
                $payment = $row->paymentMethod;
                $birthDate = $row->birthDate;
                $defaultAddress = $row->defaultAddress;
            }

        }
        $result->free();
    }
    $con->close();
    //Display payment list
    function paymentList(){
        global $payment;
        foreach($GLOBALS['payments'] as $key => $value){
            $select = "";
            if (isset($_POST["payment"])){
                if ($_POST["payment"]==$key){
                    $select = "selected";
                }
            }else if ($payment==$value){
                $select = "selected";
            }
            echo "<option value='$key' $select>$value</option>";
        }
    }
    //Display gender list
    function genderList(){
        global $gender;
        foreach($GLOBALS['genderArr'] as $key => $value){
            $select = "";
            if (isset($_POST["gender"])){
                if ($_POST["gender"]==$key){
                    $select = "selected";
                }
            }else if ($gender==$key){
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
    function displayAddress(){
        global $clientID,$states;
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{
            $sql = "SELECT * from client c, clientaddress ca, address a WHERE ca.clientID = '$clientID' AND c.clientID = ca.clientID AND ca.addressID = a.addressID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf("
                        <tr id='a%s'>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td>%s</td>
                             <td><input id='b%s' name='default' value='%d' type='radio' %s></input></td>
                            <td>
                                <span class='deleteAddress' onclick='openBox(%s)'>
                                    <i class='fas fa-minus' aria-hidden='true'></i>
                                </span>
                            </td>
                            <td><a href='editAddress.php?clientId=%s&type=address&addressID=%d' class='editButton'>EDIT</a></td>
                        </tr>
                     ",$row->addressID,$row->address,$row->zipCode,$row->city,$states[$row->state],$row->addressID,$row->addressID, $row->defaultAddress==$row->addressID?"checked":"","\"".$row->addressID."\"",$clientID,$row->addressID);
                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='6'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }
            }
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
        if (isset($_POST['editProfile'])){
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
        if (isset($_POST['editProfile'])){
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
                    }else{
                        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                        if ($con -> connect_errno) { //Check it is the connection succesful
                            
                            $databaseError = $con->connect_error;
                        }else{
                            $sql = "SELECT * from client WHERE userName = '$username'";
                            if ($result=$con->query($sql)){
                                if ($result->num_rows !=0){
                                    while($row = $result->fetch_object()){
                                        if ($row->clientID != $clientID){
                                            array_push($error,"<b>Username</b> is unavailable.");
                                            $errorUserName = true;
                                        }
                                    }
                                    
                                }
                            }else{
                                $databaseError = $con->connect_error;
                            }
                            $result->free();
                            $con->close();
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
            if(empty($_POST['gender'])){
                array_push($error,"Please select a <b>Gender</b>.");
                $errorGender = true;
            }else{
                $gender = antiExploit($_POST["gender"]);
                $pattern = '/^[MF]$/';
                if(!in_array($gender, array_keys($genderArr))){
                    array_push($error,"Please select a <b>Gender</b> Must be only either Male or Female.");
                }
            }
            if(empty($_POST["email"])){
                array_push($error,"<b>Email Address</b> cannot empty.");
                $errorEmail = true;
            }else{
                $email = antiExploit($_POST["email"]);
                if(strlen($email)>50){
                    array_push($error,"<b>Email</b> cannot more than 50 characters.");
                }else{
                    $pattern = "/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/";
                    if(!preg_match($pattern, $email)){
                        array_push($error,"<b>Email Address</b> is of invalid format.");
                        $errorEmail = true;
                    }else{
                        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                        if ($con -> connect_errno) { //Check it is the connection succesful
                            $databaseError = $con->connect_error;
                        }else{
                            $sql = "SELECT * from client WHERE email = '$email'";
                            if ($result=$con->query($sql)){
                                if ($result->num_rows !=0){
                                    while($row = $result->fetch_object()){
                                        if ($row->clientID != $clientID){
                                            array_push($error,"<b>Email</b> is unavailable.");
                                            $errorEmail = true;
                                        }
                                    }
                                    
                                }
                            }else{
                                $databaseError = $con->connect_error;
                            }
                            $result->free();
                            $con->close();
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
                }else{
                    $pattern = '/^([0-9]{2}|[0-9]{3})-([0-9]+)$/';
                    if(!preg_match($pattern,$contactNumber)){
                        array_push($error,"<b>Contact Number</b> is of invalid format. Format: xxx-xxxxxxxx");
                        $errorContactNumber = true;
                    }
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
            //Check set default address section
            if(!isset($_POST['default'])){
                array_push($error,"Please select an <b>Address</b>.");
                $errorDefault = true;
            }else{
                $defaultAddress = antiExploit($_POST['default']);
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    $databaseError = $con->connect_error;
                    $errorDefault = true;
                }else{
                    //Valid it is correct address and is address of that client 
                    $sql = "SELECT * from clientaddress WHERE addressID = '$defaultAddress' AND clientID = '$clientID'";
                    if(!$result = $con->query($sql)){
                        $databaseError = $con->error;
                        $errorDefault = true;
                    }else{
                        if ($result->num_rows ==0){
                            array_push($error,"Please select a valid <b>Address</b>.");
                            $errorDefault = true;
                        }
                    }
                }
            }
            if (empty($error)){
                $sql = "UPDATE client SET userName=?, name=?, gender=?,email=?, birthDate=?,contactNumber = ?,paymentMethod = ?,"
                        . "defaultAddress = ?,lastUpdate = ? WHERE clientID = ?";
                $stmt=$con->prepare($sql);
                $date = date('Y-m-d H:i:s');
                $id = $_SESSION["staffId"];
                $stmt -> bind_param('ssssssssss',$username,$name,$gender,$email,$birthDate,$contactNumber,$payment,$defaultAddress,$date,$clientID);
                if ($stmt->execute()){
                    $stmt->free_result();
                    $con->close();
                    
                    header('HTTP/1.1 307 Temporary Redirect');
                    header('Location: profile.php');
                }else{
                    $databaseError = $stmt->error;
                    $stmt->free_result();
                    $con->close();
                }
            }
        }
        
        
    }
