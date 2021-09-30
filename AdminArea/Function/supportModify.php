<?php
    include 'Function/helper.php';
    //Verify supportID
    if (!isset($_GET['supportId'])){
        setcookie("error","supportId",time()+1,"supports.php");
        header('Location: supports.php');
    }
    $supportID = $_GET['supportId'];
    //Get support details
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from support WHERE supportID = '$supportID'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
               $priorityA = $row->priority;
               $clientID = $row->clientID;
               $subject = $row->subject;
               $statusA = $row->status;
            }
            if ($result->num_rows ==0){
                setcookie("error","staffId",time()+1,"staffs.php");
                header('Location: staffs.php');
            }
        }
        $result->free();
        $con->close();
    }
    
    //Status list
    $status = array("Open","Pending","Resolved","Closed","Canceled");
    //Display status list
    function statusList(){
        global $statusA;
        foreach($GLOBALS['status'] as $value){
            $selected = "";
            if (isset($_POST["status"])){
                if ($_POST["status"]==$value){
                    $select = "selected";
                }
            }else if ($statusA==$value){
                $select = "selected";
            }
            printf("<option value='$value' $selected>$value");
        }
    }
    //Priority list
    $priority = array("High","Normal","Low");
    //Display priority list
    function priorityList(){
        global $priorityA;
        foreach($GLOBALS['priority'] as $value){
            $selected = "";
            if (isset($_POST["priority"])){
                if ($_POST["priority"]==$value){
                    $select = "selected";
                }
            }else if ($priorityA==$value){
                $select = "selected";
            }
            printf("<option value='$value' $selected>$value");
        }
    }
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
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        // Check support detials section
        if ($_POST["type"]=="modifyDetails"){
            if(empty($_POST["clientId"])){
                array_push($error,"<b>ClientID</b> cannot empty.");
                $errorClientId = true;
            }else{
                $clientID = antiExploit($_POST["clientId"]);
                if (strlen($clientID)!=7){
                    array_push($error,"<b>ClientID</b> client id must 7 character.");
                    $errorClientId = true;
                }else{
                    $pattern = '/^[A-Z][0-9]{6}$/';
                    if (!preg_match($pattern, $clientID)){
                        array_push($error,"<b>ClientID</b> invalid client id.");
                        $errorClientId = true;
                    }else if (!isClientIDExist($clientID)){
                        array_push($error,"<b>ClientID</b> invalid client id.");
                        $errorClientId = true;
                    }
                }

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
            if(empty($_POST['status'])){
                array_push($error,"Please select a <b>Status</b>.");
                $errorStatus = true;
            }else{
                $statusA = antiExploit($_POST["status"]);
                if(!in_array($statusA, array_keys($status))){
                    array_push($error,"Please select a <b>Status</b> Must be a valid status.");
                    $errorStatus = true;
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
                    //Update the password
                    $sql = "UPDATE support SET clientID=?, status = ?, priority=?,subject=? WHERE supportID=?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('sssss', $clientID,$statusA,$priorityA,$subject,$supportID);
                    $id = $_SESSION["staffId"];
                    if($stmt->execute()){
                        $stmt->free_result();
                        $con->close();
                        createAuditLog("Support", "Modified $supportID support ticket details", "$id has changed $supportID ticket details");
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: staffs.php');
                        
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $stmt->free_result();
                }
                $con->close();
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: supports.php');
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

