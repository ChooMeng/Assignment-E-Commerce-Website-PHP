<?php
    include 'AdminArea/Function/helper.php';
    include 'settings.php';
    $clientID = $_SESSION["clientId"];
    //States List
    $states = array(
        ""=>"-Select State-",
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
    //Display state list
    function stateList(){
        foreach($GLOBALS['states'] as $key => $value){
            echo "<option value='$key'".(isset($_POST["state"])?($_POST["state"]==$key?"selected":""):"").">$value</option>";
        }
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
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['addAddress'])){
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
                if (checkAddressExist($clientID, $address, $city, $zipCode, $state)){
                    array_push($error,"Contains same <b>client address</b>.");
                }else{
                    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                    if ($con -> connect_errno) { //Check it is the connection succesful
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                    }else{
                        $sql = "SELECT * FROM address";
                        if(!$result = $con->query($sql)){
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }else{
                            $addressID = $result->num_rows+1;
                            $sql = "INSERT INTO address(addressID,address,zipCode,city,state) VALUES (?,?,?,?,?)";//Insert address detail into address table
                            $stmt = $con -> prepare ($sql);
                            $stmt ->bind_param('sssss', $addressID,$address,$zipCode,$city,$state);
                            if ($stmt->execute()){
                                $stmt->free_result();
                                $sql = "INSERT INTO clientaddress VALUES (?,?)";
                                $stmt = $con -> prepare ($sql);
                                $stmt ->bind_param('ss', $clientID,$addressID);
                                if ($stmt->execute()){
                                    $stmt->free_result();
                                    //Update the member details updateTime
                                    $sql = "UPDATE client SET lastUpdate = ? WHERE clientID=?";
                                    $stmt = $con->prepare($sql);
                                    $stmt ->bind_param('ss',$date, $clientID);
                                    if($stmt->execute()){
                                        $stmt->free_result();
                                        $result->free();
                                        $con->close();
                                        header('HTTP/1.1 307 Temporary Redirect');
                                        header('Location: profile.php');
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
                    $con->close();
                }
            }
        }
    }