<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/changePassword.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/changePassword.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/changePassword.php'; include 'header.php';?>
        <?php include 'topNavBar.php';?>
        <section id="firstPage">
            
            <!--Before the user want to go into the setNewPw.php they need to enter their current password first-->
            <div id="pwBlock">
                <div>
                    <h1 id="changePwH1">Enter your current password</h1>
                    <?php displayErrorMessage(); ?>
                    <form action="changePassword.php" method="POST" onsubmit="return validatePw();">
                        <input type="password" name="currentPwd" id="currentPwd" required="required" autofocus="autofocus">
                        <br><br><br>
                        <input type="checkbox" onclick="showPw()" name="showPassword" id="showPassword">
                        <label for="showPassword" id="showPwCheckbox">Show Password</label>
                        <br><br><br>
                        <input type="submit" value="PROCEED" name="changepassword" id="proceedButton">
                    </form>
                </div>
            </div>
            
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
