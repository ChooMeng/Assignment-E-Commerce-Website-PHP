<?php
    session_start();
    if (!isset($_SESSION["staffId"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    if (isset($_SESSION['description'])){
        echo $_SESSION['description'];
        unset($_SESSION['description']);
    }
    