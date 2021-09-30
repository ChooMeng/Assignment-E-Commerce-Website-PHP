<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/modifyClient.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/modifyClient.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/clientModify.php';include 'header.php';?>
        <section>
            <div id="confirmationMessageProfile" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the edited client profile?</p>
            </div>
            <div id="confirmationMessagePassword" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to change the client password?</p>
            </div>
            <div id="confirmationMessageDefaultAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the default address?</p>
            </div>
            <div id="confirmationMessageModifyAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the edited existing address?</p>
            </div>
            <div id="confirmationMessageCreateAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to create the new address?</p>
            </div>
            <h1>Modify Client Details</h1>
            <!--Navigation Bar for switching profile, changePassword and address section-->
            <div class="modifyMenu">
                <a id="profile" class="active" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=profile'">Profile</a>
                <a id="changePassword" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=password'"><?php echo $databaseStatus != "Guest"?"Change Password":"Set Password"; ?></a>
                <a id="addressA" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=address'" <?php echo $databaseStatus!="Guest"?"":"style='display: none'"  ?>>Address</a>
            </div>
            <?php displayErrorMessage();?>
            <?php displayDatabaseErrorMessage();?>
            <!--Display the notifications-->
            <div class="notificationBox" id="notificationBox"></div> 
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
            <!-- Profile Section: Display exist client details and allow edit-->
            <div class="profile">
                <form id="profileForm" action="modifyClient.php?clientId=<?php echo $clientID;?>&type=profile" method="POST">
                    <div class="rightSide">
                        <label for="contactNumber" >Contact Number: <span class="required">*</span></label>
                        <input type="text" name="contactNumber" placeholder="xxx-xxxxxxxx" id="contactNumber" maxlength="12" value="<?php echo isset($_POST["contactNumber"])?$_POST["contactNumber"]:$contactNumber;?>">
                        <?php echo isset($errorContactNumber)?displayError():"";?>
                        <br/>
                        <label for="email">Email: <span class="required">*</span></label>
                        <input type="text" name="email" id="email" placeholder="abc@example.com" maxlength="50" value="<?php echo isset($_POST["email"])?$_POST["email"]:$email;?>">
                        <?php echo isset($errorEmail)?displayError():"";?>
                        <br/>
                        <label for="birthdate">Birth Date: <span class="required">*</span></label>
                        <input type="date" name="birthdate" id="birthdate" value="<?php echo isset($_POST["birthdate"])?$_POST["birthdate"]:$birthDate;?>">
                        <?php echo isset($errorBirthDate)?displayError():"";?>
                        <br/>
                        <label for="payment">Payment Method: <span class="required">*</span></label>
                        <select name="payment" id="payment">
                            <?php paymentList();?>
                        </select>
                        <?php echo isset($errorPayment)?displayError():"";?>
                    </div>
                    <div class="leftSide">
                        <label for="clientID">Client ID: </label>
                        <input style="cursor:no-drop;" type="text" name="clientID" id="clientId" value="<?php echo $clientID;?>" readonly disabled="true">
                        <br/>
                        <label for="username">Username: </label>
                        <input type="text" name="username" id="username" maxlength="40" placeholder="AhLim" value="<?php echo isset($_POST["username"])?$_POST["username"]:$userName;?>">
                        <?php echo isset($errorUserName)?displayError():"";?>
                        <br/>
                        <label for="name">Name: <span class="required">*</span></label>
                        <input type="text" name="name" id="name" maxlength="40" placeholder="Tan Ah Lim" value="<?php echo isset($_POST["name"])?$_POST["name"]:$name;?>">
                        <?php echo isset($errorName)?displayError():"";?>
                        <br/>
                        <label for="gender">Gender: <span class="required">*</span></label>
                        <div id="gen">
                            <?php genderList();?>
                            <?php echo isset($errorGender)?displayError():"";?>
                        </div>
                        <br/>
                        <label for="status">Account Status: <span class="required">*</span></label>
                        <select name="status" id="status">
                            <?php statusList()?>
                        </select>
                        <?php echo isset($errorStatus)?displayError():"";?>
                        <br/>
                        
                    </div>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageProfile()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='client.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyProfile">
                </form>
            </div>
            <!--Change Password section-->
            <div class="changePassword">
                <form id="changePasswordForm" action="modifyClient.php?clientId=<?php echo $clientID;?>&type=password" method="POST">
                    <label for="password">New Password: <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="8 - 32 characters" maxlength="32" value="<?php echo isset($_POST["password"])?$_POST["password"]:'';?>">
                    <?php echo isset($errorPassword)?displayError():"";?>
                    <br/>
                    <label for="confirmpassword">Confirm Password: <span class="required">*</span></label>
                    <input type="password" name="confirmpassword" placeholder="8 - 32 characters" id="confirmpassword" maxlength="32" value="<?php echo isset($_POST["confirmpassword"])?$_POST["confirmpassword"]:'';?>">
                    <?php echo isset($errorConfirmPassword)?displayError():"";?>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessagePassword()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='client.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyPassword">
                </form>
            </div>
            <!--Address section: Display exist address details and allow edit-->
            <div class="addressA"> <!-- Select an address and set it as default -->
                <form id="defaultAddressForm" action="modifyClient.php?clientId=<?php echo $clientID;?>&type=address" method="POST">
                    <div class="createMenu">
                        <button type="button" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=newaddress'">Add new address</button>
                    </div>
                    <table>
                        <tr>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Default <?php echo isset($errorDefault)?displayError():"";?></th>
                            <th>Action</th>
                        </tr>

                        <?php displayAddress();?>
                    </table>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageDefaultAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='client.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyAddress">
                </form>
                
            </div>
            <div class="modifyAddress"> <!-- Modify existing address -->
                <br/>
                <form id="modifyAddressForm" action="modifyClient.php?clientId=<?php echo $clientID;?>&type=address&address=<?php echo isset($_GET['address'])?$_GET['address']:"";?>" method="POST">
                    <label for="address">Address: <span class="required">*</span></label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST["address"])?$_POST["address"]:$address;?></textarea>
                    <?php echo isset($errorAddress)?displayError():"";?>
                    <br/>
                    <label for="zipCode">Zip Code: <span class="required">*</span></label>
                    <input type="text" name="zipCode" placeholder="11111" id="zipCode" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:$zipCode;?>">
                    <?php echo isset($errorZipCode)?displayError():"";?>
                    <br/>
                    <label for="city">City: <span class="required">*</span></label>
                    <input type="text" name="city" id="city" maxlength="28" placeholder="Gelugor" value="<?php echo isset($_POST["city"])?$_POST["city"]:$city;?>">
                    <?php echo isset($errorCity)?displayError():"";?>
                    <br>
                    <label for="state">State: <span class="required">*</span></label>
                    <select name="state" id="state">
                        <?php stateList();?>
                    </select>
                    <?php echo isset($errorState)?displayError():"";?>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageModifyAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=address'">
                    </div>
                    <input hidden="true" name="type" value="modifyExistAddress">
                </form>
            </div>
            <div class="newAddress"> <!-- Create new address -->
                <form id="newAddressForm" method="POST" action="modifyClient.php?clientId=<?php echo $clientID;?>&type=newaddress">
                    <label for="address">Address: <span class="required">*</span></label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST["address"])?$_POST["address"]:"";?></textarea>
                    <?php echo isset($errorAddress)?displayError():"";?>
                    <br/>
                    <label for="zipCode">Zip Code: <span class="required">*</span></label>
                    <input type="text" name="zipCode" placeholder="11111" id="zipCode" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:"";?>">
                    <?php echo isset($errorZipCode)?displayError():"";?>
                    <br/>
                    <label for="city">City: <span class="required">*</span></label>
                    <input type="text" name="city" id="city" maxlength="28" placeholder="Gelugor" value="<?php echo isset($_POST["city"])?$_POST["city"]:"";?>">
                    <?php echo isset($errorCity)?displayError():"";?>
                    <br>
                    <label for="state">State: <span class="required">*</span></label>
                    <select name="state" id="state">
                        <?php stateList();?>
                    </select>
                    <?php echo isset($errorState)?displayError():"";?>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageCreateAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='modifyClient.php?clientId=<?php echo $clientID;?>&type=address'">
                    </div>
                    <input hidden="true" name="type" value="newAddress">
                </form>
            </div>
            <!-- Check the section type -->
            <?php check();?>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
