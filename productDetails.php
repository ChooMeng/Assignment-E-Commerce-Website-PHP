<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/productDetails.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/productDetails.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'Function/productDetails.php';include 'header.php'; ?>
        <?php echo "<script>var productID = '$productID';</script>"; ?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <section>
            <div id="confirmationMessageWishList" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to put the product into wishlist?</p>
            </div>
            <div id="confirmationMessageCart" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to put the product into Shopping Cart?</p>
            </div>
            <div id="mainContent">
                <!--Pathway of the product-->
                <div>
                    <p id="productPathway">
                        <a href="index.php"><i class="fas fa-home"></i></a> <i class="fas fa-angle-right"></i> 
                        <a href="products.php?type=<?php $category; ?>"><?php echo $categoryName; ?></a> <i class="fas fa-angle-right"></i> 
                        <a href="#"><?php echo $name;?></a>
                    </p>
                </div>
                <!--Display product img + add to cart + add to wishlist-->
                <div>
                    <table id="productDisp" width="100%">
                        <tr>
                            <td id="productImgTD"><img src="uploads/<?php echo $image; ?>" alt="" class="productImg"></td>
                            <td valign="top" id="productDescTD">
                                <p id="productName"><?php echo $name; ?> <span style="font-size:18px">(Stock Left:<?php echo $stock; ?>)</span></p>
                                    <p id="addWishlist" <?php echo isset($_SESSION['clientId'])? 'onclick="confirmMessageWishList()"':'onclick="location.href=\'login.php\'"' ?>><i class="fas fa-heart"></i></p>
                                
                                <p id="productPrice"><strong>RM<?php echo $price; ?></strong></p>
                                
                                <p>
                                    <?php
                                    if ($stock>0){
                                        printf('<input type="number" name="qty" id="qty" value="1" max="%d" min="0">
                                    <span id="addToCart" onclick=\'confirmMessageCart()\'><i class="fas fa-cart-plus"></i></span>',$stock);
                                    }else{
                                        echo '<span style="color:red">Out of stock</span>';
                                    }
                                    
                                    
                                ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--Product information eg. features, ingredients, origin-->
                <div id="productInfo">
                    <div>
                        <h3>Description</h3>
                        <p><?php echo $productDesc; ?></p>
                        
                    </div>
                    
                </div>
                <div id="usingProductInfo">
                    <p><strong>Using Product Information</strong></p>
                    <p>
                    While every care has been taken to ensure product information is correct, food products are constantly being reformulated, 
                    so ingredients, nutrition content, dietary and allergens may change. You should always read the product label and not rely 
                    solely on the information provided on the website.<br>
                    If you have any queries, or you'd like advice on any Daily Market brand products, please contact Daily Market Customer Services, or the 
                    product manufacturer if not a Daily Market brand product.<br>
                    Although product information is regularly updated, Daily Market is unable to accept liability for any incorrect information. 
                    This does not affect your statutory rights.<br>
                    This information is supplied for personal use only, and may not be reproduced in any way without the prior consent of 
                    Daily Market Stores Limited nor without due acknowledgement.<br>
                    Daily Market © Copyright 2020<br>
                    </p>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>