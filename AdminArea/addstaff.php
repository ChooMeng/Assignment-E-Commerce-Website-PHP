<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/addstaff.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/addstaff.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <section>
            <div id="confirmationMessageCreate" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to create the staff account?</p>
            </div>
            <h1>Add new staff</h1>
            <?php include 'Function/Staffcreate.php'; ?>
            <!--Form of create new staff-->
            <form id="createForm" action="addstaff.php" method="POST">
                <!-- Right column of the form -->
                <div class="rightSide">
                    <label for="gender">Gender: <span class="required">*</span></label>
                    <div id="gen">
                        <?php genderList();?>
                        <?php echo isset($errorGender)?displayError():"";?>
                    </div>
                    <br/>
                    <label for="contactNumber" >Contact Number: <span class="required">*</span></label>
                    <input type="text" name="contactNumber" placeholder="xxx-xxxxxxxx" id="contactNumber" maxlength="12" value="<?php echo isset($_POST["contactNumber"])?$_POST["contactNumber"]:'';?>">
                    <?php echo isset($errorContactNumber)?displayError():"";?>
                    <br/>
                    <label for="email">Email: <span class="required">*</span></label>
                    <input type="text" name="email" id="email" placeholder="abc@example.com" maxlength="50" value="<?php echo isset($_POST["email"])?$_POST["email"]:'';?>">
                    <?php echo isset($errorEmail)?displayError():"";?>
                    <br/>
                    <label for="birthdate">Birth Date: <span class="required">*</span></label>
                    <input type="date" name="birthdate" id="birthdate" value="<?php echo isset($_POST["birthdate"])?$_POST["birthdate"]:'';?>">
                    <?php echo isset($errorBirthDate)?displayError():"";?>
                    <br/>
                    <label for="position">Position: <span class="required">*</span></label>
                    <input type="text" name="position" id="position" maxlength="40" value="<?php echo isset($_POST["position"])?$_POST["position"]:'';?>">
                    <?php echo isset($errorPosition)?displayError():"";?>
                    <br/>
                    
                </div>
                <!-- Left column of the form -->
                <div class="leftSide">
                    <label for="username">Username: <span class="required">*</span></label>
                    <input type="text" name="username" id="username" maxlength="40" placeholder="AhLim" value="<?php echo isset($_POST["username"])?$_POST["username"]:'';?>">
                    <?php echo isset($errorUserName)?displayError():"";?>
                    <br/>
                    <label for="name">Name: <span class="required">*</span></label>
                    <input type="text" name="name" id="name" maxlength="40" placeholder="Tan Ah Lim" value="<?php echo isset($_POST["name"])?$_POST["name"]:'';?>">
                    <?php echo isset($errorName)?displayError():"";?>
                    <br/>
                    <label for="password">Password: <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="8 - 32 characters" maxlength="32" value="<?php echo isset($_POST["password"])?$_POST["password"]:'';?>">
                    <?php echo isset($errorPassword)?displayError():"";?>
                    <br/>
                    <label for="confirmpassword">Confirm Password: <span class="required">*</span></label>
                    <input type="password" name="confirmpassword" placeholder="8 - 32 characters" id="confirmpassword" maxlength="32" value="<?php echo isset($_POST["confirmpassword"])?$_POST["confirmpassword"]:'';?>">
                    <?php echo isset($errorConfirmPassword)?displayError():"";?>
                    <br/>
                    <label for="department">Department: <span class="required">*</span></label>
                    <select name="department" id="department">
                        <?php departmentList()?>
                    </select>
                    <?php echo isset($errorDepartment)?displayError():"";?>
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
                    <input type="button" value="Submit" id="formButton" class="submit" onclick="confirmMessageCreate()">
                    <input type="reset" value="Reset" id="formButton" class="reset" onclick="location='<?php echo $_SERVER['PHP_SELF']?>'">
                    <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='staffs.php'">
                </div>
                <input hidden="true" name="type" value="create">
            </form>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
