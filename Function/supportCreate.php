<?php
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        $databaseError = $con->connect_error;
    }else{
        $sql = "SELECT * from client WHERE clientID = '".$_SESSION['clientId']."'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
               $name = $row->name;
               $email = $row->email;
            }
            if ($result->num_rows ==0){
                unset($_SESSION["clientId"]);
                header('location: login.php');
            }
        }
        $result->free();
    }
    $con->close();
    //Priority list
    $priority = array("High","Normal","Low");
    //Display priority list
    function priorityList(){
        
        foreach($GLOBALS['priority'] as $value){
            if (isset($_POST["priority"])&&$GLOBALS['priorityA']==$value){
                $selected = "selected";
                
            }else{
                if ($value=="Normal"){
                    $selected = "selected";
                }else{
                    $selected = "";
                }
                
            }
            printf("<option value='$value' $selected>$value");
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
    //Display error message
    function displayErrorMessage(){
        if (!empty($GLOBALS['error'])){
            echo "<br/><br/><ul class='error'>";
            foreach($GLOBALS['error'] as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
        }
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty($_POST["message"])){
            array_push($error,"<b>Message</b> cannot empty.");
            $errorMessage = true;
        }else{
            $message = antiExploit($_POST["message"]);
        }
        if (empty($_POST["subject"])){
            array_push($error,"<b>Subject</b> cannot empty.");
            $errorSubject = true;
        }else{
            $subject = antiExploit($_POST["subject"]);
            if (strlen($subject)>300){
                array_push($error,"<b>Subject</b> cannot more than 300 character.");
                $errorSubject = true;
            }
        }
        if (isset($_FILES['attachment'])){
            $file = $_FILES["attachment"];
            if ($file['error']!=UPLOAD_ERR_NO_FILE){
                if ($file['error']>0){
                    switch($file['error']){
                        case UPLOAD_ERR_NO_FILE:
                            array_push($error,"No <b>File</b> was selected.");
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            array_push($error,"<b>File</b> uploaded is too large!");
                            break;
                        default:
                            array_push($error,"There was an error while uploading the <b>File</b>!");
                            break;
                    }
                }else if($file['size']>31457280){
                    array_push($error,"<b>File</b> uploaded is too large. Maximum 30MB!");
                }else{
                    //extract file extension - jpg,png,gif,txt,pdf
                    $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
                    if ($ext!='jpg'&&$ext!="jpeg"&&$ext != "gif"&&$ext !="png"&&$ext!="txt"&&$ext!="pdf"){
                        array_push($error,"<b>File</b> Only jpg, gif,png and txt format are allowed");
                    }else{
                        if (empty($error)){
                            $save_as = uniqid("",true).'.'.$ext;
                            move_uploaded_file($file['tmp_name'],'uploads/'.$save_as);
                        }

                    }
                } 
            }
            
        }
        
        if(empty($_POST['priority'])){
            array_push($error,"Please select a <b>Priority</b>.");
            $errorPriority = true;
        }else{
            $priorityA = antiExploit($_POST["priority"]);
            if(!in_array($priorityA, array_keys($priority))){
                array_push($error,"Please select a <b>Priority</b> Must be a valid priority.");
                $errorPriority = true;
            }
        }
        if (empty($error)){
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT * from support";
                if($result = $con->query($sql)){
                    $count = $result->num_rows+1;
                    $result->free();
                    $supportID = "T".$count;
                    $msgID = uniqid("",true);
                    $oldStatus = "";
                    $status = "Open";
                    $date = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO support VALUES (?,?,?,?,?,?,?,?)";
                    $stmt = $con -> prepare($sql);
                    $stmt -> bind_param('ssssssss',$supportID,$_SESSION['clientId'],$subject,$date,$status,$priorityA,$msgID,$oldStatus);
                    if ($stmt->execute()){
                        $stmt->free_result();
                        
                        $sql = "INSERT INTO message VALUES (?,?,?,?,?)";
                        $stmt = $con -> prepare($sql);
                        $stmt -> bind_param('sssss',$msgID,$_SESSION['clientId'],$date,$message,$save_as);
                        if ($stmt->execute()){
                            $stmt->free_result();
                            $sql = "INSERT INTO supportmessage VALUES (?,?)";
                            $stmt = $con -> prepare($sql);
                            $stmt -> bind_param('ss',$supportID,$msgID);
                            if ($stmt->execute()){
                                $stmt->free_result();
                                $con -> close();
                                header('HTTP/1.1 307 Temporary Redirect');
                                header('Location: supports.php');
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                            }
                        }else{
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
            }
        }
    }