<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
<head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/changePassword.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/newPassword.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/newPassword.php';include 'header.php';?>
        <?php include 'topNavBar.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to change your password?</p>
        </div>
        <section id="newPwForm">
            <!--This page will allow the user to change their password after they pass the changePw.php page-->
            <div id="pwBlock">
                <div>
                    <!--They need to enter the new password and confirm password to let the js do the comparison-->
                    <form action="newPassword.php" id="newPwPage" method="POST">
                        <?php displayErrorMessage(); ?>
                        <h2>Enter your new password <?php echo isset($errorPassword)?displayError():""; ?></h2>
                        <input type="password" id="newPw" name="newPassword" required autofocus="autofocus">
                        
                        <!--The user need to enter their same new password for the second time-->
                        <h2>Confirm your password <?php echo isset($errorConfirmPassword)?displayError():""; ?></h2>
                        <input type="password" id="cfPw" name="confirmPassword" required>
                        
                        <br><br><br>
                        <input type="button" id="submitButton" value="SET TO NEW PASSWORD" onclick="confirmMessage()">
                        <input hidden="true" name="changepasswordnew" value="Add">
                    </form>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
