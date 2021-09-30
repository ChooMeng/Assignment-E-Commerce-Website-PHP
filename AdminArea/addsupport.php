<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/addsupport.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/addsupport.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="confirmationMessageCreate" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to create the support ticket?</p>
        </div>
        <?php include 'header.php'; ?>
        <section>
            <h1>Add new ticket</h1>
            <?php include'Function/supportCreate.php' ?>
            <!--Form of create new support-->
            <form id="createForm" action="addsupport.php" method="POST" enctype="multipart/form-data">
                <!-- Right column of the form -->
                <div class="rightSide">
                    <label for="status" >Status: <span class="required">*</span></label>
                    <select name="status" id="statuss">
                        <?php statusList(); ?>
                    </select>
                    <?php echo isset($errorStatus)?displayError():"";?>
                    <br/>
                    <label for="priority">Priority: <span class="required">*</span></label>
                    <select name="priority" id="prioritys">
                        <?php priorityList(); ?>
                    </select>
                    <?php echo isset($errorPriority)?displayError():"";?>
                    <br/>
                </div>
                <!-- Left column of the form -->
                <div class="leftSide">
                    <label for="clientId">Client ID: <span class="required">*</span></label>
                    <input list="clientList" type="text" name="clientId" id="clientId" maxlength="7" placeholder="Cxxxxxx" autocomplete="off" value="<?php echo isset($_POST["clientId"])?$_POST["clientId"]:'';?>" oninput="suggestClient(),displayAddress()">
                    <dataList id="clientList" name="clientId"></dataList>
                    <?php echo isset($errorClientId)?displayError():"";?>
                    <br/>
                    <label for="subject">Subject: <span class="required">*</span></label>
                    <input type="text" name="subject" id="subject" maxlength="300" placeholder="Subject" value="<?php echo isset($_POST["subject"])?$_POST["subject"]:'';?>">
                    <?php echo isset($errorSubject)?displayError():"";?>
                    <br/>
                    <label for="attachment">Attachment: </label>
                    <input type="file" name="attachment" id="attachment" value="<?php echo isset($_FILES["attachment"])?$_FILES["attachment"]:'';?>">
                    <?php echo isset($errorAttachment)?displayError():"";?>
                    <br/>
                </div>
                <!-- Message of the ticket -->
                <label for="message">Message: <span class="required">*</span></label><br/>
                <textarea id="message" name="message"><?php echo isset($_POST["message"])?$_POST["message"]:''; ?></textarea>
                <?php echo isset($errorMessage)?displayError():"";?>
                <br>
                <br>
                <br>
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
