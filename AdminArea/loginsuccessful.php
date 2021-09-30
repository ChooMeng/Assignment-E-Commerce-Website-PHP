<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href='CSS/loginsuccessful.css' rel='stylesheet' type='text/css'>
        <meta http-equiv="refresh" content="3; url=dashboard.php" />
        <script src="JavaScript/loginsuccesful.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
    </head>
    <body>
        <div id="centreBox">
            <div class="coverBox">
                <h1>Login Successful!</h1>
                <p class="login">Go to Dashboard after <span id="time">3</span> seconds..</p>
            </div>
        </div>
    </body>
</html>
