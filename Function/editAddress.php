<?php
    include 'settings.php';
    include 'AdminArea/Function/helper.php';
    $addressID = $_GET['addressID'];
    $clientID = $_SESSION["clientId"];
    //Verify addressID whether it is valid
    if (!isset($addressID)){
        header('Location: editProfile.php');
    }
    //States List
    $states = array(
        "" => "-Select State-",
        "JH" => "Johor",
        "KD" => "Kedah",
        "KT" => "Kelantan",
        "KL" => "Kuala Lumpur",
        "LB" => "Labuan",
        "MK" => "Melaka",
        "NS" => "Negeri Sembilan",
        "PH" => "Pahang",
        "PN" => "Penang",
        "PR" => "Perak",
        "PL" => "Perlis",
        "PJ" => "Putrajaya",
        "SB" => "Sabah",
        "SW" => "Sarawak",
        "SG" => "Selangor",
        "TR" => "Terengganu"
    );
    //Display error icon
    function displayError(){
        echo "<img src='Media/error.png'>";
    }
    $error = array();
    function displayErrorMessage(){
        $error = $GLOBALS['error'];
        if (!empty($error)){
            //Display error message
            echo "<ul class='error'>";
            foreach($error as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
        }
    }
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(isset($addressID)) //Get the current address from database
    {
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{
            $sql = "SELECT * from client c, address a,clientaddress ca WHERE c.clientID = ca.clientID AND a.addressID = '$addressID' AND a.addressID = ca.addressID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object())
                {
                $currentAddress = $address = $row->address;
                $currentZipCode = $zipCode = $row->zipCode;
                $currentCity = $city = $row->city;
                $currentState = $state = $row->state;
                }
                if ($result->num_rows ==0){
                    setcookie("error","clientId",time()+1,"profile.php");
                    header('Location: profile.php');
                }
            }
            $result->free();
        }
        $con->close();
    }
    //Display state list
    function stateList(){
        foreach($GLOBALS['states'] as $key => $value){
            echo "<option value='$key'".(isset($_POST["state"])?($_POST["state"]==$key?"selected":""):$GLOBALS['state']==$key?"selected":"").">$value</option>";
        }
    }
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['editAddress'])){
            if(empty($_POST["address"])){
                array_push($error,"<b>Address</b> cannot empty.");
                $errorAddress = true;
            }else{
                $address = antiExploit($_POST["address"]);
            }
            if(!empty($_POST["zipCode"])){
                $zipCode = trim($_POST["zipCode"]);
                if (strlen($zipCode)>5){
                    array_push($error,"<b>Zip Code</b> cannot more than 5 characters.");
                    $errorZipCode = true;
                }else{
                    $pattern = '/^[0-9]{5}$/';
                    if(!preg_match($pattern,$zipCode)){
                        array_push($error,"<b>Zip Code</b> is of invalid format.");
                        $errorZipCode = true;
                    } 
                }
            }
            if(!empty($_POST["city"])){
                $city = trim($_POST["city"]);
                if (strlen($city)>28){
                    array_push($error,"<b>City</b> cannot more than 28 characters.");
                    $errorCity = true;
                }
            }
            if(!empty($_POST['state'])){
                $state = trim($_POST["state"]);
                if(!in_array($state, array_keys($states))){
                    array_push($error,"Please select a <b>State</b> Must be a valid state.");
                    $errorState = true;
                }
            }
            if (empty($error)){
                $clientID = $_SESSION["clientId"];
                if ($currentAddress == $address && $currentCity == $city && $currentZipCode == $zipCode && $currentState == $state){
                    header('HTTP/1.1 307 Temporary Redirect');
                    header('Location: profile.php');
                }
                if (checkAddressExist($clientID, $address, $city, $zipCode, $state)){
                    array_push($error,"Contains same <b>client address</b>.");
                }else{
                    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                    if ($con -> connect_errno) { //Check it is the connection succesful
                        $databaseError = $con->connect_error;
                    }else{
                        $addressID = $_GET['addressID']; 
                        $date = date('Y-m-d H:i:s');
                        //Update the member details updateTime
                        $sql = "UPDATE client SET lastUpdate = ? WHERE clientID=?";
                        $stmt = $con->prepare($sql);
                        $stmt ->bind_param('ss',$date, $clientID);
                        if($stmt->execute()){
                            $stmt->free_result();
                            $sql = "UPDATE address SET address = ?, zipCode = ?, city = ?, state=? WHERE addressID = ?";//Insert address detail into address table
                            $stmt = $con -> prepare ($sql);
                            $stmt ->bind_param('sssss', $address, $zipCode, $city, $state, $addressID);
                            if ($stmt->execute()){
                                $stmt->free_result();
                                $result->free();
                                $con->close();
                                header('HTTP/1.1 307 Temporary Redirect');
                                header('Location: profile.php');
                            }else{
                                $databaseError = $stmt->error;
                            }
                            $stmt->free_result();
                        }else{
                            $databaseError = $stmt->error;
                        }
                        $stmt->free_result();
                        $con->close();
                    }
                }
            }
        }
    }