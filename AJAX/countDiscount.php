<?php
    session_start();
    if (!isset($_GET["discount"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../shoppingCart.php');
    }
    $discount = $_GET['discount'];
    if (!empty($discount)){
        $discountArray = array("1283-3368"=>5,"3247-2178"=>8,"3782-7389"=>6,"3712-3892"=>5,"3478-4398"=>10,
 "2689-2321"=>10,"4368-4289"=>9,"9270-4302"=>15,"6421-7532"=>6,"3891-3426"=>10);
        if (in_array($discount, array_keys($discountArray))){
            $_SESSION['discountApply'] = $discountArray[$discount];
            $_SESSION['applied'] = true;
        }else{
            $_SESSION['discountApply'] = 0;
            $_SESSION['applied'] = false;
        }
    }else{
        $_SESSION['applied'] = false;
    }
    
    
    

