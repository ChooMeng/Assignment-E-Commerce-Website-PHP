<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
    if (empty($search)){
        $search = " ";
    }
    require '../../settings.php';
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
    //Read client address details
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        $databaseError = $con->connect_error;
    }else{
        $sql = "SELECT * from client c, clientaddress ca, address a WHERE ca.clientID = '$search' AND c.clientID = ca.clientID AND ca.addressID = a.addressID";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            echo "<table>
                        <tr>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Selected</th>
                        </tr>";
            while($row = $result->fetch_object()){
                printf("
                    <tr id='a%s'>
                         <td>%s</td>
                         <td>%s</td>
                         <td>%s</td>
                         <td>%s</td>
                         <td><input id='b%s' name='default' value='%d' type='radio' %s></input></td>
                    </tr>
                 ",$row->addressID,$row->address,$row->zipCode,$row->city,$states[$row->state],$row->addressID,$row->addressID, $row->defaultAddress==$row->addressID?"checked":"");
            }
            if ($result->num_rows ==0){
                printf("
                    <tr><td colspan='6'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                        ");
            }
            echo "</table>";
        }
    }