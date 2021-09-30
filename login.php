<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/login.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/login.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/login.php';include 'header.php';?>
        <section id="login">
            <!--Display the notifications-->
            <div class="notificationBox" id="notificationBox"></div>
            <div id="UserInfo">
                <img src="Media/favicon.png" width="230" height="230">
                <!--Enter the user login details-->
                <h1>Daily Market Login</h1>
                <?php displayErrorMessage();showRegisterSuccesful();?>
                <form action="" method="POST">
                    <table id="userDetails" cellspacing="10">
                        <tr>
                            <td>
                                <label for="userName"><h3>User Name</h3></label>
                                <input type="text" id="userName" name="userName" required autofocus="autofocus" value="<?php echo isset($username)?$username:"";?>"><br>            
                                <label for="password"><h3>Password</h3></label>
                                <input type="password" name="password" id="password" class="enterInput" required/>
                            </td>
                        </tr>
                    </table>  
                        <!--Enter the submit button after provide login details-->
                        <br>
                        <div>
                            <input type="submit" name="login" id="loginBtn" value="Login">
                        </div>
                        <p id="forgetP"><a href="forgotpassword.php">Forgot password?</a></p>
                        <p id="noAcc"><a href="register.php">Create New Account</a></p>
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
