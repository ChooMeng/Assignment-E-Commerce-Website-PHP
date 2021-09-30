<?php
    include 'settings.php';
    include 'AdminArea/Function/helper.php';
    $clientID = "";
    $discount = 0;
    $subtotal = 0;
    $counter = 0;
    $total = 0;
    $date = date('Y-m-d H:i:s'); //new Date
    $error = array();

    if(isset($_SESSION['clientId'])){ //For the login user
        $clientID = antiExploit($_SESSION['clientId']);
    }elseif (isset($_SESSION['guestId'])){ // For the unauthorized user 
        $clientID = antiExploit($_SESSION['guestId']);
        uploadProduct();
    }else{ //Nothing has been set then send the user back to the home page
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location: index.php');
    }

    if(isset($_SESSION['discountApply'])){ //If the voucher code in shopping cart is correct
        $discount = $_SESSION['discountApply'];
    }
    function uploadProduct(){
        if (isset($_SESSION['shoppingCart'])){
            $clientID = $_SESSION['guestId'];
            
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "DELETE FROM shoppingcarttable WHERE clientID = ?";
                $stmt = $con->prepare($sql);
                $stmt -> bind_param('s', $clientID);
                if($stmt->execute())
                {
                    $stmt->free_result();
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
                foreach($_SESSION['shoppingCart'] as $key => $value){
                    $sql = "INSERT INTO shoppingcarttable (clientID, productID, quantity) VALUES (?,?,?)";
                    $stmt = $con->prepare($sql);
                    $stmt -> bind_param('sss', $clientID, $key, $value);
                    if($stmt->execute())
                    {
                        $stmt->free_result();
                    }else{
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }
                }

            }
            $con->close();
        }
    }
    //Display error icon
    function displayError(){
        echo "<img src='Media/error.png'>";
    }
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Display the error message
    function displayCreditCardErrorMessage(){
        $error = $GLOBALS['error'];
        if (isset($_POST['submitCreditCard'])){
            if (!empty($error)){
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
    }
    //Display the error message
    function displayPaypalErrorMessage(){
        $error = $GLOBALS['error'];
        if (isset($_POST['submitPaypal'])){
            if (!empty($error)){
                echo "<ul class='error'>";
                foreach($error as $value){
                    echo "<li>$value</li>";
                }
                echo "</ul>";
            }
        }
    }
    //Display the default payment block
    function showCreditCard(){
        global $clientID;
        if (isset($_POST['submitCreditCard'])){
            echo "style='display: block;'";
        }else{
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                $databaseError = $con->connect_error;
            }else{ //Check if the user has set the default payment method as Credit Card
                $sql = "SELECT * FROM client WHERE clientID = '$clientID'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object())
                    {
                        if($row->paymentMethod == "CreditCard")
                        {
                            echo "style='display: block;'";
                        }
                    }
                }
            }
        }
    }
    function showTouchNGo(){
        global $clientID;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            $databaseError = $con->connect_error;
        }else{ //Check if the user has set the default payment method as TouchNGo
            $sql = "SELECT * FROM client WHERE clientID = '$clientID'";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object())
                {
                    if($row->paymentMethod == "TouchNGo")
                    {
                        echo "style='display: block;'";
                    }
                }
            }
        }
    }
    function showPaypal(){
        global $clientID;
        if (isset($_POST['submitPaypal'])){
            echo "style='display: block;'";
        }else{
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                $databaseError = $con->connect_error;
            }else{ //Check if the user has set the default payment method as Paypal
                $sql = "SELECT * FROM client WHERE clientID = '$clientID'";
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object())
                    {
                        if($row->paymentMethod == "Paypal")
                        {
                            echo "style='display: block;'";
                        }
                    }
                }
            }
        }
    }

    function displaySummary(){
        global $clientID, $discount, $subtotal, $counter;
        
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT p.Name, s.quantity AS quantity, p.Price, ((p.Price-p.Price* p.Discount/100) * s.quantity ) AS subtotal 
                    FROM shoppingcarttable s, productlist p 
                    WHERE s.clientID = '$clientID' AND p.Product_ID = s.productID;";

            if($result = $con->query($sql)){
                printf('
                    <colgroup>
                        <col span="1" width="65%%">
                        <col span="1" width="8%%">
                        <col span="1" width="38%%">         
                    </colgroup>   
                    <tr id="header">
                        <td>Product Name</td>
                        <td>Qty</td>
                        <td>Price</td>
                    </tr>
                ');
                while($row = $result->fetch_object()){
                    printf('
                    <tr>
                        <td class="leftStatement">%s</td>
                        <td>%d</td>
                        <td>RM %.2f</td>
                    </tr>
                    ', $row->Name, $row->quantity, $row->subtotal);

                    $subtotal += $row->subtotal;
                    $counter ++;
                }
                $offer = $subtotal * ($discount/100); //discount is from the voucher there one
                $total = $subtotal - $offer;
                
                printf('
                        <tr><td colspan="3"><hr/></td></tr>
                        <tr>
                            <td class="leftStatement">Subtotal (%d items)</td>
                            <td colspan="2">RM %.2f</td>
                        </tr>
                        <tr>
                            <td class="leftStatement">Discount:</td>
                            <td colspan="2">RM %.2f</td>
                        </tr>
                        <tr>
                            <td class="leftStatement">Total:</td>
                            <td colspan="2">RM %.2f</td>
                        </tr>
                    ',$counter, $subtotal, $offer, $total);
            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }
        }
    }
    //Generate random letter by using ASCII number
    function randomLetter(){
	    $letter = chr(rand(65,90));
	    return $letter;
    }
    // Generate random number by using ASCII number
    function randomNumber() {
        $letter = chr(rand(48,57));
        return $letter;
    }
    
    function madeOrders(){ //Create new orders and orderproduct rows
        global $discount, $subtotal, $counter, $date;
        $clientID;
        $status = "Pending"; //The order should begin with pending status
        $orderID = "";
        $quantity = 0;
        $orderCount = 0;
        $paymentMethod = "";

        if(isset($_SESSION['clientId'])){ //For the login user
            $clientID = antiExploit($_SESSION['clientId']);
        }elseif (isset($_SESSION['guestId'])){ // For the unauthorized user 
            $clientID = antiExploit($_SESSION['guestId']);
        }

        if(isset($_POST['submitCreditCard'])){
            $paymentMethod = "CreditCard";
        }elseif (isset($_POST['submitTouchNGo'])){
            $paymentMethod = "TouchNGo";
        }elseif(isset($_POST['submitPaypal'])){
            $paymentMethod = "Paypal";
        }


        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{

            $sql = "SELECT * FROM shoppingcarttable WHERE clientID = '$clientID'"; //Get the product and qty from shopping cart
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                $product = array();
                while($row = $result->fetch_object()){ // Save the $productID and $quantity into an associative array
                    $productID = $row->productID;
                    $quantity = $row->quantity;
                    $product[$productID]=$quantity; //Save it inside the $product associative array
                }
                $result->free();

                $sql = "SELECT * FROM orders";
                if($result = $con->query($sql)){ //Generate the orderID 
                    $orderCount = ($result->num_rows) + 1;
                    $orderID = "O".str_repeat("0",9-strlen($orderCount)).$orderCount;
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
                $result->free();

                $sql = "SELECT defaultAddress FROM client WHERE clientID = '$clientID'";
                if($result = $con->query($sql)){ //Get client default address
                    while($row = $result->fetch_object()){
                        $addressID = $row->defaultAddress;

                    }
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
                $result->free();

                $sql = "SELECT p.Name, s.quantity AS quantity, p.Price, ((p.Price-p.Price* p.Discount/100) * s.quantity ) AS subtotal 
                    FROM shoppingcarttable s, productlist p 
                    WHERE s.clientID = '$clientID' AND p.Product_ID = s.productID;";
                if($result = $con->query($sql)){
                    while($row = $result->fetch_object()){
                        $subtotal += $row->subtotal;
                        $counter += $row->quantity;
                    }
                    $offer = $subtotal * ($discount/100); //discount is from the voucher there one
                    $total = $subtotal - $offer;
                }

                $orderNo = "";
                for ($i = 0; $i < 12; $i++) { //To generate the orderNo by using the for loop + rand
                    $num = rand() % (2) + 1;
                    if ($num == 1) {
                        $orderNo = $orderNo.randomLetter();
                    }
                    else {
                        $orderNo = $orderNo.randomNumber();
                    }
                }

                //Create new order row
                $sql = "INSERT INTO orders(orderID, orderNo, purchasedTime, clientID, payment, billingAddress, status, lastUpdate, discount, productAmount, totalPrice)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $con -> prepare ($sql);
                $stmt->bind_param('sssssssssss', $orderID, $orderNo, $date, $clientID, $paymentMethod, $addressID, $status, $date, $discount, $counter, $total);

                if($stmt->execute()){ //Use foreach loop to create the orderproduct rows
                    $stmt->free_result();
                    
                    foreach($product as $key => $value){ //Create the bridge between orders and productlist
                        $sql = "INSERT INTO orderproduct(orderID, productID, quantity) VALUE (?, ?, ?)";
                        $stmt = $con -> prepare ($sql);
                        $stmt->bind_param('sss', $orderID, $key, $value);
                        
                        if($stmt->execute()){ //DELETE the rows from shoppingcarttable since the order has been made
                            $stmt->free_result();
                            $sql = "DELETE FROM shoppingcarttable WHERE clientID = ? AND productID = ?";
                            $stmt = $con -> prepare ($sql);
                            $stmt->bind_param('ss', $clientID, $key);

                            if($stmt->execute()){ //An orderproduct row has been created
                                $stmt->free_result();
                                updateStockQuantity($key, -$quantity);
                                unset($_SESSION['shoppingCart']);
                                unset($_SESSION['guestId']);
                                unset($_SESSION['subtotal']);
                                unset($_SESSION['discountApply']);
                            }else{
                                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                            }
                        }else{
                            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                        }
                    }
                    if($stmt->execute()){ //The checkout process has successful, it will back to the homepage (index.php)
                        $stmt->free_result();
                        header('HTTP/1.1 307 Temporary Redirect');
                        header('Location: index.php');
                    }
                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
            }
        }
        $con->close();
    }

    //Validate the input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST['submitCreditCard'])){ // Credit Card
            if (empty($_POST["cardNumber"])){
                array_push($error,"<b>Card Number</b> cannot empty.");
                $errorCardNumber = true;
            }else{
                $cardNumber = antiExploit($_POST["cardNumber"]);
                if (strlen($cardNumber)!=16){
                    array_push($error,"<b>Card Number</b> must 16 digit.");
                    $errorCardNumber = true;
                }else{
                    $pattern = '/^[0-9]{16}$/';
                    if(!preg_match($pattern, $cardNumber)){
                        array_push($error,"<b>Card Number</b> must 16 digit.");
                        $errorCardNumber = true;
                    } 
                }
            }
            if (empty($_POST["nameOnCard"])){
                array_push($error,"<b>Name on Card</b> cannot empty.");
                $errorName = true;
            }else{
                $name = antiExploit($_POST["nameOnCard"]);
                if (strlen($name)>40){
                    array_push($error,"<b>Name on Card</b> cannot more than 40 characters.");
                    $errorName = true;
                }else{
                    $pattern = '/^[A-Za-z\s@\'\.-\/]+$/';
                    if(!preg_match($pattern, $name)){
                        array_push($error,"<b>Name on Card</b> can contains only uppercase and lowercase alphabet, space, alias, comma, single-quote, dot, dash and slash.");
                        $errorName = true;
                    } 
                }
            }
            if (empty($_POST["expirationDate"])){
                array_push($error,"<b>Expiration Date</b> cannot empty.");
                $errorExpirationDate = true;
            }else{
                $expirationDate = antiExploit($_POST["expirationDate"]);
                if (strlen($expirationDate)!=5){
                    array_push($error,"<b>Expiration Date</b> invalid format.");
                    $errorExpirationDate = true;
                }else{
                    $pattern = '/^(1[0-2])|(0[1-9])\/([0-9]{2})$/';
                    if(!preg_match($pattern, $expirationDate)){
                        array_push($error,"<b>Expiration Date</b> invalid format.");
                        $errorExpirationDate = true;
                    }
                }
            }
            if (empty($_POST["cvv"])){
                array_push($error,"<b>CVV</b> cannot empty.");
                $errorCVV = true;
            }else{
                $cvv = antiExploit($_POST["cvv"]);
                if (strlen($cvv)!=3){
                    array_push($error,"<b>CVV</b> must 3 digit.");
                    $errorCVV = true;
                }else{
                    $pattern = '/^[0-9]{3}$/';
                    if(!preg_match($pattern, $cvv)){
                        array_push($error,"<b>CVV</b> must 3 digit.");
                        $errorCVV = true;
                    } 
                }
            }
            if (empty($error)){
                madeOrders();
            }
        }

        if (isset($_POST['submitTouchNGo'])){ //Touch 'n Go
            madeOrders();
        }

        if (isset($_POST['submitPaypal'])){ //PayPal

            if (!isset($_POST["payPalChoices"])){
                $errorChoice = true;
                array_push($error,"Choose a <b>Paypal</b> option.");
            }
            if (empty($error)){
                madeOrders();
            }
        }
    }
?>