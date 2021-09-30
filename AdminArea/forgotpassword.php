<!DOCTYPE html>
<?php
    session_start();
    include 'TempData/staffs.php';
    if (isset($_SESSION["staffId"])){
        header('location: dashboard.php');
    }
?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/forgetpassword.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
    </head>
    <body>
        <?php include 'Function/forgotpassword.php'; ?>
        <h1>Forgot Password</h1>
        
        <form action="" method="POST">
            <img src="../Media/favicon.png" width="210" height="210"/>
            
            <table border="0" cellspacing="0" cellpadding="5" width="370" height="200">
                <tr>
                    <td>
                        <?php displayMessage(); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <p>[<a href="login.php">Back to Login</a>]</p><br/>

                        <p>Please enter you email address below and
                        we will send you the information to change
                        your password.</p>
                        <br/><br/>

                        <p>Email Address</p>
                        <p><input type="email" name="emailAddress" maxlength="50" autofocus="autofocus" ></p>
                        <input type="submit" name="forgotPassword" value="Submit" />

                    </td>
                </tr>
            
            </table>
        </form> 
        <script>
        if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
        }
        </script>
    </body>
</html>
