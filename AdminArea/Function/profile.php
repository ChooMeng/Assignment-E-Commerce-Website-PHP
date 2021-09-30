<?php
    include 'Function/helper.php';
    $staffID = $_SESSION["staffId"];
    if ((isset($_GET['type'])&&$_GET['type']=="profile")||!isset($_GET['type'])){
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from staff WHERE staffID = '$staffID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                   $userName = $row->userName;
                   $name = $row->name;
                   $gender = $row ->gender;
                   $email = $row->email;
                   $birthDate = $row->birthDate;
                   $contactNumber = $row->contactNumber;
                }
            }
            $result->free();
        }
        $con->close();
    }
    
    //Gender List
    $genderArr = array(
        "F" => "Female",
        "M" => "Male"
    );
    function genderList(){
        global $gender;
        foreach($GLOBALS['genderArr'] as $key => $value){
            echo "<input type='radio' id='$value' name='gender' value='$key'".(isset($_POST["gender"])?($_POST["gender"]==$key?"checked":""):($gender==$key?"checked":"")).">";
            echo "<label for='$key'>$value</label>";
        }
    }
    $error = array();
    //Display error icon
    function displayError(){
        echo "<img src='../Media/error.png'>";
    }
    //Check section requested and switch the section
    function check(){
        if (isset($_GET['type'])){
            $type = $_GET['type'];
            if($type=="password"){
                echo "<script>switchType('password')</script>";
            }else if($type=="profile"){
                echo "<script>switchType('profile')</script>";
            }
        }else{
            echo "<script>switchType('profile')</script>";
        }
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        //Check profile section
        if ($_POST["type"]=="modifyProfile"){
            if (empty($_POST["username"])){
                array_push($error,"<b>Username</b> cannot empty.");
                $errorUserName = true;
            }else{
                $userName = antiExploit($_POST["username"]);
                if (strlen($userName)>40){
                    array_push($error,"<b>Username</b> cannot more than 40 characters.");
                    $errorUserName = true;
                }else{
                    $pattern = '/^[A-Za-z0-9]+$/';
                    if(!preg_match($pattern, $userName)){
                        array_push($error,"<b>Username</b> can contains only uppercase and lowercase alphabet and numbers");
                        $errorUserName = true;
                    }else{
                        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                        if ($con -> connect_errno) { //Check it is the connection succesful
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                        }else{
                            $sql = "SELECT * from staff WHERE username = '$userName'";
                            if ($result=$con->query($sql)){
                                if ($result->num_rows !=0){
                                    while($row = $result->fetch_object()){
                                        if ($row->staffID != $staffID){
                                            array_push($error,"<b>Username</b> is unavailable.");
                                            $errorUserName = true;
                                        }
                                    }
                                }
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
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
            if(!isset($_POST['gender'])){
                array_push($error,"Please select a <b>Gender</b>.");
                $errorGender = true;
            }else{
                $gender = antiExploit($_POST["gender"]);
                $pattern = '/^[MF]$/';
                if (!preg_match($pattern, $gender)){
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
                        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                        if ($con -> connect_errno) { //Check it is the connection succesful
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                        }else{
                            $sql = "SELECT * from staff WHERE email = '$email'";
                            if ($result=$con->query($sql)){
                                if ($result->num_rows !=0){
                                    while($row = $result->fetch_object()){
                                        if ($row->staffID != $staffID){
                                            array_push($error,"<b>Email</b> is unavailable.");
                                            $errorEmail = true;
                                        }
                                    }
                                    
                                }
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
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
                    $errorContactNumber = true;
                }else{
                    $pattern = '/^([0-9]{2}|[0-9]{3})-([0-9]+)$/';
                    if(!preg_match($pattern,$contactNumber)){
                        array_push($error,"<b>Contact Number</b> is of invalid format. Format: xxx-xxxxxxxx");
                        $errorContactNumber = true;
                    }
                }

            }
            if (empty($error)){
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "UPDATE staff SET userName=?, name=?, gender=?,email=?, birthDate=?,contactNumber = ?,lastUpdate = ? WHERE staffID = ?";
                    $stmt=$con->prepare($sql);
                    $date = date('Y-m-d H:i:s');
                    $stmt -> bind_param('ssssssss',$userName,$name,$gender,$email,$birthDate,$contactNumber,$date,$staffID);
                    if ($stmt->execute()){
                        $stmt->free_result();
                        $con->close();
                        createAuditLog("Staff", "Modified their own account details", $staffID." modified their own account profile.");
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: dashboard.php');
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        $stmt->free_result();
                        $con->close();
                    }
                }
                
            }else{
                //Display error message
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        //Check password section
        }else if ($_POST["type"]=="modifyPassword"){
            if(empty($_POST["currentpassword"])){
                array_push($error,"<b>Current Password</b> cannot empty.");
                $errorCurrentPassword = true;
            }else{
                $currentpassword = antiExploit($_POST["currentpassword"]);
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "SELECT password from staff WHERE staffID = '$staffID'";
                    if ($result=$con->query($sql)){
                        while($row=$result->fetch_object()){
                            if (!password_verify($currentpassword, $row->password)){
                                 if (isset($_SESSION["wrongCount"])){
                                    $_SESSION["wrongCount"]++;
                                }else{
                                    $_SESSION["wrongCount"] = 0;
                                }
                                array_push($error,"Wrong <b>Current Password</b>.");
                                $errorCurrentPassword = true;
                            }
                        }
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                    }
                    $result->free();
                    $con->close();
                }
            }
            if (empty($_POST["password"])){
                array_push($error,"<b>New Password</b> cannot empty.");
                $errorPassword = true;
            }else{
                $password = antiExploit($_POST["password"]);
                if (strlen($password)<8||strlen($password)>32){
                    array_push($error,"<b>New Password</b> must between 8 to 32 characters.");
                    $errorPassword = true;
                }else{
                    $pattern = '/^[\w]+$/';
                    if(!preg_match($pattern, $password)){
                        array_push($error,"<b>New Password</b> can contains only uppercase and lowercase alphabet, digit and underscore.");
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
            if (empty($error)){
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "UPDATE staff SET password=?, lastUpdate = ? WHERE staffID=?";
                    $stmt = $con->prepare($sql);
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $date = date('Y-m-d H:i:s');
                    $stmt->bind_param('sss', $password,$date,$staffID);
                    if (isset($_SESSION["wrongCount"])){
                        $wrongCount = $_SESSION["wrongCount"];
                    }else{
                        $wrongCount = 0;
                    }
                    $id = $_SESSION["staffId"];
                    createAuditLog("Staff", "Changed their own account password", "$id has changed their account password with $wrongCount time wrong username or password");
                    unset($_SESSION["wrongCount"]);
                    $stmt->execute();
                    if($stmt->affected_rows >0){
                        $stmt->free_result();
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: dashboard.php');
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $stmt->free_result();
                }
                $con->close();
                
            }else{
                //Display error message
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
    }