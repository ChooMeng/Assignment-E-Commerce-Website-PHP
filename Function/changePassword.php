<?php
    $clientID = $_SESSION["clientId"];
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
        if (isset($_POST['changepassword'])){
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
        if (isset($_POST['changepassword'])){
            
            if (empty($_POST["currentPwd"])){
                array_push($error,"<b>Password</b> cannot empty.");
                $errorPassword = true;
            }else{
                $password = trim($_POST['currentPwd']);
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "SELECT password from client WHERE clientID ='$clientID'";
                    if($result = $con->query($sql)){
                        while($row = $result->fetch_object()){
                            if (!password_verify($_POST["currentPwd"], $row->password)){
                                $valid = true;
                                
                                array_push($error,"Invalid <b>Password</b>.");
                                $errorPassword = true;
                                break;
                            }
                        }
                        $result->free();
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }

                    $con->close();

                }
            }
            if (empty($error)){
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: newPassword.php');
            }
        }
    }

