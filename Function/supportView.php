<?php
    //Verify supportID whether it is valid
    if (!isset($_GET['supportId'])){
        setcookie("error","supportId",time()+1,"supports.php");
        header('Location: supports.php');
    }
    $supportID = $_GET['supportId'];
    //Display succesful update support status message
    if(isset($_SESSION["updated"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Update the support ticket status.");}, 100);</script>';
        unset($_SESSION["updated"]);
    }
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * FROM support WHERE supportID = '$supportID';";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                if ($row->status=="Removed"||$row->clientID!=$_SESSION['clientId']){
                    setcookie("error","supportId",time()+1,"supports.php");
                    header('Location: supports.php');
                }
            }
            //Verify supportID whether it is valid
            if ($result->num_rows ==0){
                setcookie("error","supportId",time()+1,"supports.php");
                header('Location: supports.php');
            }
        }
        $result->free();
    }
    $con->close();
    //Send succesful reply the ticket notification
    if(isset($_COOKIE['succesful'])){
        echo '<script>setTimeout(function(){addNotification("green","<b>Succesful</b>! Reply to the ticket.");}, 100);</script>';
        unset($_POST['response']);
        unset($_POST['attatchment']);
        unset($_POST['status']);
    }
    //Gender List
    $genderArr = array(
        "F" => "Female",
        "M" => "Male"
    );
     //Display customer details and support ticket details
    function details(){
        $supportID = $GLOBALS['supportID'];
        global $status,$supportID;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT s.supportID,s.clientID,userName,contactNumber,birthDate,lastLogin,name,email,gender,joinDate,subject,createdTime, s.status, priority, responserID, time "
                    . "FROM support s JOIN client c ON c.clientID = s.clientID JOIN message m ON s.lastResponse = m.messageID "
                    . "WHERE s.supportID = '$supportID';";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    
                    printf('
                        <p id="supportId">Support ID: %s</p>  
                        <div class="ticketInfo">
                             <h2 style="text-align:center">Ticket Information</h2>
                             <table class="rightSide">
                                 <tr>
                                     <td width="35%%">Last Reply : </td>
                                     <td>%s</td>
                                 </tr>
                                 <tr>
                                     <td width="35%%">Last Reply Time : </td>
                                     <td>%s</td>
                                 </tr>

                             </table>
                             <table class="leftSide">
                                 <tr>
                                     <td width="35%%">Subject : </td>
                                     <td>%s</td>
                                 </tr>
                                 <tr>
                                     <td width="35%%">CreatedTime : </td>
                                     <td>%s</td>
                                 </tr>
                                 <tr>
                                     <td width="35%%">Status : </td>
                                     <td>%s</td>
                                 </tr>
                                 <tr>
                                     <td width="35%%">Priority : </td>
                                     <td>%s</td>
                                 </tr>
                             </table>
                         </div>
                     ', $supportID,getReplyRecipient($row->responserID),$row->time,$row->subject,$row->createdTime,$row->status,$row->priority);
                }
            }
            $result->free();
        }
        $con->close();
    }
    //Get the person name,department and position of last reply
    function getReplyRecipient($id){
        $check= substr($id, 0,1); //Get first letter of the string as firsts letter represent data type
        if ($check=="C"){ //Customer
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name FROM client WHERE clientID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                    }
                }
            }
            $result->free();
            $con->close();
            $lastReply = "$id<br>$name<br/>Customer";
        }
        if ($check=="S"){ //Staff
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT name,department, position FROM staff WHERE staffID = '$id'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        $name = $row->name;
                        $department = $row->department;
                        $position = $row->position;
                    }
                }
            }
            $lastReply = "$id<br>$name<br/>$department<br/>$position";
        }
        return $lastReply;
    }
     //Get support ticket responses
    function getMessages(){
        global $supportID,$genderArr;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT sm.messageID, responserID, time, message, attachment FROM supportmessage sm JOIN message m ON sm.messageID = m.messageID WHERE sm.supportID = '$supportID'";
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
                                   
                                    <p class='responses'>%s</p>
                                    <br/>
                                    <br/>
                                    <p class='timeframe'>%s</p>
                                    <p>%s</p>
                                </div>
                            </div>
                        ",$row->messageID,getMessageReplyRecipient($row->responserID),$row->message,$row->time, attachmentExist($row->attachment));
                    }else{
                        printf("<div id='msg-%s' class='staffMsg'>
                                    <div class='msgContents'>
                                        <div class='profile'>
                                            %s
                                        </div>
                                        <p class='responses'>%s</p>
                                        <br/>
                                        <br/>
                                        <p class='timeframe'>%s</p>
                                        <p>%s</p>

                                    </div>
                                </div>
                            ",$row->messageID,getMessageReplyRecipient($row->responserID),$row->message ,$row->time, attachmentExist($row->attachment));
                    }
                }
            }
        }
    }
    function attachmentExist($id){
        if (!empty($id)){
            return "<br><img onclick='zoomSlide(this)' src='uploads/$id' width='64' height= '64' style='cursor:pointer;'>";
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
    //Display error messages
    function displayErrorMessage(){
        if (!empty($GLOBALS['error'] )){
            echo "<ul class='error'>";
            foreach($GLOBALS['error'] as $value){
                echo "<li>$value</li>";
            }
        echo "</ul>";
        }
        
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty($_POST["response"])){
            array_push($error,"<b>Response</b> cannot empty.");
            $errorResponse = true;
        }else{
            $response = antiExploit($_POST["response"]);
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
        if (empty($error)){
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "UPDATE support SET status=?, lastResponse = ? WHERE supportID = ?";
                $msgID = uniqid("",true);
                $date = date('Y-m-d H:i:s');
                $stmt = $con -> prepare($sql);
                $statusA = "Pending";
                $stmt->bind_param('sss', $statusA,$msgID,$supportID);
                $clientID = $_SESSION['clientId'];
                
                if ($stmt->execute()){
                    $stmt->free_result();
                    $sql = "INSERT INTO message VALUES (?,?,?,?,?)";
                    $stmt = $con -> prepare($sql);
                    $stmt -> bind_param('sssss',$msgID,$clientID,$date,$response,$save_as);
                    if ($stmt->execute()){
                        $stmt->free_result();
                        $sql = "INSERT INTO supportmessage VALUES (?,?)";
                        $stmt = $con -> prepare($sql);
                        $stmt -> bind_param('ss',$supportID,$msgID);
                        if ($stmt->execute()){
                            $stmt->free_result();
                            $con -> close();
                            setcookie("succesful","reply",time()+1,"viewSupport.php");
                            header("Refresh:0");
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