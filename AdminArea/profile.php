<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/profile.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/profile.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <section>
            <div id="confirmationMessageProfile" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save your edited profile?</p>
            </div>
            <div id="confirmationMessagePassword" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to change your password?</p>
            </div>
            <h1 id="superTitle">Modify Profile</h1>
            <?php include 'Function/profile.php';?>
            <!--Navigation Bar for switching profile and changePassword section-->
            <div class="modifyMenu">
                <a id="profile" class="active" onclick="location.href='profile.php?type=profile'">Profile</a>
                <a id="changePassword" onclick="location.href='profile.php?type=password'">Change Password</a>
              
            </div>
            <!--Profile section: Display exist profile details and allow edit-->
            <div class="profile">
                <form id="profileForm" action="profile.php?type=profile" method="POST">
                    <!-- Right column of the form -->
                    <div class="rightSide">
                        <label for="contactNumber" >Contact Number: <span class="required">*</span></label>
                        <input type="text" name="contactNumber" placeholder="xxx-xxxxxxxx" id="contactNumber" maxlength="12" value="<?php echo isset($_POST["contactNumber"])?$_POST["contactNumber"]:$contactNumber?>">
                        <?php echo isset($errorContactNumber)?displayError():"";?>
                        <br/>
                        <label for="email">Email: <span class="required">*</span></label>
                        <input type="text" name="email" id="email" placeholder="abc@example.com" maxlength="50" value="<?php echo isset($_POST["email"])?$_POST["email"]:$email;?>">
                        <?php echo isset($errorEmail)?displayError():"";?>
                        <br/>
                        <label for="birthdate">Birth Date: <span class="required">*</span></label>
                        <input type="date" name="birthdate" id="birthdate" value="<?php echo isset($_POST["birthdate"])?$_POST["birthdate"]:$birthDate;?>">
                        <?php echo isset($errorBirthDate)?displayError():"";?>
                    </div>
                    <!-- Left column of the form -->
                    <div class="leftSide">
                        <label for="staffID">Staff ID: </label>
                        <input style="cursor:no-drop;" type="text" name="staffID" id="staffId" value="<?php echo $staffID;?>" readonly disabled="true">
                        <br/>
                        <label for="username">Username: <span class="required">*</span></label>
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
                    </div>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageProfile()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='dashboard.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyProfile">
                </form>
            </div>
            <!--ChangePassword section-->
            <div class="changePassword">
                <form id="changePasswordForm" action="profile.php?type=password" method="POST">
                    <label for="currentpassword">Current Password: <span class="required">*</span></label>
                    <input type="password" name="currentpassword" id="currentpassword" placeholder="8 - 32 characters" maxlength="32" value="<?php echo isset($_POST["currentpassword"])?$_POST["currentpassword"]:'';?>">
                    <?php echo isset($errorCurrentPassword)?displayError():"";?>
                    <br/>
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
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='dashboard.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyPassword">
                </form>
            </div>
            <!-- Check the section type -->
            <?php check();?>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
