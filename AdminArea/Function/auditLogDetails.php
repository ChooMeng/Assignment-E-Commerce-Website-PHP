<?php
    include 'Function/helper.php';
    //Verify staffID whether it is valid
    if (!isset($_GET['auditId'])){
        setcookie("error","auditId",time()+1,"auditlog.php");
        header('Location: auditlog.php');
    }
    $auditID = $_GET['auditId'];
    function details(){ //Display audit log details
        global $auditID;
        //Read audit log details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from auditlog WHERE auditID = '$auditID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                        <p id="auditId">AUDIT ID: %s</p>
                        <p class="field">Log Time</p>
                        <p class="data">%s</p>
                        <p class="field">Staff</p>
                        <p class="data">%s</p>
                        <p class="field">IP Address</p>
                        <p class="data">%s</p> 
                        <p class="field">Type</p>
                        <p class="data">%s</p> 
                        <p class="field">Changes</p>
                        <p class="data">%s</p> 
                        <p class="field">Details</p>
                        <p class="textdata">%s</p> 
                    ',$auditID,$row->logTime, staffIDToName($row->staffID),$row->ipAddress,$row->type,$row->changes,$row->details);
                }
                if ($result->num_rows ==0){
                    setcookie("error","auditId",time()+1,"auditlog.php");
                    header('Location: auditlog.php');
                }
            }
        }
    }