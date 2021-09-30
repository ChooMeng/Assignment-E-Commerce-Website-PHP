<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/register.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/register.php'; include 'header.php';?>
        <section id="register">
            <div id="newUserInfo">
                <h1>Daily Market Register</h1>
                <?php displayErrorMessage(); ?>
                <!--The user need to complete all the form section then they can make the register-->
                <form action="" method="POST">
                    <table cellspacing="10" id="userDetails">
                        <tr>
                            <td width="50%">
                                <label for="name"><h3>Name <?php isset($errorName)?displayError():""; ?></h3></label>
                                <input type="text" id="name" name="name" maxlength="40" value="<?php echo isset($name)?$name:""; ?>"><br>       
                            </td>
                            <td width="50%">
                                <label for="username"><h3>User Name <?php isset($errorUserName)?displayError():""; ?></h3></label>
                                <input type="text" id="username" name="username" maxlength="40" value="<?php echo isset($username)?$username:""; ?>"><br>            
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3>Gender <?php isset($errorGender)?displayError():""; ?></h3>
                                <select name="gender" id="gender">
                                    <?php genderList(); ?>
                                </select>
                            </td>
                            <td>
                                <label for="contactNumber"><h3>Contact Number <?php isset($errorContactNumber)?displayError():""; ?></h3></label>
                                <input type="text" id="phoneNumber" name="contactNumber" size="12" value="<?php echo isset($contactNumber)?$contactNumber:""; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email"><h3>Email Address <?php isset($errorEmail)?displayError():""; ?></h3></label>
                                <input type="email" id="emailAddress" name="email" maxlength="50" value="<?php echo isset($email)?$email:""; ?>">
                            </td>
                            <td>
                                <label for="birthdate"><h3>Birth Date <?php isset($errorBirthDate)?displayError():""; ?></h3></label>
                                <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($birthDate)?$birthDate:""; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label><h3>Payment Method <?php isset($errorPayment)?displayError():""; ?></h3></label>
                                <select name="payment" id="payMethod">
                                    <?php paymentList(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr><td colspan="4"><br/><hr></td></tr>
                        <!--Enter the new user address-->
                        <tr>
                            <td>
                                <label for="address"><h3>Address <?php isset($errorAddress)?displayError():""; ?></h3></label><br>
                                <textarea id="address" name="address" rows="4" cols="30" class="enterInput" maxlength="100"><?php echo isset($address)?$address:""; ?></textarea>
                            </td>
                            <td valign="top">
                                <label for="zipCode"><h3>Zip Code <?php isset($errorZipCode)?displayError():""; ?></h3></label><br>
                                <input type="text" name="zipCode" id="zipCode" class="enterInput" value="<?php echo isset($zipCode)?$zipCode:""; ?>" style="vertical-align:top">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="city"><h3>City <?php isset($errorCity)?displayError():""; ?></h3></label><br>
                                <input type="text" name="city" id="city" class="enterInput" value="<?php echo isset($city)?$city:""; ?>">
                            </td>
                            <td>
                                <label for="state"><h3>State <?php isset($errorState)?displayError():""; ?></h3></label><br>
                                <select name="state" id="state" class="enterInput">
                                    <?php stateList(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr><td colspan="4"><br/><hr></td></tr>
                        <tr>
                            <!--Set the new password for the new user-->
                            <td>
                                <label for="password"><h3>Password <?php isset($errorPassword)?displayError():""; ?></h3></label>
                                <input type="password" name="password" id="password" class="enterInput" maxlength="32" placeholder="No more than 32 characters"/>
                            </td>
                            <td>
                                <label for="confirmpassword"><h3>Confirm Password <?php isset($errorConfirmPassword)?displayError():""; ?></h3></label>
                                <input type="password" name="confirmpassword" id="confirmPassword" class="enterInput" maxlength="32" placeholder="No more than 32 characters"/>
                            </td>
                        </tr>
                    </table>
                     <!--Enter the button proceed after fillup register infomation-->
                    <br>
                    <input type="submit" id="registerBtn" name="register" value="Register">
                    <input type="reset" id="resetBtn" onclick="location.href=<?php echo $_SERVER['PHP_SELF']; ?>" value="Reset">
                    <p id="gotAcc"><a href="login.php">Having Account? Just Login</a></p>
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>