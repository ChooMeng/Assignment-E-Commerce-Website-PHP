<?php
    include 'Function/helper.php';
    //Gender List
    $genderArr = array(
        "F" => "Female",
        "M" => "Male"
    );
    //Status List
    $statusArr = array(
        "A"=>"Active",
        "S"=>"Suspended",
        "T"=>"Terminated"
    );
    //Department List
    $departmentArr = array(""=>"--Selected One--","IT"=>"Information Technology","Acc"=>"Accounting","Admin"=>"Administration","CS"=>"Customer Service","Finance"=>"Finance","HR"=>"Human Resources","MA"=>"Marketing & Advertising","Production"=>"Production","Sales"=>"Sales","Shipping"=>"Shipping");
    //Display gender list
    function genderList(){
        foreach($GLOBALS['genderArr'] as $key => $value){
            echo "<input type='radio' id='$value' name='gender' value='$key'".(isset($_POST["gender"])?($_POST["gender"]==$key?"checked":""):"").">";
            echo "<label for='$key'>$value</label>";
        }
    }
    //Display department list
    function departmentList(){
        foreach($GLOBALS['departmentArr'] as $key => $value){
            echo "<option value='$key'".(isset($_POST["department"])?($_POST["department"]==$key?"selected":""):"").">$value</option>";
        }
    }
    //Display status list
    function statusList(){
        foreach($GLOBALS['statusArr']as$value){
             echo "<option value='$value'".(isset($_POST["status"])?($_POST["status"]==$value?"selected":""):"").">$value</option>";
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
        if (empty($_POST["username"])){
            array_push($error,"<b>Username</b> cannot empty.");
            $errorUserName = true;
        }else{
            $username = antiExploit($_POST["username"]);
            if (strlen($username)>40){
                array_push($error,"<b>Username</b> cannot more than 40 characters.");
                $errorUserName = true;
            }else{
                $pattern = '/^[A-Za-z0-9]+$/';
                if(!preg_match($pattern, $username)){
                    array_push($error,"<b>Username</b> can contains only uppercase and lowercase alphabet and numbers");
                    $errorUserName = true;
                }else{
                    if (isStaffUserNameExist($username)){
                        array_push($error,"<b>Username</b> is unavailable");
                        $errorUserName = true;
                    }
                }
            }
        }
        if (empty($_POST["name"])){
            array_push($error,"<b>Name</b> cannot empty.");
            $errorName = true;
        }else{
            $name = antiExploit($_POST["name"]);
            if (strlen($name)>40){
                array_push($error,"<b>Name</b> cannot more than 40 characters.");
                $errorName = true;
            }else{
                $pattern = '/^[A-Za-z\s@\'\.-\/]+$/';
                if(!preg_match($pattern, $name)){
                    array_push($error,"<b>Name</b> can contains only uppercase and lowercase alphabet, space, alias, comma, single-quote, dot, dash and slash.");
                    $errorName = true;
                } 
            }
        }
        if (empty($_POST["password"])){
            array_push($error,"<b>Password</b> cannot empty.");
            $errorPassword = true;
        }else{
            $password = antiExploit($_POST["password"]);
            if (strlen($password)<8||strlen($password)>32){
                array_push($error,"<b>Password</b> must between 8 to 32 characters.");
                $errorPassword = true;
            }else{
                $pattern = '/^[\w]+$/';
                if(!preg_match($pattern, $password)){
                    array_push($error,"<b>Password</b> can contains only uppercase and lowercase alphabet, digit and underscore.");
                    $errorPassword = true;
                }
            }
        }
        if(empty($_POST["confirmpassword"])){
            array_push($error,"<b>Confirm Password</b> cannot empty.");
            $errorConfirmPassword = true;
        }else{
            $confirmpassword = antiExploit($_POST["confirmpassword"]);
            if(isset($password)&&($password!=$confirmpassword)){
                array_push($error,"<b>Confirm Password</b> must match the password.");
                $errorConfirmPassword = true;
            }
        }
        if(!isset($_POST['gender'])){
            array_push($error,"Please select a <b>Gender</b>.");
            $errorGender = true;
        }else{
            $gender = antiExploit($_POST["gender"]);
            $pattern = '/^[MF]$/';
            if (!preg_match($pattern, $gender)){
                array_push($error,"Please select a <b>Gender</b> Must be only either Male or Female.");
                $errorGender = true;
            }
        }
        if(empty($_POST["email"])){
            array_push($error,"<b>Email Address</b> cannot empty.");
            $errorEmail = true;
        }else{
            $email = antiExploit($_POST["email"]);
            if(strlen($email)>50){
                array_push($error,"<b>Email</b> cannot more than 50 characters.");
                $errorEmail = true;
            }else{
                $pattern = "/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/";
                if(!preg_match($pattern, $email)){
                    array_push($error,"<b>Email Address</b> is of invalid format.");
                    $errorEmail = true;
                }else{
                    if (isStaffEmailExist($email)){
                        array_push($error,"<b>Email</b> is unavailable");
                        $errorEmail = true;
                    }
                }
            }
        }
        if(empty($_POST["birthdate"])){
            array_push($error,"<b>Birth Date</b> cannot empty.");
            $errorBirthDate = true;
            
        }else{
            $birthDate = antiExploit($_POST["birthdate"]);
            $year = substr($birthDate, 0,4);
            $month = substr($birthDate,5,2);
            $day = substr($birthDate,8,2);
            if(!checkdate($month, $day, $year)){
                array_push($error,"<b>Birth Date</b> invalid date.");
                $errorBirthDate = true;
            }
        }
        if(empty($_POST["contactNumber"])){
            array_push($error,"<b>Contact Number</b> cannot empty.");
            $errorContactNumber = true;
        }else{
            $contactNumber = antiExploit($_POST["contactNumber"]);
            if (strlen($contactNumber)>12){
                array_push($error,"<b>Contact Number</b> cannot more than 12 characters.");
                $errorContactNumber = true;
            }else{
                $pattern = '/^([0-9]{2}|[0-9]{3})-([0-9]+)$/';
                if(!preg_match($pattern,$contactNumber)){
                    array_push($error,"<b>Contact Number</b> is of invalid format. Format: xxx-xxxxxxxx");
                    $errorContactNumber = true;
                }
            }
            
        }
        if(empty($_POST['department'])){
            array_push($error,"Please select a <b>Department</b>.");
            $errorDepartment = true;
        }else{
            $department = antiExploit($_POST["department"]);
            if(!in_array($department, array_keys($departmentArr))){
                array_push($error,"Please select a <b>Department</b> Must be a valid department.");
                $errorDepartment = true;
            }
        }
        if(empty($_POST["position"])){
            array_push($error,"<b>Position</b> cannot empty.");
            $errorPosition = true;
        }else{
            $position = antiExploit($_POST["position"]);
            if (strlen($position)>40){
                array_push($error,"<b>Position</b> cannot more than 40 characters.");
                $errorPosition = true;
            }else{
                $pattern = '/^[A-Za-z\s]+$/';
                if(!preg_match($pattern,$position)){
                    array_push($error,"<b>Position</b> is of invalid format. Only accept alphabetical");
                    $errorPosition = true;
                }
            }
            
        }
        if(empty($_POST['status'])){
            array_push($error,"Please select a <b>Account Status</b>.");
            $errorStatus = true;
        }else{
            $status = antiExploit($_POST["status"]);
            if(!in_array($status, array_values($statusArr))){
                array_push($error,"Please select a <b>Account Status</b> Must be a valid account status.");
                $errorStatus = true;
            }
        }
        if (empty($error)){
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT * from staff";

                if($result = $con->query($sql)){
                    $count = $result->num_rows+1;
                    $staffID = "S".str_repeat("0",4-strlen($count)).$count;
                    $date = date('Y-m-d H:i:s');
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO staff(staffID,userName,name,password,gender,email,birthDate,contactNumber,department,position,
                        joinDate,lastUpdate,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $con -> prepare($sql);
                    $stmt ->bind_param('sssssssssssss',$staffID,$username,$name,$password,$gender,$email,$birthDate,$contactNumber,$department,$position
                            ,$date,$date,$status);
                    $stmt->execute();
                    createAuditLog("Staff","Create new staff account","Staff account with StaffID $staffID created.");
                    if ($stmt->affected_rows >0){
                        $stmt->free_result();
                        $result->free();
                        $con->close();
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: staffs.php');
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                    $stmt->free_result();
                }else{
                     echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
                $result->free();
                $con->close();
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
?>