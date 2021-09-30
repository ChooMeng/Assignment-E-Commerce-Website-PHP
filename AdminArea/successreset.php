<!DOCTYPE html>
<?php
    session_start();
    if (isset($_SESSION["staffId"])){
        header('location: dashboard.php');
    }
?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/successreset.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
    </head>
    <body>
        <h1>Password Reset Successful !</h1>
        
        <form action="login.php" method="POST">
            <img src="../Media/favicon.png" width="210" height="210"/>
            
            <table border="0" cellspacing="0" cellpadding="5" width="370" height="200">
                
                <tr>
                    <td>
                        <p class="info">Woohoo ! Your password have been reset successfully! <br/><br/>
                            Please click the <b>'Back to Login'</b> button to login the system using <b>new password.</b></p>
                        <br/><br/>

                        <input type="submit" name="forgotPassword" value="Back To Login"/>

                    </td>
                </tr>
            
            </table>
        </form> 
    </body>
</html>
