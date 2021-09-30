<!DOCTYPE html>
<?php include 'Function/login.php';  ?>
<html>
<head>
    <?php include 'Function/default.php';?>
    <link href='CSS/login.css' rel='stylesheet' type='text/css'>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
</head>
<body>
    <div id="centreBox">
        
        <img src="../Media/favicon.png" width="230" height="230">
        <div class="coverBox">
            <h1>Admin Login</h1>
            <?php displayErrorMessage();?>
            <form action="" method="POST">
                <input class="username" name="username" type="text" placeholder="User Name" required="required" autofocus="autofocus" value="<?php echo isset($username)?$username:"";?>">
                <input class="username" name="password" type="password" placeholder="Password" required="required" value="<?php echo isset($password)?$password:"";?>" >
                <input type="submit" name="login" value="Login" >
            </form>
        </div>
        
        <p>Forgot your password? <a class="forget" href="forgotpassword.php">Click Here!</a></p>
    </div>        
</body>
</html>