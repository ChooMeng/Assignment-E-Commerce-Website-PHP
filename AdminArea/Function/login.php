<?php
    session_start();
    include '../settings.php';
    include 'Function/helper.php';
    date_default_timezone_set(TIMEZONE);
    if (isset($_SESSION["staffId"])){
        /*Get the username of staff and check the login credential is valid because the staff account maybe get removed
        but the account still not yet logged out*/
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $loginID = $_SESSION["staffId"];
            $sql = "SELECT userName from staff WHERE staffID='$loginID'";
            if($result = $con->query($sql)){
                if (($result->num_rows)>0){
                    header('location: dashboard.php');
                }else{
                    unset($_SESSION["staffId"]);
                }

            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }
            $result->free();
            $con->close();
        }
    }
    
    function detectAdminLoginError()
    {
        global $username, $password;
        $error = array();
        //array hold error msg
        $valid = false;
        //check username and password
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT staffID,userName, password,status from staff WHERE username='$username'";
            if($result = $con->query($sql)){
                while($row = $result->fetch_object()){
                    if ($row->status == "Terminated"){
                        break;
                    }
                    if ($row->status == "Suspended"){
                        $error['account'] = 'Your account has been <b>suspended</b>.';
                        return $error;
                        break;
                    }
                    if (password_verify($password, $row->password)){
                        $valid = true;
                        $_SESSION["staffId"] = $row->staffID;
                        $result->free();
                        $date = date('Y-m-d H:i:s');
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
                        $sql = "UPDATE staff SET lastlogin=?,ipAddress=? WHERE staffID='$row->staffID'";
                        $stmt = $con->prepare($sql);
                        //pass in value into ???? in sql statement
                        //parameterized query
                        //s - string type
                        $stmt->bind_param('ss',$date,$ipAddress);
                        $id = $_SESSION["staffId"];
                        if (isset($_SESSION["wrongCount"])){
                            $wrongCount = $_SESSION["wrongCount"];
                        }else{
                            $wrongCount = 0;
                        }
                        createAuditLog("Staff", "Staff account logged in", "$id has succesful logged in to the website with $wrongCount time wrong username or password");
                        unset($_SESSION["wrongCount"]);
                        if(!$stmt->execute()){
                             echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }
                        break;
                    }
                }
            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }

            $con->close();
            
        }
        if ($valid == false){
            if (isset($_SESSION["wrongCount"])){
                $_SESSION["wrongCount"]++;
            }else{
                $_SESSION["wrongCount"] = 0;
            }
            $error['account'] = 'Invalid <b>Username</b> or <b>Password</b>.';
        }
        return $error;
        
    }
    if(isset($_POST['login']))
    {
        $username = trim($_POST['username']); 
        $password = trim($_POST['password']);

        $error = detectAdminLoginError(); // call function for validation
        if(empty($error)) //display output
        {
            header("location:loginsuccessful.php");
        }
        
    }
    function displayErrorMessage(){
        global $error;
        if(!empty($error)){
            printf('<ul class="error"><li>%s</li></ul>
                         ', implode('</li><li>', $error));
        }
    }


?>
