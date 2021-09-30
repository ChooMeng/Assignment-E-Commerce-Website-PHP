<?php
    include 'settings.php';
    if (isset($_SESSION["clientId"])){
        header('location: index.php');
    }
    if(isset($_SESSION["successfulReset"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Succesful reset the client account password.");}, 100);</script>';
        unset($_SESSION["successfulReset"]);
    }
    function detectClientLoginError()
    {
        global $username, $password;
        $error = array();
        //array hold error msg
        $valid = false;
        //check username and password
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        //Display succesful terminate account message
        
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT clientID,userName, password,status from client WHERE username='$username'";
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
                        $_SESSION["clientId"] = $row->clientID;
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
                        $sql = "UPDATE client SET lastlogin=?,ipAddress=? WHERE clientID='$row->clientID'";
                        $stmt = $con->prepare($sql);
                        //pass in value into ???? in sql statement
                        //parameterized query
                        //s - string type
                        $stmt->bind_param('ss',$date,$ipAddress);
                        $id = $_SESSION["clientId"];
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
            $error['account'] = 'Invalid <b>Username</b> or <b>Password</b>.';
        }
        return $error;
    }
    function showRegisterSuccesful(){
        if (isset($_POST['register'])){
            printf("<p class='info'>Succesful registered the account. You may login now.</p>");
        }
    }
    if(isset($_POST['login']))
    {
        
        if (isset($_POST['userName'])){
            $username = trim($_POST['userName']); 
        }else{
            $username = "";
        }
        if (isset($_POST['password'])){
            $password = trim($_POST['password']);
        }else{
            $password = "";
        }
        

        $error = detectClientLoginError(); // call function for validation
        if(empty($error)) //display output
        {
            if (isset($_SESSION['back'])&&isset($_GET['back'])){
                header("location:".$_SESSION['back']);
            }else{
                header("location:index.php");
            }
            
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
