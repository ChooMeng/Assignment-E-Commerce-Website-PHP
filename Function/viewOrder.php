<?php
    $clientID = $_SESSION["clientId"];
    include 'settings.php';
    function orderDetails(){ //Display client order details
        global $dataArr,$index,$status,$clientID;
        
        //Read client address details
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT o.orderID, o.purchasedTime, p.Image, o.totalPrice, o.status 
                    FROM orders o, orderProduct op, productlist p
                    WHERE o.clientID = '$clientID' AND op.orderID = o.orderID AND p.Product_ID = op.productID AND o.status != 'Removed'
                    GROUP BY o.orderID ORDER BY o.purchasedTime DESC LIMIT ".MAX_CLIENT_VIEW_ORDER_RESULT;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                    <tr class="item">
                        <td>%s</td>
                        <td>%.10s</td>
                    ',$row->orderID, $row->purchasedTime);
                    echo "<td>";
                    printf('<img src="uploads/%s">', $row->Image);
                    echo "</td>";
                    printf("
                        <td>%.2f</td>
                        <td>%s</td>
                        <td><button class='orderDet' onclick=\"location.href='orderDetails.php?orderId=%s'\">Details</button></td>
                    </tr>
                    ",$row->totalPrice,$row->status, $row->orderID);
                }
                echo "<tr><td colspan='5'>";
                if (!$result->num_rows == 0){
                    printf('<div id="endOfItemDisp" class="lastMessage">This is the end of the order list</div>');
                }elseif ($result->num_rows == 0){
                    printf('<div id="noIemDisp" class="lastMessage">Hmm... seems like you haven\'t order something yet...</div>');
                }
                echo "</td></tr>";
            }
        }

    }

