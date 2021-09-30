<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/viewSupport.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/viewSupport.js" type="text/javascript"></script>
    </head>
    <body>
        <!--Display the notifications-->
         <div class="notificationBox" id="notificationBox"></div>
        <?php include 'header.php'; ?>
         <div id="imgModal">
            <span id="close" onclick="closeZoom()">&times;</span>
            <img id="zoomImg">
        </div>
        <section>
            <h1>Support Ticket</h1>
            <?php include 'Function/supportView.php'?>
            <!-- Box for update the support status and priority -->
            <div id='supportBox'> 
                <div class='contents'>
                    <div class='header'>
                        Change status and priority for a ticket.
                    </div>
                    <div class='body'>
                        <form action="supports.php" method="POST" style="text-align:center" onsubmit="return updateStatus();">
                            <label for="statusUpdate">Status: </label><br/>
                            <select name="statusUpdate" id="status">
                                <?php statusList();?>
                            </select><br/><br/>
                            <label for="priorityUpdate">Priority: </label><br/>
                            <select name="priorityUpdate" id="priority">
                                <?php priorityList();?>
                            </select> 
                            <br/>
                            <br/>
                            <div id='button'>
                                <input type="submit" class='yes' value="Update">
                                <input type="button"class='no' onclick='closeSupportBox(),addNotification("yellow","<b>Cancelled</b>! Update ticket status and priority.")' value="Cancel">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Display the confirmation box while trying to remove ticket-->
            <div id='confirmationBox'> 
                <div class='contents'>
                    <form id="removeForm" name="removeForm" action="" method="POST" onsubmit="return removeSupport();">
                        <div class='header'>
                            Are you sure you want remove this ticket response?
                        </div>
                        <div class='body'>
                            <label for="reason" style="color:black">Reason:</label>
                            <br/>
                            <textarea type="textbox" name="reason" id="reason" required></textarea>
                            <div id='button'>
                                <input type="submit" class='yes' value="Yes">
                                <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful remove support tickets.")' value="No">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="content">
                <!-- Display client details and support tickets details -->
                <div id="details">
                    <?php details(); ?>
                </div>
                <hr/>
                <!-- Get ticket responses -->
                <div id="messages">
                    <h2 style="text-align:center">Responses</h2>
                        <?php getMessages();?>
                    
                </div>
                <br>
                <hr/>
                <!-- Button for modify or back or update the status and priority -->
                <div class="buttonMenu">
                    <button id='modify' onclick='location.href="modifySupport.php?supportId=<?php echo $supportID;?>"'>Modify</button>
                    <button id='update' onclick='openSupportBox("<?php echo $supportID;?>")'>Update</button>
                    <button id='back' onclick='location.href ="supports.php"'>Back</button>
                </div>
                <hr/>
                <!-- Reply the ticket -->
                <div id="reply">
                    <h2 style="text-align:center">Reply the ticket</h2>
                    <form action="viewSupport.php?supportId=<?php echo $supportID;?>" method="POST" enctype="multipart/form-data">
                        <table id="replyForm">
                            <tr>
                                <td width="10%"><label for="response">Response: <span class="required">*</span></label></td>
                                <td width="90%">
                                    <textarea id="response" name="response"><?php echo isset($_POST["response"])?$_POST["response"]:''; ?></textarea>
                                    <?php echo isset($errorResponse)?displayError():"";?>
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label for="attachment">Attachment: </label></td>
                                <td width="90%" style="text-align:left;">
                                    <input type="file" id="attachment" name="attachment" value="<?php echo isset($_POST["attachment"])?$attachment:''; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label for="status">Status: <span class="required">*</span></label></td>
                                <td width="90%" style="text-align:left;">
                                    <select name="status" id="statuss">
                                        <?php statusList();?>
                                    </select>
                                    <?php echo isset($errorStatus)?displayError():"";?>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <!-- Button that control the form -->
                        <div class="formCenter">
                            <input type="submit" value="Reply" id="formButton" class="submit">
                            <input type="reset" value="Reset" id="formButton" class="reset" onclick="location='viewSupport.php?supportId=<?php echo $supportID;?>'">
                        </div>
                        <br/>
                        <input hidden="true" name="type" value="reply">
                    </form>
                </div>
                <br>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>

