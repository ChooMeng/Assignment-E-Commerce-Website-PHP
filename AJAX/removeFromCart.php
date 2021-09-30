<?php
    session_start();
    if (!isset($_GET["productID"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../shoppingcart.php');
    }
    include '../settings.php';
    //Prevent hacker to exploit the system
    function antiExploit($data){
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $productID = antiExploit($_GET["productID"]);
    if (isset($_SESSION['clientId'])){
        
    }else{
        $shoppingCart = $_SESSION['shoppingCart'];
        unset($shoppingCart[$productID]);
        $_SESSION['shoppingCart'] = $shoppingCart;
        if (empty($shoppingCart)){
            unset($_SESSION['shoppingCart']);
        }
    }
    $_SESSION['removed'] = true;

