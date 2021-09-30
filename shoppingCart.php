<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/shoppingcart.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/shoppingcart.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/shoppingcart.php';include 'header.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <section>
            <div id="mainContent">
                <h1 class="mainHeader">Shopping Cart</h1>
                <?php displayErrorMessage(); ?>
                <div id="mainTable">
                    <div id="leftSide">
                        <table id="shoppingcartTable" cellspacing="0" width="100%">
                            <colgroup>
                                <col span="1" width="15%">
                                <col span="1" width="65%">
                                <col span="1" width="20%">
                            </colgroup>
                            <tr valign="top" id="cartHeader">
                                <th colspan="2" width="85%"><h3 id="itemHeader">Item</h3></th>
                                <th width="15%"><h3 id="priceHeader">Price</h3></th>
                            </tr>
                            

                            <?php guestShoppingCartList(); ?>
                       </table>
                    </div>
                    <div id="rightSide">
                        <div>
                            
                           <form action="" method="POST">
                               <table>
                                   <colgroup>
                                       <col id="leftSummary" span="1">
                                        <col id="rightSummary" span="1">
                                   </colgroup>
                                   <?php shoppingCartSummary(); ?>
                                   <tr colspan="2"><td><input type="submit" id="checkoutBtn" name='shoppingCart' value='Proceed to checkout'></td></tr>
                               </table>
                           </form>
                       </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
    <?php include 'footer.php';?>
</html>
