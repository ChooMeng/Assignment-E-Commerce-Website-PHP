<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/addsupport.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/addsupport.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include'Function/supportCreate.php';include 'header.php'; ?>
        <div id="confirmationMessageCreate" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to create the support ticket?</p>
        </div>
        <section>
            
            <!--Form of create new support-->
            <form id="createForm" action="addSupport.php" method="POST">
                <h1>Create new ticket</h1>
                <?php displayErrorMessage(); ?>
                <br/>
                <label for="name">Name: <span class="required">*</span></label><br/>
                <input type='text' name="name" id="name" value="<?php echo $name; ?>" readonly>
                <br/><br/>
                <label for="email">Email: <span class="required">*</span></label><br/>
                <input type='text' name="email" id="email" value="<?php echo $email; ?>" readonly>
                <br/><br/>
                <label for="priority">Priority: <span class="required">*</span></label>
                <br/>
                <select name="priority" id="prioritys">
                    <?php priorityList(); ?>
                </select>
                <?php echo isset($errorPriority)?displayError():"";?>
                <br/><br/>
                <label for="subject">Subject: <span class="required">*</span></label><br/>
                <input type="text" name="subject" id="subject" maxlength="300" placeholder="Subject" value="<?php echo isset($_POST["subject"])?$_POST["subject"]:'';?>">
                <?php echo isset($errorSubject)?displayError():"";?>
                <br/><br/>
                <label for="message">Message: <span class="required">*</span></label><br/>
                <textarea id="message" name="message"><?php echo isset($_POST["message"])?$_POST["message"]:''; ?></textarea>
                <?php echo isset($errorMessage)?displayError():"";?>
                <br/><br/>
                <label for="attachment">Attachment: </label><br/>
                <input type="file" name="attachment" id="attachment" value="<?php echo isset($_POST["attachment"])?$_POST["attachment"]:'';?>">
                <?php echo isset($errorAttachment)?displayError():"";?>
                <br/><br/><br/>
                <!-- Button that control the form -->
                <div class="formCenter">
                    <input type="button" value="Submit" id="formButton" class="submit" onclick="confirmMessageCreate()">
                    <input type="reset" value="Reset" id="formButton" class="reset" onclick="location='<?php echo $_SERVER['PHP_SELF']?>'">
                    <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='supports.php'">
                </div>
                <input hidden="true" name="type" value="create">
            </form>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
