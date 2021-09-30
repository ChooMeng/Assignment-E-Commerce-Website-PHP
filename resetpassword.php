<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/resetpassword.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'function/resetpassword.php'; include 'header.php'; ?>
        <section id="resetPassword">
            <br/><br/>
            <div id="resetPasswordInfo">
                <!--Enter the user login details-->
                <h1>Reset Password</h1>
                <?php displayErrorMessage(); ?>
                <p>Please enter your new password at bellow</p>
                
                <form action="" method="POST">
                    <label for="newPassword"><h3>Set New Password</h3></label>
                    <input type="password" name="newPassword" id="npassword" class="enterInput" maxlength="32" placeholder="No more than 32 characters"/>
                    <label for="confirmPassword"><h3>Confirm New Password</h3></label>
                    <input type="password" name="confirmPassword" id="nconfirmPassword" class="enterInput" maxlength="32" placeholder="No more than 32 characters"/>
                    <br><br>
                    <div>
                        <input type="submit" id="submitBtn" name="newPasswordSubmit" value="Submit">
                    </div>
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>