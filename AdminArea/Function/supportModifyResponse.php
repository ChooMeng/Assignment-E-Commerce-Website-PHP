<?php
    include 'Function/helper.php';
    //Verify supportID
    if (!isset($_GET['supportId'])){
        setcookie("error","supportId",time()+1,"supports.php");
        header('Location: supports.php');
    }
    $supportID = $_GET['supportId'];
    //Verify msgID
    if (!isset($_GET['msgId'])){
        setcookie("error","msgId",time()+1,"viewSupport.php");
        header("Location: viewSupport.php?supportId=$supportID");
    }
    $msgID = $_GET['msgId'];
    $error = array();
    //Display error icon
    function displayError(){
        echo "<img src='../Media/error.png'>";
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Gender List
    $genderArr = array(
        "F" => "Female",
        "M" => "Male"
    );
    //Get ticket responses
    function getMessages(){
        global $supportID;
        $msgID = $GLOBALS['msgID'];
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT messageID, responserID, time, message, attachment FROM message WHERE messageID = '$msgID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    $check= substr($row->responserID, 0,1); //Get first letter of the id as firsts letter represent data type
                    if ($check=="C"){ //Customer
                        printf("<div id='msg-%s' class='custMsg'>
                                <div class='msgContents'>
                                    <div class='profile'>
                                        %s
                                    </div>
                                    <textarea name='response' class='response'>%s</textarea>
                                    <p class='timeframe'>%s</p>
                                    <p>%s<input type='file' id='attachment' name='attachment' value='%s'><input type='hidden' id='existFile' name='existFile' value='%s'></p>
                                </div>
                            </div>
                        ",$msgID,getMessageReplyRecipient($row->responserID),$row->message,$row->time,attachmentExist($row->attachment),isset($_POST["attachment"])?$row->attachment:'',$row->attachment);
                    }else{
                        printf("<div id='msg-%s' class='staffMsg'>
                                <div class='msgContents'>
                                    <div class='profile'>
                                        %s
                                    </div>
                                    <textarea name='response' class='response'>%s</textarea>
                                    <p class='timeframe'>%s</p>
                                    <p>%s<input type='file' id='attachment' name='attachment' value='%s'><input type='hidden' id='existFile' name='existFile' value='%s'></p>
                                </div>
                            </div>
                        ",$msgID,getMessageReplyRecipient($row->responserID),$row->message,$row->time,attachmentExist($row->attachment),isset($_POST["attachment"])?$row->attachment:'',$row->attachment);
                    }
                    
                }
                //Verify messageID whether it is valid
                if ($result->num_rows ==0){
                    setcookie("error","msgId",time()+1,"viewSupport.php");
                    header("Location: viewSupport.php?supportId=$supportID");
                }
            }
            $result->free_result();
            $con->close();
            
        }
    }
    
    function attachmentExist($id){
        
        if (!empty($id)){
            return "<br><img onclick='zoomSlide(this)' src='../uploads/$id' width='64' height= '64' style='cursor:pointer;'>";
        }
        
    }
    //Get the person name,department and position of last reply
    function getMessageReplyRecipient($id){
        global $genderArr;
        $check= substr($id, 0,1); //Get first letter of the string as firsts letter represent data type
        if ($check=="C"){ //Customer
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name,gender, lastLogin FROM client WHERE clientID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                        $gender = $row->gender;
                        $lastLogin = $row->lastLogin;
                    }
                }
            }
            empty($lastLogin)?$lastLogin = "No result":$lastLogin;
            $lastReply = "$id<br/>$name<br/>Customer<br/><br/>$genderArr[$gender]<br/><br/>Last Login<br/>$lastLogin";
        }
        if ($check=="S"){ //Staff
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name,gender,lastLogin,department, position FROM staff WHERE staffID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                        $department = $row->department;
                        $position = $row->position;
                        $gender = $row->gender;
                        $lastLogin = $row->lastLogin;
                    }
                }
            }
            empty($lastLogin)?$lastLogin = "No result":$lastLogin;
            $lastReply = "$id<br/>$name<br/>$department<br/>$position<br/><br/>$genderArr[$gender]<br/><br/>Last Login<br/>$lastLogin";
        }
        return $lastReply;
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["response"])){
            array_push($error,"<b>Response</b> cannot empty.");
            $errorResponse = true; 
        }else{
            $response = $_POST['response'];
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
                            move_uploaded_file($file['tmp_name'],'../uploads/'.$save_as);
                        }

                    }
                } 
            }
        }
        if (empty($error)){
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "UPDATE message SET message=?, attachment = ? WHERE messageID = ?";
                $stmt = $con -> prepare($sql);
                if (isset($save_as)){
                    $attachment = $save_as;
                }else{
                    if (!empty($_POST['existFile'])){
                        $attachment = $_POST['existFile'];
                    }else{
                        $attachment = "";
                    }
                }
                $stmt->bind_param('sss', $response,$attachment,$msgID);
                if ($stmt->execute()){
                    $stmt->free_result();
                    $con -> close();
                    createAuditLog("Support","Support ticket responses modified","MessageID $messageID for SupportID $supportID modified.");
                    setcookie("success","editMsg",time()+1,"viewSupport.php");
                    header("Location: viewSupport.php?supportId=$supportID");
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
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
    }