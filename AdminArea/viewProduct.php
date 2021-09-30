<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/viewProduct.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/viewProduct.php';?>
        <section>
            <div class="title">
                <a href="products.php">Product</a>
                <a href="newproduct.php">New Product</a>
                <a href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
                <a class="active" href="">View Product</a>
            </div>
            <h1>Product Details</h1>
            <!-- Display staff details -->
            <div id="details">
                <p id="lastUpdate">Last update: 5-8-2020 13:30</p>
                <p id="productId">PRODUCT ID: P00001</p>
                <table id="products" width="100%">
                    <tr>
                        <th width="20%">Product ID : </th>
                        <td>
                            <p><?php echo $productID; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Category : </th>
                        <td>
                            <p><?php echo $categoryName; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Name : </th>
                        <td>
                            <p><?php echo $name; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Image: </th>
                        <td>
                            <p><img src="../uploads/<?php echo $save_as; ?>" width="20%" height="auto"></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Status : </th>
                        <td>
                            <p><?php echo $status; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Stock : </th>
                        <td>
                            <p><?php echo $stock; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Discount (%): </th>
                        <td>
                            <p><?php echo $discount; ?>%</p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Price :</th>
                        <td>
                            <p>RM <?php echo $price; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Product Describe : </th>
                        <td>
                            <p style="text-align:justify;"><?php echo $productDescribe; ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- Button for modify or back -->
            <div class="buttonMenu">
                <button id='modify' onclick='location.href="editProduct.php?id=<?php echo $productID; ?>"'>Modify</button>
                <button id='back' onclick='location.href ="products.php"'>Back</button>
            </div>
            <br/>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
