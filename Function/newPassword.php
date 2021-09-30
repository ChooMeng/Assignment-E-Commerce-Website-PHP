<?php
    $error = array();
    if (!isset($_POST['changepassword'])&&!isset($_POST['changepasswordnew'])){
        header('Location: changePassword.php');
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
        if (isset($_POST['changepasswordnew'])){
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
        if (isset($_POST['changepasswordnew'])){
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
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    $databaseError = $con->connect_error;
                }else{
                    //Update the password
                    $sql = "UPDATE client SET password=?, lastUpdate = ? WHERE clientID=?";
                    $stmt = $con->prepare($sql);
                    $password = password_hash($newPassword, PASSWORD_DEFAULT);
                    $date = date('Y-m-d H:i:s');
                    $clientID = $_SESSION['clientId'];
                    $stmt->bind_param('sss', $password,$date,$clientID);
                    if($stmt->execute()){
                        $stmt->free_result();
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: profile.php');
                    }else{
                        $databaseError = $stmt->error;
                    }
                    $stmt->free_result();
                }
                $con->close();
            }
        }
    }

