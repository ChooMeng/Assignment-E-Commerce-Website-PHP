<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/unAuthPurchase.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include 'Function/unAuthPurchase.php'; include 'header.php';?>
        <section>
            <div id="mainContent">
            <!--This section is for those customer who don't want to login and purchase-->
                <h1 class="mainHeader">Purchase without login</h1>
                <?php displayErrorMessage(); ?>
                <form action="#" method="POST" id="shippingForm">
                    <h1>Customer details</h1>

                    <table id="custDetails" cellspacing="5px" cellpadding="5px">
                        <tr>
                            <td width="50%">
                                <label for="name"><h3><span class="important">*</span> Customer Name <?php isset($errorName)?displayError():""; ?></h3></label>
                                <input type="text" id="name" name="name" maxlength="40" value="<?php echo isset($name)?$name:""; ?>"><br>       
                            </td>
                            <td width="50%">
                                <label for="contactNumber"><h3><span class="important">*</span> Contact Number <?php isset($errorContactNumber)?displayError():""; ?></h3></label>
                                <input type="text" id="phoneNumber" name="contactNumber" size="12" value="<?php echo isset($contactNumber)?$contactNumber:""; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email"><h3><span class="important">*</span> Email Address <?php isset($errorEmail)?displayError():""; ?></h3></label>
                                <input type="text" id="emailAddress" name="email" maxlength="50" value="<?php echo isset($email)?$email:""; ?>">
                            </td>
                            <td>
                                <label for="gender"><h3><span class="important">*</span> Gender <?php isset($errorGender)?displayError():""; ?></h3></label>
                                <select name="gender" id="gender">
                                    <?php genderList(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="birthdate"><h3><span class="important">*</span> Birth Date <?php isset($errorBirthDate)?displayError():""; ?></h3></label>
                                <input type="date" id="emailAddress" name="birthdate" value="<?php echo isset($birthDate)?$birthDate:""; ?>">
                            </td>
                            <td>
                                <label for="payment"><h3><span class="important">*</span> Payment Method <?php isset($errorPayment)?displayError():""; ?></h3></label>
                                <select name="payment" id="payment">
                                    <?php paymentList(); ?>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <br><hr><br>
                    <h1>Shipping Details</h1>
                    <table id="shippingDetails" cellspacing="5px" cellpadding="5px">
                        <tr>
                            <td>
                                <label for="address"><h3><span class="important">*</span> Address <?php isset($errorAddress)?displayError():""; ?></h3></label><br>
                                <textarea id="address" name="address" rows="4" cols="30" class="enterInput" maxlength="100"><?php echo isset($address)?$address:""; ?></textarea>
                            </td>
                            <td valign="top">
                                <label for="zipCode"><h3><span class="important">*</span> Zip Code <?php isset($errorZipCode)?displayError():""; ?></h3></label><br>
                                <input type="text" name="zipCode" id="zipCode" class="enterInput" value="<?php echo isset($zipCode)?$zipCode:""; ?>" style="vertical-align:top">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="city"><h3><span class="important">*</span> City <?php isset($errorCity)?displayError():""; ?></h3></label><br>
                                <input type="text" name="city" id="city" class="enterInput" value="<?php echo isset($city)?$city:""; ?>">
                            </td>
                            <td>
                                <label for="state"><h3><span class="important">*</span> State <?php isset($errorState)?displayError():""; ?></h3></label><br>
                                <select name="state" id="state" class="enterInput">
                                    <?php stateList(); ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <br><br><br>
                    <div class="button">
                        <input type="submit" name="unAuthPurchase" value="SUBMIT" id="sButton" class="fButton">
                        <input type="reset" value="RESET" id="rButton" onclick="location.href=<?php echo $_SERVER['PHP_SELF']; ?>" class="fButton">
                    </div>
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>