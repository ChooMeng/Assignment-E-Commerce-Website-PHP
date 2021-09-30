<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/modifySupport.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/modifySupport.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to save the edited support details?</p>
        </div>
        <section>
            <h1>Modify Ticket Details</h1>
            <?php include 'Function/supportModify.php';?>
            <!--Navigation Bar for support ticket details and responses section-->
            <div class="modifyMenu">
                <a id="details" class="active" onclick="location.href='modifySupport.php?supportId=<?php echo $supportID;?>&type=details'">Details</a>
                
            </div>
            <!-- Support ticket details: Display exist support order details and allow edit -->
            <div class="details">
                <form action="modifySupport.php?supportId=<?php echo $supportID;?>&type=details" method="POST">
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
                    <div class="leftSide">
                        <label for="ticketID">Ticket ID: </label>
                        <input style="cursor:no-drop;" type="text" name="ticketID" id="ticketId" value="<?php echo $supportID;?>" readonly disabled="true">
                        <br/>
                        <label for="clientId">Client ID: <span class="required">*</span></label>
                        <input list="clientList" type="text" name="clientId" id="clientId" maxlength="7" placeholder="Cxxxxxx" autocomplete="off" value="<?php echo isset($_POST["clientId"])?$_POST["clientId"]:$clientID;?>" oninput="suggestClient(),displayAddress()">
                        <dataList id="clientList" name="clientId"></dataList>
                        <?php echo isset($errorClientId)?displayError():"";?>
                        <br/>
                        <label for="subject">Subject: <span class="required">*</span></label>
                        <input type="text" name="subject" id="subject" maxlength="300" placeholder="Subject" value="<?php echo isset($_POST["subject"])?$_POST["subject"]:$subject;?>">
                        <?php echo isset($errorSubject)?displayError():"";?>
                        <br/>
                        
                    </div>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessage()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='supports.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyDetails">
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
