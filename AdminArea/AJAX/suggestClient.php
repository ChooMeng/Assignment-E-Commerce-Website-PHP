<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
    if (empty($search)){
        $search = "%";
    }
    require '../../settings.php';
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $maxamount = SUGGEST_DATA_MAX_AMOUNT-1;
        $sql = "SELECT * from client WHERE clientID LIKE '%$search%' LIMIT 0,".$maxamount;
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                echo "<option value='$row->clientID'>$row->name</option>";
            }
        }
    }

