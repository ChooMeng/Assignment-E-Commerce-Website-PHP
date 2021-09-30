<?php
    require 'Function/helper.php';
    $error = array();
    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['shoppingCart'])){
            if (isset($_SESSION['clientId'])){
                $clientID = $_SESSION['clientId'];
                @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "SELECT * FROM shoppingcarttable s, productlist p WHERE p.Product_ID = s.productID AND s.clientID = '$clientID';";
                    if(!$result = $con->query($sql)){
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }else{
                        if($result->num_rows > 0){
                            while($row = $result->fetch_object()){
                                $stockLeft = enoughQuantity($row->productID, $row->quantity);
                                if ((int)$stockLeft<($row->quantity)){
                                    $productName = $row->Name;
                                    array_push($error,"<b>$productName</b> not enough stock. Stock Left: $stockLeft");
                                }
                            }
                            if (empty($error)){
                                header('location: checkOut.php');
                            }
                        }else{
                            echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! No have products.");}, 100);</script>';
                        }
                    }
                }
            }else{
                if (isset($_SESSION['shoppingCart'])){
                    foreach($_SESSION['shoppingCart'] as $key =>$value){
                        $stockLeft = enoughQuantity($key, $value);
                        if ((int)$stockLeft<($value)){
                            $productName = productIDToName($key);
                            array_push($error,"<b>$productName</b> not enough stock. Stock Left: $stockLeft");
                        }
                    }
                    if (empty($error)){
                        header('location: unAuthPurchase.php');
                    }
                }else{
                    echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! No have products.");}, 100);</script>';
                }
            }
            
        }
    }
    function displayErrorMessage(){
        global $error;
        if (!empty($error)){
            echo "<ul class='error'>";
            foreach($error as $value){
                echo "<li>$value</li>";
            }
            echo "</ul>";
        }
        
    }
    if (isset($_SESSION['removed'])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Remove the product from shopping cart.");}, 100);</script>';
        unset($_SESSION['removed']);
    }
    //Display succesful apply discount message
    if(isset($_SESSION["applied"])){
        if ($_SESSION["applied"]==true){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Succesful apply the discount.");}, 100);</script>';
            unset($_SESSION["applied"]);
        }else{
            echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid voucher code.");}, 100);</script>';
            unset($_SESSION["applied"]);
        }
        
    }
    function guestShoppingCartList(){
        if (isset($_SESSION['clientId'])){
            $_SESSION['subtotal'] = 0;
            $clientID = $_SESSION['clientId'];
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT * FROM shoppingcarttable s, productlist p WHERE p.Product_ID = s.productID AND s.clientID = '$clientID';";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        printf('<tr>
                            <td class="itemImg">
                                <img src="uploads/%s" class="productImg">
                            </td>
                            <td valign="top" class="itemDescription">
                                <p>%s</p>
                                <p class="delete"><span onclick="removeFromCart(\'%s\')"><i class="fas fa-trash"></i></span></p>
                            </td>
                            <td valign="top" class="itemPrice">
                                <p>%.2f</p>
                                <p>- %d%%</p>
                                <p>QTY: <input name="quantityNum" type="number" min="1" max="%d" value="%d" onchange="updateQuantity(\'%s\',this)"></p>
                            </td>
                        </tr>',$row->Image,$row->Name,$row->Product_ID, $row->Discount>0?($row->Price-$row->Price*($row->Discount/100)):$row->Price,$row->Price,$row->Stock,$row->quantity,$row->productID);
                        $_SESSION['subtotal'] += $row->Discount>0?($row->Price-$row->Price*($row->Discount/100))*$row->quantity:$row->Price*$row->quantity;


                    }
                }
            }
        }else{
            $_SESSION['subtotal'] = 0;
            if (isset($_SESSION['shoppingCart'])){
                $shoppingCart = $_SESSION['shoppingCart'];

                foreach ($shoppingCart as $key => $value){
                    $details = getProductDetails($key);
                    printf('<tr>
                        <td class="itemImg">
                            <img src="uploads/%s" class="productImg">
                        </td>
                        <td valign="top" class="itemDescription">
                            <p>%s</p>
                            <p class="delete"><span onclick="removeFromCart(\'%s\')"><i class="fas fa-trash"></i></span></p>
                        </td>
                        <td valign="top" class="itemPrice">
                            <p>%.2f</p>
                            <p>- %d%%</p>
                            <p>QTY: <input name="quantityNum" type="number" min="1" max="%d" value="%d" onchange="updateQuantity(\'%s\',this)"></p>
                        </td>
                    </tr>',$details['image'],$details['name'],$key, $details['discount']>0?($details['price']-$details['price']*($details['discount']/100)):$details['price'],$details['price'],$details['stock'],$value,$key);
                    $_SESSION['subtotal'] += $details['discount']>0?($details['price']-$details['price']*($details['discount']/100))*$value:$details['price']*$value;
                }

            }
        }
        
    }
    function shoppingCartSummary(){
        global $totalCartAmount;
        printf('<tr>
                    <td colspan="2">
                        <!--Shows the vouncher part-->
                         <h2 id="orderSummary">Order Summary</h2>
                    </td>
                </tr>
                <tr>
                    <td class="leftStatement">Subtotal (%d items)</td>
                    <td>RM %.2f</td>
                </tr>
                <tr><td colspan="2"><p>Voucher Code</p><hr></td></tr>
                <tr>
                    <td>
                        <input type="text" name="voucherInput" id="voucherInput" placeholder="XXXX-XXXX" maxlength="9" pattern="[0-9]{4}-[0-9]{4}">
                    </td>
                    <td><button type="button" id="applyBtn" onclick="applyDiscount()">Apply</button></td>
                </tr>
                <tr>
                    <td class="leftStatement">Discount [%d%%]:</td>
                    <td>RM %.2f</td>
                </tr>
                <tr>
                    <td class="leftStatement">Total:</td>
                    <td>RM %.2f</td>
                </tr>', isset($_SESSION['staffId'])?$totalCartAmount:(isset($_SESSION['shoppingCart'])?count($_SESSION['shoppingCart']):0), isset($_SESSION['subtotal'])?$_SESSION['subtotal']:0,
                isset($_SESSION['discountApply'])?$_SESSION['discountApply']:0,isset($_SESSION['discountApply'])?$_SESSION['discountApply']/100*$_SESSION['subtotal']:0,isset($_SESSION['discountApply'])?$_SESSION['subtotal']-$_SESSION['discountApply']/100*$_SESSION['subtotal']:$_SESSION['subtotal']);
    }
?>
