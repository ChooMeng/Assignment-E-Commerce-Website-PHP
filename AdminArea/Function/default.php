<?php
    
    //Set metadata of the website,title of the website, favicon of the website, font-family and icon
    $author = "Poh Choo Meng, Choo Zhi Yan, William Choong, Chew Wei Chung";
    $keywords = "Shopping, Market, Shop, Necessasry, Daily";
    $description = "A online shopping mart";
    $fileName = pathinfo($_SERVER['PHP_SELF'],PATHINFO_BASENAME);
    $companyName = 'DailyMarket';
    switch (strtolower($fileName)){
        case 'profile.php':
            $pageTitle = "View my profile";
            break;
        case 'dashboard.php':
            $pageTitle = "Dashboard";
            break;
        case 'addclient.php':
            $pageTitle = "Add new client";
            break;
        case 'addorder.php':
            $pageTitle = "Add new order";
            break;
        case 'addstaff.php':
            $pageTitle = "Add new staff";
            break;
        case 'addsupport.php':
            $pageTitle = "Add new support ticket";
            break;
        case 'client.php':
            $pageTitle = "Clients list";
            break;
        case 'detailclient.php':
            $pageTitle = "Client details";
            break;
        case 'detailorder.php':
            $pageTitle = "Order details";
            break;
        case 'detailstaff.php':
            $pageTitle = "Staff details";
            break;
        case 'modifyclient.php':
            $pageTitle = "Modify client details";
            break;
        case 'modifyorder.php':
            $pageTitle = "Modify order details";
            break;
        case 'modifystaff.php':
            $pageTitle = "Modify staff details";
            break;
        case 'modifysupport.php':
            $pageTitle = "Modify support details";
            break;
        case 'modifysupportresponse.php':
            $pageTitle = "Modify Ticket Responses";
            break;
        case 'orders.php':
            $pageTitle = "Orders list";
            break;
        case 'staffs.php':
            $pageTitle = "Staffs list";
            break;
        case 'supports.php':
            $pageTitle = "Support tickets list";
            break;
        case 'viewsupport.php':
            $pageTitle = "View support";
            break;
        case 'login.php':
            $pageTitle = "Admin login";
            break;
        case 'loginsuccessful.php':
            $pageTitle = "Login succesful";
            break;
        case 'logout.php':
            $pageTitle = "Logout succesful";
            break;
        case 'forgotpassword.php':
            $pageTitle = "Forgot password";
            break;
        case 'products.php':
            $pageTitle = "Products List";
            break;
        case 'newproduct.php':
        case 'newproductsettings.php':
            $pageTitle = "New product";
            break;
        case 'editproduct.php':
            $pageTitle = "Edit product";
            break;
        case 'categories.php':
            $pageTitle = "Categories list";
            break;
        case 'newcategory.php':
            $pageTitle = "New category";
            break;
        case 'editcategory.php':
            $pageTitle = "Edit category";
            break;
        case 'auditlog.php':
            $pageTitle = "Audit logs";
            break;
        case 'detailaudit.php';
            $pageTitle = "Audit log details";
            break;
        case 'viewproduct.php';
            $pageTitle = "View product";
            break;
        case 'orderrestore.php';
            $pageTitle = "Restore removed orders";
            break;
        case 'supportrestore.php':
            $pageTitle = "Restore removed support tickets";
            break;
        case 'successreset.php':
            $pageTitle = "Password Reset Succesful";
            break;
        case 'productrestore.php':
            $pageTitle = "Product Restore";
            break;
        default:
            $pageTitle = "Admin Area";
            break;
    }
    printf('
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="%s">
        <meta name="keywords" content="%s">
        <meta name="description" content="%s">
        <title>%s | %s</title>
        <link rel="icon" type="image/png" href="../Media/favicon2.png"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    ',$author,$keywords,$description,$pageTitle,$companyName);
    