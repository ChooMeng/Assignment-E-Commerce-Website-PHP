<?php
    //Verify orderID whether it is valid
    if (!isset($_GET['orderId'])){
        setcookie("error","orderId",time()+1,"orders.php");
        header('Location: orders.php');
    }
    //States list
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
    $orderID = $_GET['orderId'];
    $discount = 0;
    function details(){ //Display order details
        global $orderID, $states,$discount;
        //Read order details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT o.lastUpdate,orderID,discount , email, contactNumber, o.status, payment, orderNo, purchasedTime, o.clientID,name, address, state, city, zipCode from orders o JOIN client c ON o.clientID = c.clientID JOIN address a ON o.billingaddress = a.addressID WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    if ($row->status == "Removed"){
                        setcookie("error","orderId",time()+1,"orders.php");
                        header('Location: orders.php');
                        break;
                    }
                    $discount = $row->discount;
                    printf('
                        <p id="lastUpdate">Last update: %s</p>
                      <p id="orderId">Order ID: %s</p>
                      <div class="rightSide">
                          <p class="field">Email</p>
                          <p class="data">%s</p>
                          <p class="field">Contact Number</p>
                          <p class="data">%s</p>
                          <p class="field">Status</p>
                          <p class="data">%s</p>
                          <p class="field">Payment Method</p>
                          <p class="data">%s</p>
                      </div>
                      <div class="leftSide">
                          <p class="field">Order No</p>
                          <p class="data">%s</p>
                          <p class="field">Purchased Time</p>
                          <p class="data">%s</p>
                          <p class="field">Client ID</p>
                          <p class="data">%s</p>
                          <p class="field">Name</p>
                          <p class="data">%s</p>
                          <br>
                          <br>
                      </div>  
                  ',$row->lastUpdate,$orderID,$row->email,$row->contactNumber,$row->status,$row->payment,$row->orderNo,$row->purchasedTime,$row->clientID
                          ,$row->name);
                    printf('
                        <hr/>
                        <h2 style="text-align: center;color:white;">Address</h2>
                        <div class="rightSide">
                            <p class="field">City</p>
                            <p class="data">%s</p>
                            <p class="field">State</p>
                            <p class="data">%s</p>
                        </div>
                        <div class="leftSide">
                            <p class="field">Address</p>
                            <p class="data">%s</p>
                            <p class="field">ZipCode</p>
                            <p class="data">%s</p>
                            <br/>
                        </div>
                            ',$row->city,$states[$row->state],$row->address,$row->zipCode);
                }
                if ($result->num_rows ==0){
                    setcookie("error","orderId",time()+1,"orders.php");
                    header('Location: orders.php');
                }
            }
        }
    }
    function orderProduct(){ //Display ordered products list
        global $orderID, $discount;
        $subtotal = 0;
        //Read order details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from orders WHERE orderID = '$orderID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                    <table>
                        <tr>
                            <th>No.</th>
                            <th>Product ID</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price<br>(RM)</th>
                            <th>Total<br>(RM)</th>
                        </tr>
                        ');
                    $result->free();
                    $sql = "SELECT op.productID, p.Name, op.quantity,Price from orderproduct op JOIN productlist p ON op.productID = p.Product_ID WHERE orderID = '$orderID'";
                    if(!$result = $con->query($sql)){
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }else{
                        $i = 0;
                        while($row = $result->fetch_object()){
                            
                            printf('
                                <tr>
                                    <td>%d</td>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td>%d</td>
                                    <td>%.2f</td>
                                    <td>%.2f</td>
                                 </tr>
                            ',$i+1,$row->productID,$row->Name,$row->quantity,$row->Price,$row->quantity*$row->Price);
                            $subtotal += ($row->quantity*$row->Price);
                            $i++;
                        }
                        //Display subtotal,discount and grandtotal with calculation
                        printf('
                            </table>
                            <table>
                            <tr>
                                <th class="tableHeader" width="90%%" style="text-align:right;">SubTotal</th>
                                <td id="subtotal" width="10%%">%.2f</td>
                            </tr>
                            <tr>
                                <th class="tableHeader" width="90%%" style="text-align:right;">Discount %d%%</th>
                                <td id="discount" width="10%%">%.2f</td>
                            </tr>
                            <tr>
                                <th class="tableHeader" width="90%%" style="text-align:right;">Grand Total</th>
                                <td id="grandtotal" width="10%%">%.2f</td>
                            </tr>
                            </table>
                        ',$subtotal,$discount,$discount/100,$subtotal-($discount/100));
                    }
                }
            }
        }
    }