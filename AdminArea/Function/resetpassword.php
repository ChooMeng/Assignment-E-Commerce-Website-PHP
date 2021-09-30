<?php
    $error = array();
    require '../settings.php';
    if (isset($_SESSION["staffId"])){
        header('location: index.php');
    }
    if (empty($_GET["resetCode"])){
        header('Location: login.php');
    }
    if (empty($_GET["email"])){
        header('Location: login.php');
    }
    date_default_timezone_set(TIMEZONE);
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from staffresetpassword WHERE email='".$_GET["email"]."' AND resetCode='".$_GET['resetCode']."'";
        if($result = $con->query($sql)){
            if (($result->num_rows)==0){
                header('Location: login.php');

            }
            while($row = $result->fetch_object()){
                
                $requestTimeStored = $row->request_time;
                $requestTime = date('Y-m-d H:i:s', strtotime($requestTimeStored . ' +1 hour'));
                $localTime = date('Y-m-d H:i:s');
                if ($localTime>$requestTime){
                    $sql = "DELETE FROM staffresetpassword WHERE email=?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('s',$_GET["email"]);
                    if($stmt->execute()){
                        $stmt->free_result();
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: login.php');
                    }
                }
            }
            $result->free();
        }else{
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }
        $con->close();
    }
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
        if (isset($_POST['newPasswordSubmit'])){
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
        if (isset($_POST['newPasswordSubmit'])){
            if (empty($_POST["newPassword"])){
                array_push($error,"<b>New Password</b> cannot empty.");
                $errorPassword = true;
            }else{
                $newPassword = antiExploit($_POST["newPassword"]);
                if (strlen($newPassword)<8||strlen($newPassword)>32){
                    array_push($error,"<b>New Password</b> must between 8 to 32 characters.");
                    $errorPassword = true;
                }else{
                    $pattern = '/^[\w]+$/';
                    if(!preg_match($pattern, $newPassword)){
                        array_push($error,"<b>New Password</b> can contains only uppercase and lowercase alphabet, digit and underscore.");
                        $errorPassword = true;
                    }
                }
            }
            if(empty($_POST["confirmPassword"])){
                array_push($error,"<b>Confirm Password</b> cannot empty.");
                $errorConfirmPassword = true;
            }else{
                $confirmpassword = antiExploit($_POST["confirmPassword"]);
                if(isset($newPassword)&&($newPassword!=$confirmpassword)){
                    array_push($error,"<b>Confirm Password</b> must match the password.");
                    $errorConfirmPassword = true;
                }
            }
            if (empty($error)){
                //database thing
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "UPDATE staff SET password=?, lastUpdate = ? WHERE email=?";
                    $stmt = $con->prepare($sql);
                    $password = password_hash($newPassword, PASSWORD_DEFAULT);
                    $date = date('Y-m-d H:i:s');
                    $email = antiExploit($_GET['email']);
                    $stmt->bind_param('sss', $password,$date,$email);
                    if($stmt->execute()){
                        $stmt->free_result();
                        $sql = "DELETE FROM staffresetpassword WHERE email=?";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('s',$email);
                        if($stmt->execute()){
                            $stmt->free_result();
                            header('HTTP/1.1 307 Temporary Redirect');
                            header('Location: successreset.php');
                        }
                        
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $stmt->free_result();
                }
                $con->close();
            }
        }
    }

