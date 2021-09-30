<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href='CSS/loginsuccessful.css' rel='stylesheet' type='text/css'>
        <meta http-equiv="refresh" content="3; url=login.php" />
        <script src="JavaScript/loginsuccesful.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
    </head>
    <body>
        <?php 
            unset($_SESSION['staffId']);
        ?>
        <% 
            request.getSession().removeAttribute("username");
            response.sendRedirect("index.jsp")
        %>
        
        <div id="centreBox">
            <div class="coverBox">
                <h1>Logout Successful!</h1>
                <p class="login">Redirect to login page after <span id="time">3</span> seconds..</p>
            </div>
        </div>
    </body>
</html>
