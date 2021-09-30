<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/detailsStaff.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include 'header.php';include 'Function/staffDetails.php';?>
        <section>
            <h1>Staff Details</h1>
            <!-- Display staff details -->
            <div id="details">
                <?php details(); ?>
            </div>
            <!-- Button for modify or back -->
            <div class="buttonMenu">
                <button id='modify' onclick='location.href="modifyStaff.php?staffId=<?php echo $staffID;?>"'>Modify</button>
                <button id='back' onclick='location.href ="staffs.php"'>Back</button>
            </div>
            <br/>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
