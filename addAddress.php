<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/addAddress.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/addAddress.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/addAddress.php';include 'header.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to add your address?</p>
        </div>
        <section id="content">
            <?php include 'topNavBar.php';?>            
            <div id="mainContent">
                <div id="addressBlock">
                    <div>
                        <h1 class="mainHeader">Add Address</h1>
                        <?php displayErrorMessage();?>
                        <!--Adding address details-->
                        <form id="addressForm" action="addAddress.php" method="POST">
                            <table>
                                <tr>
                                    <td>
                                        <label for="address">Address<?php echo isset($errorAddress)?displayError():"";?></label><br>
                                        <textarea id="address" name="address" rows="4" cols="30" class="enterInput"required><?php echo empty($_POST["address"])?'':$address; ?></textarea>
                                    </td>
                                    <td valign="top">
                                        <label for="zipCode">Zip Code<?php echo isset($errorZipCode)?displayError():"";?></label><br>
                                        <input type="text" name="zipCode" id="zipCode" class="enterInput" maxlength="5" value="<?php echo empty($_POST["zipCode"])?'':$zipCode; ?>" style="vertical-align:top" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="city">City<?php echo isset($errorCity)?displayError():"";?></label><br>
                                        <input type="text" name="city" maxlength="28" id="city" class="enterInput" value="<?php echo empty($_POST["city"])?'':$city; ?>" required>
                                    </td>
                                    <td>
                                        <label for="state">State<?php echo isset($errorState)?displayError():"";?></label><br>
                                        <select name="state" id="state" class="enterInput" required>
                                            <?php stateList(); ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <input hidden="true" name="addAddress" value="Add">
                            <!--Submit and reset button-->
                            <table id="tableBtn">
                                <tr>
                                    <td><input type="button" id="addButton" class="fButton" value="Add" onclick="confirmMessage()"/></td>
                                    <td><input type="reset" style="cursor:pointer;" id="resetButton" class="fButton" onclick="location.href=<?php echo $_SERVER['PHP_SELF']; ?>" value="Reset"/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <br>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
