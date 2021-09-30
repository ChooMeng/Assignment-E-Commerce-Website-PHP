<?php
    //Verify staffID whether it is valid
    if (!isset($_GET['staffId'])){
        setcookie("error","staffId",time()+1,"staffs.php");
        header('Location: staffs.php');
    }
    //Department List
    $departmentArr = array(""=>"--Selected One--","IT"=>"Information Technology","Acc"=>"Accounting","Admin"=>"Administration","CS"=>"Customer Service","Finance"=>"Finance","HR"=>"Human Resources","MA"=>"Marketing & Advertising","Production"=>"Production","Sales"=>"Sales","Shipping"=>"Shipping");
    $staffID = $_GET['staffId'];
    function details(){ //Display staff details
        global $staffID,$departmentArr;
        //Read staff details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from staff WHERE staffID = '$staffID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                      <p id="lastUpdate">Last update: %s</p>
                      <p id="staffId">STAFF ID: %s</p>
                      <div class="rightSide">
                          <p class="field">Username</p>
                          <p class="data">%s</p>
                          <p class="field">Contact Number</p>
                          <p class="data">%s</p>
                          <p class="field">Birth Date</p>
                          <p class="data">%s</p>
                          <p class="field">Position</p>
                          <p class="data">%s</p>
                          <p class="field">Last Login</p>
                          <p class="data">%s</p>
                          <p class="field">Account Status</p>
                          <p class="data">%s</p>
                      </div>
                      <div class="leftSide">
                          <p class="field">Name</p>
                          <p class="data">%s</p>
                          <p class="field">Gender</p>
                          <p class="data">%s</p>
                          <p class="field">Email</p>
                          <p class="data">%s</p>
                          <p class="field">Department</p>
                          <p class="data">%s</p>
                          <p class="field">Join Date</p>
                          <p class="data">%s</p>
                          <p class="field">IP Address</p>
                          <p class="data">%s</p>
                          <br>
                          <br>
                      </div>  
                  ',$row->lastUpdate,$staffID,$row->userName,$row->contactNumber,$row->birthDate,$row->position,empty($row->lastLogin)?"Not logged in":$row->lastLogin,$row->status,$row->name,
                            $row->gender=="M"?"Male":"Female",$row->email,$departmentArr[$row->department],$row->joinDate,empty($row->ipAddress)?"Not logged in":$row->ipAddress);
                }
                if ($result->num_rows ==0){
                    setcookie("error","staffId",time()+1,"staffs.php");
                    header('Location: staffs.php');
                }
            }
        }
    }