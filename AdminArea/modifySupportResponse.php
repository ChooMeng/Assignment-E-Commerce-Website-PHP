<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/modifySupportResponse.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/modifySupportResponse.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <div id="imgModal">
            <span id="close" onclick="closeZoom()">&times;</span>
            <img id="zoomImg">
        </div>
        <section>
            <h1>Modify Ticket Responses</h1>
            <?php include 'Function/supportModifyResponse.php';?>
            <!-- Modify a ticket response -->
            <form method="POST" action="modifySupportResponse.php?supportId=<?php echo $supportID;?>&msgId=<?php echo $msgID;?>" enctype="multipart/form-data">
                <div id="messages">
                    <?php getMessages();?>
                </div>
                <br/>
                <!-- Button that control the form -->
                <div class="formCenter">
                    <input type="submit" value="Save" id="formButton" class="submit">
                    <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='viewSupport.php?supportId=<?php echo $supportID;?>'">
                </div>
                <input hidden="true" name="type" value="modifyResponses">
            </form>
            
            
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
