<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/notification.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';?>
        <section>
            <div id="mainContent">
                <h1 class="mainHeader">My Notification</h1>
                <!--Display notification list-->
                <table border>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Sender</th>
                        <th>Date</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>
                            <a href="#">You have complete your profile saving</a>
                        </td>
                        <td>Daily Market Admin</td>
                        <td>28/7/2020</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>
                        <a href="#">Hello Welcome to Daily Market</a>  
                        </td>
                        <td>Daily Market Admin</td>
                        <td>27/7/2020</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>
                        <a href="#">Please read this!!!</a>  
                        </td>
                        <td>Daily Market Admin</td>
                        <td>27/7/2020</td>
                    </tr>
                    <!--Display this message when the wishlist item has show finish-->
                    <tr>
                        <td colspan="4" id="endOfNotification"><b>This is the end of the notification</b></td>
                    </tr>
                    <!--Display this message when there don't have any item has been added to wishlist-->
                    <tr>
                        <td colspan="3" id="noNotification"><b>Hmm... seems like you haven't receive any message yet.</b></td>
                    </tr>
                </table>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
