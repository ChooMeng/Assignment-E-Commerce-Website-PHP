<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/viewSupport.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/viewSupport.js" type="text/javascript"></script>
    </head>
    <body>
        
        <?php include 'Function/supportView.php';include_once 'header.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <div id="imgModal">
            <span id="close" onclick="closeZoom()">&times;</span>
            <img id="zoomImg">
        </div>
        <!--Display the confirmation box while trying to remove ticket-->
        <div id='resolvedConfirmationBox'> 
            <div class='contents'>
                <div class='header'>
                    Mark the ticket as resolved?
                </div>
                <div class='body'>
                    <div id='button'>
                        <div class='yes' onclick='resolvedTicket()'>Yes</div>
                        <div class='no' onclick='closeResolvedBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful mark the ticket as resolved.")'>No</div>
                    </div>
                </div>
            </div>
        </div>
        <div id='canceledConfirmationBox'> 
            <div class='contents'>
                <div class='header'>
                    Mark the ticket as canceled?
                </div>
                <div class='body'>
                    <div id='button'>
                        <div class='yes' onclick='closeCanceledBox(),canceledTicket()'>Yes</div>
                        <div class='no' onclick='closeCanceledBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful mark the ticket as canceled.")'>No</div>
                    </div>
                </div>
            </div>
        </div>
         
        <section>
            <div id="content">
                <h1>Support Ticket</h1>
                <?php displayErrorMessage();?> <!-- Display error message -->
                <!-- Display support tickets details -->
                <div id="details">
                    <?php details(); ?>
                </div>
                <div class="ticketStatusButton">
                    <button class="ticketResolved" onclick="openResolvedBox('<?php echo $supportID;?>');">Mark as Resolved</button>
                    <button class="ticketCanceled" onclick="openCanceledBox('<?php echo $supportID;?>');">Mark as Canceled</button>
                </div>
                <hr/>
                <!-- Get ticket responses -->
                <div id="messages">
                    <h2 style="text-align:center">Responses</h2>
                        <?php getMessages();?>
                    
                </div>
                <br>
                <hr/>
                 <!-- Button for back -->
                <div class="buttonMenu">
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
                        </table>
                        <br>
                        <!-- Button that control the form -->
                        <div class="formCenter">
                            <input type="submit" value="Reply" id="formButton" class="submit">
                            <input type="reset" value="Reset" id="formButton" class="reset" onclick="location='/ClientArea/AdminArea/viewSupport.php?supportId=<?php echo $supportID;?>'">
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

