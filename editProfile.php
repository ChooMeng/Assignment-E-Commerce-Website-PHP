<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/editProfile.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/editProfile.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/editProfile.php'; include 'header.php'; ?>
        <section>
            <div id="confirmationMessageProfile" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save your edited profile?</p>
            </div>
            <!--Display the confirmation box while trying to remove address-->
            <div id='confirmationBox'> 
                <div class='contents'>
                    <div class='header'>
                        Are you sure you want remove this address?
                    </div>
                    <div class='body'>
                        <div id='button'>
                            <div class='yes' onclick="removeAddress()">Yes</div>
                            <div class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful remove address.")'>No</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Display the notifications-->
            <div class="notificationBox" id="notificationBox"></div>
            <?php include 'topNavBar.php';?>
            <div id="mainContent">
                <h1 class="mainHeader">Edit My Profile</h1>
                <?php displayErrorMessage(); ?>
                    <form id="profileForm" action="editProfile.php" method="POST">
                        <!--Edit user details-->
                        <table id="userDetails" cellspacing="5px" cellpadding="5px">
                            <tr>
                                <td width="50%">
                                    <label for="name"><h3>Name <?php isset($errorName)?displayError():""; ?></h3></label>
                                    <input type="text" id="name" name="name" maxlength="40" value="<?php echo isset($name)?$name:$name; ?>"><br>       
                                </td>
                                <td width="50%">
                                    <label for="username"><h3>User Name <?php isset($errorUserName)?displayError():""; ?></h3></label>
                                    <input type="text" id="username" name="username" maxlength="40" value="<?php echo isset($username)?$username:$username; ?>"><br>            
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
                                <input type="text" id="phoneNumber" name="contactNumber" size="12" value="<?php echo isset($contactNumber)?$contactNumber:$contactNumber; ?>">
                            </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="email"><h3>Email Address <?php isset($errorEmail)?displayError():""; ?></h3></label>
                                    <input type="email" id="emailAddress" name="email" maxlength="50" value="<?php echo isset($email)?$email:$email; ?>">
                                </td>
                                <td>
                                    <label for="birthdate"><h3>Birth Date <?php isset($errorBirthDate)?displayError():""; ?></h3></label>
                                    <input type="date" id="birthdate" name="birthdate" value="<?php echo isset($birthDate)?$birthDate:$birthDate; ?>">
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
                        </table>

                        <h2>Address</h2>
                        <!--Set user default address details-->
                        <table id="addressTable" cellspacing="5px" cellpadding="5px">
                            <tr>
                                <th id="receiverAddress">Address</th>
                                <th id="zipCode">Zipcode</th>
                                <th id="city">City</th>
                                <th id="state">State</th>
                                <th id="defaultAddress">Default Address</th>
                                <th id="delete">Delete Address</th>
                                <th id="editAddress">Edit Address</th>
                            </tr>
                            <?php displayAddress(); ?>
                        </table>
                        <br>
                        <div id="saveButton"><input type="button" value="Save" onclick="confirmMessageProfile()"/></div>
                        <input hidden="true" name="editProfile" value="editProfile">
                    </form>
                    <br>
                    <br>
            </div>
            
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
