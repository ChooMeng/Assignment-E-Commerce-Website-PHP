<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/forgotpassword.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <?php
        if (isset($_SESSION["clientId"])){
            header('location: index.php');
        }
    ?>
    <body>
        <?php include 'header.php';include 'Function/forgotpassword.php'?>
            <section id="forgotPassword">
                <div id="forgotPasswordInfo">
                    <img src="Media/favicon.png" width="230" height="230">
                    <h1>Forgot Password</h1>
                    <?php displayMessage(); ?>
                    <p>Please enter the account of email that you want reset the password and we will send you the information to change your password.</p>
                    <!--The user need to complete all the form section then they can make the register-->
                    <form id="forgetPasswordForm" action="" method="POST">
                        <div id="userDetails">
                            <!--Enter email details-->
                            <label for="emailAddress"><h3>Email Address</h3></label>
                            <input type="email" id="emailAddress" name="emailAddress" size="50" maxlength="50">
                        </div>  
                            <!--Enter next button after provide email details-->
                        <br/>
                       <div>
                           
                           <input type="submit" value="Submit" name="forgotPassword" id="nextBtn">
                           
                       </div>
                       <p id="loginAcc"><a href="login.php">Have an account?</a></p>
                    </form>
                </div>
            </section>
        <?php include 'footer.php';?>
        <script>
        if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
        }
        </script>
    </body>
</html>
