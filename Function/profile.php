<?php
    $clientID = $_SESSION["clientId"];
    //States List
    $states = array(
        ""=>"--Selected One--",
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
    //Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['changepasswordnew'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Modified the password.");}, 100);</script>';
        }
        if (isset($_POST['editProfile'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Modified the profile.");}, 100);</script>';
        }
        if (isset($_POST['editAddress'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Modified the address.");}, 100);</script>';
        }
        if (isset($_POST['addAddress'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Added new address.");}, 100);</script>';
        }
    }
    function profile(){
        global $clientID,$defaultAddress;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from client WHERE clientID = '$clientID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result-> fetch_object())
                {
                    $gender = $row->gender;
                    $name = $row->name;
                    $email = $row->email;
                    $contactNumber = $row->contactNumber;
                    $payment = $row->paymentMethod;
                    $birthDate = $row->birthDate;
                    $defaultAddress = $row->defaultAddress;
                }
                
            }
            $result->free();
        }
        $con->close();
        if ($gender=='M'){
            printf('
            <p id="name"><strong>%s</strong> <span id="male"><i class="fas fa-mars"></i></span></p>
                ',$name);
        }else{
            printf('
            <p id="name"><strong>%s</strong> <span id="female"><i class="fas fa-venus"></i></span></p>
                ',$name);
        }
        printf('
            <span id="subDetails">
                <p>EMAIL : %s</p>
                <p>PHONE : %s</p>
                <p>BIRTH DATE : %s</p>
                <p>PAYMENT METHOD : %s</p>
            </span>
        ',$email,$contactNumber,$birthDate,$payment);
    }
    function displayAddress(){
        global $defaultAddress, $states;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from address WHERE addressID = '$defaultAddress'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result-> fetch_object())
                {
                    $address = $row->address;
                    $zipCode = $row->zipCode;
                    $city = $row->city;
                    $state = $row->state;
                }
                
            }
            $result->free();
        }
        $con->close();
        printf('
            %s<br>
            %s, %s<br>
            %s<br>
                ',$address,$zipCode,$city,$states[$state]);
    }
    function orderDetails(){ //Display client order details
        global $status,$clientID;
        
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT o.orderID, o.purchasedTime, COUNT(p.Product_ID) AS imgCounter, p.Image, o.totalPrice, o.status 
                    FROM orders o, orderProduct op, productlist p
                    WHERE o.clientID = '$clientID' AND op.orderID = o.orderID AND op.productID = p.Product_ID AND o.status != 'Removed'  
                    GROUP BY o.orderID ORDER BY o.purchasedTime DESC LIMIT 5";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                
                    while($row = $result->fetch_object()){
                        printf('
                        <tr>
                        <td>%s</td>
                        <td>%.10s</td>
                        ',$row->orderID, $row->purchasedTime);
                        echo "<td>";
                        //need to connect with the product img link
                        printf('<img src="uploads/%s">', $row->Image);
                        echo "</td>";
                        printf('
                        <td>%.2f</td>
                        <td>%s</td>
                        </tr>
                        ',$row->totalPrice,$row->status);
                    }
                if ($result->num_rows ==0){
                    printf("<tr><td colspan='8'height='60px' class='emptySlot'><b>You haven't start to order anything yet...</b></td></tr>");
                }
            }
        }

    }
