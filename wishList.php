<!DOCTYPE html>
<?php require 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/wishList.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/wishList.js" type="text/javascript"></script>
        <script src="JavaScript/default.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/wishList.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <section>     
            <div id="mainContent">
                <h1 class="mainHeader">My Wishlist</h1>
                <form method="POST">
                    <!--Shows the product that has been added to wishlist-->
                    <table id="wishlistTable" width="100%">
                        <colgroup>
                            <col span="1" width="20%">
                            <col span="1" width="50%">
                            <col span="1" width="20%">
                            <col span="1" width="10%">
                        </colgroup>
                        <tr>
                            <th colspan="2">Item</th>
                            <th>Price</th>
                            <th><i class="fas fa-shopping-cart"></i></th>
                        </tr>
                        <?php displayWishlist();?>
                    </table>
                </form>
                <br><br><br><br>
            </div>
        
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
