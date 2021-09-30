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
        <link href="CSS/resetpassword.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
    </head>
    <body>
        <?php include 'Function/resetpassword.php'; ?>
        <h1>Reset Password</h1>
        
        <form action="" method="POST">
            <img src="../Media/favicon.png" width="210" height="210"/>
            
            <table border="0" cellspacing="0" cellpadding="5" width="370" height="200">
                <tr>
                    <td>
                        <?php displayErrorMessage(); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <p>[<a href="login.php">Back to Login</a>]</p><br/>

                        <p>Please enter your new password below</p>
                        <br/>
                        <p><label for="newPassword">New Password</label></p>
                        <p><input type="password" name="newPassword" id="newPassword" maxlength="32"/></p>
                        <p><label for="confirmPassword">Confirm New Password</label></p>
                        <p><input type="password" name="confirmPassword" id="confirmPassword" maxlength="32" placeholder="No more than 32 characters"/></p>
                        <p><input type="submit" name="newPasswordSubmit" value="Submit" /></p>

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
