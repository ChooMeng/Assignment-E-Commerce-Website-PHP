<?php
    include 'settings.php';
    //Check session is started or not
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    //Set metadata of the website,title of the website, favicon of the website, font-family and icon
    $author = "Poh Choo Meng, Choo Zhi Yan, William Choong, Chew Wei Chung";
    $keywords = "Shopping, Market, Shop, Necessasry, Daily";
    $description = "A online shopping mart";
    $fileName = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
    $companyName = 'DailyMarket';
    switch (strtolower($fileName)){
        case 'addsupport.php':
            $pageTitle = "Create new ticket";
            break;
        case 'supports.php':
            $pageTitle = "My tickets";
            break;
        case 'viewsupport.php':
            $pageTitle = "View my ticket";
            break;
        case 'addaddress.php':
            $pageTitle = "Add an address";
            break;
        case 'profile.php':
            $pageTitle = "View my profile";
            break;
        case 'editprofile.php':
            $pageTitle = "Edit my profile";
            break;
        case 'changepassword.php':
            $pageTitle = "Change password";
            break;
        case 'newpassword.php':
            $pageTitle = "Set new password";
            break;
        case 'editaddress.php':
            $pageTitle = "Edit address";
            break;
        case 'notification.php':
            $pageTitle = "My notification";
            break;
        case 'wishlist.php':
            $pageTitle = "My wishlist";
            break;
        case 'vieworder.php':
            $pageTitle = "View my orders";
            break;
        case 'orderdetails.php':
            $pageTitle = "Order details";
            break;
        case 'productdetails.php':
            if (isset($_GET['productID'])){
                $type = $_GET['productID'];
               @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
                if ($con -> connect_errno) { //Check it is the connection succesful
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
                }else{
                    $sql = "SELECT * from productList WHERE product_ID = '$type'";
                    if(!$result = $con->query($sql)){
                        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                    }else{
                        while($row = $result->fetch_object()){
                            $productName = $row->Name;
                        }
                    }
                }
                $pageTitle = $productName; 
            }
            
            break;
        case 'unauthpurchase.php':
            $pageTitle = "Purchase without login";
            break;
        case 'voucher.php':
            $pageTitle = "Voucher";
            break;
        case 'index.php':
            $pageTitle = "Welcome to Daily Market";
            break;
        case 'checkout.php':
            $pageTitle = "Checkout";
            break;
        case 'termandconditions.php':
            $pageTitle = "Term and Conditions";
            break;
        case 'login.php':
            $pageTitle = "Login";
            break;
        case 'logout.php':
            $pageTitle = "Logout";
            break;
        case 'forgotpassword.php':
            $pageTitle = "Forgot password";
            break;
        case 'aboutus.php':
            $pageTitle = "About Us";
            break;
        case 'register.php':
            $pageTitle = "Register";
            break;
        case 'shoppingcart.php':
            $pageTitle = "Shopping Cart";
            break;
        case 'productlist.php':
            $pageTitle = "Product List";
            break;
        case 'products.php':
            $pageTitle = "Products";
            break;
        case 'resetpassword.php':
            $pageTitle = "Reset password";
            break;
        case 'searchproduct.php':
            $pageTitle = "Search Product";
            break;
    }
    if ($fileName == 'index.php'){
        printf('
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="%s">
            <meta name="keywords" content="%s">
            <meta name="description" content="%s">
            <title>%s</title>
            <link rel="icon" type="image/png" href="Media/logo.png"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        ',$author,$keywords,$description,$pageTitle);
    }else{
        printf('
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="%s">
            <meta name="keywords" content="%s">
            <meta name="description" content="%s">
            <title>%s | %s</title>
            <link rel="icon" type="image/png" href="Media/logo.png"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        ',$author,$keywords,$description,$pageTitle,$companyName);
    }
    
    function activeSubNavigation($name){
        $fileName = $GLOBALS['fileName'];
        echo $fileName==$name?' active':'';
    }
    function activeMainNavigation($name){
        $fileName = $GLOBALS['fileName'];
        echo $fileName==$name?"class='active'":"";
    }