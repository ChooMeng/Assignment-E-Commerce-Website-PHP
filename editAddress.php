<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/addAddress.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/addAddress.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/editAddress.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/editAddress.php';?>
        <?php include 'header.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to edit your address?</p>
        </div>
        <section id="content">
            <?php include 'topNavBar.php';?>
            <div id="mainContent">
                <div id="addressBlock">
                    <div>
                        <!--Edit the user address details-->
                        <form method="POST" id="addressForm" action="">
                            <h1 class="mainHeader">Edit Address</h1>
                            <?php displayErrorMessage();?>
                            <table>
                                <tr valign>
                                    <td>
                                        <label for="address">Address<?php echo isset($errorAddress)?displayError():"";?></label><br>
                                        <textarea id="address" name="address" rows="4" cols="30" class="enterInput" required><?php echo isset($_POST["address"])?$_POST["address"]:$address;?></textarea>
                                    </td>
                                    <td valign="top">
                                        <label for="zipCode">Zip Code<?php echo isset($errorZipCode)?displayError():"";?></label><br>
                                        <input type="text" name="zipCode" id="zipCode" class="enterInput" style="vertical-align:top" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:$zipCode;?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="city">City<?php echo isset($errorCity)?displayError():"";?></label><br>
                                        <input type="text" name="city" id="city" class="enterInput" value="<?php echo isset($_POST["city"])?$_POST["city"]:$city;?>" required>
                                    </td>
                                    <td>
                                        <label for="state">State<?php echo isset($errorState)?displayError():"";?></label><br>
                                        <select name="state" id="state" class="enterInput" required>
                                            <?php stateList(); ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <!--This addButton is same with Add address button in the addAddress.php-->
                            <table id="tableBtn">
                                <tr>
                                    <input hidden="true" name="editAddress" value="Add">
                                    <td><button type="button" id="addButton" name="editAddress"class="fButton" onclick="confirmMessage()">Save</button></td>
                                    <td><button type="reset" id="resetButton" style="cursor:pointer;" onclick="location.href=<?php echo $_SERVER['PHP_SELF']; ?>" class="fButton">Reset</button></td>
                                </tr>
                            </table>
                            <br>
                        </form>
                        <br>
                    </div>
                </div>
                
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
