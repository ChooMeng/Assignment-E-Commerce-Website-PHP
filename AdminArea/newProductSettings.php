<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/newproduct.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/client.js" type="text/javascript"></script>
        <script src="JavaScript/newProduct.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/newProductSettings.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to create the new product?</p>
        </div>
        <section id="box">
            <div class="title">
                <a href="products.php">Product</a>
                <a class="active" href="newproduct.php">New Product</a>
                <a href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
            </div>
            <div id="table">
                <h1>New product</h1>
                <?php displayErrorMessage(); ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <table id="products">
                        <tr>
                            <td colspan="2" style="font-size: 1.5em; background-color: #b8f2e6" >General Setting</td>
                            
                        </tr>
                        <tr>
                            <th width="20%">Category : </th>
                            <td>
                                <select name="categoryName">
                                    <option value="">--Select One--</option>
                                    <?php categoryOption() ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Name : </th>
                            <td><input id="text" type="text" name="name" value="<?php echo isset($_POST['name'])?$_POST['name']:""?>" size="50" /></td>
                        </tr>
                        <tr>
                            <th width="20%">Insert image file: </th>
                            <td><input id="text" type="file" name="image"/></td>
                        </tr>
                        <tr>
                            <th width="20%">Status : </th>
                            <td><input type="radio" name="status" value="Enabled" <?php if(isset($status) && $status == "Enabled") echo"checked";?>/> Enabled
                              <input type="radio" name="status" value="OutOfStock" <?php if(isset($status) && $status == "OutOfStock") echo"checked";?>/> Out of Stock</td>
                        </tr>
                        <tr>
                            <th width="20%">Stock : </th>
                            <td><input id="text" type="number" name="stock" value="<?php echo isset($stock)?$stock:""?>" size="5"></td>
                        </tr>
                        <tr>
                            <th width="20%">Discount (%): </th>
                            <td><input type="number" max="100" min="0" step="1" id="discount" name="discount" value="<?php echo isset($discount)?$discount:0?>"></td>
                        </tr>
                        <tr>
                            <th width="10%">Price (RM): </th>
                            <td><input type="number" max="10000" min="0" step="1" id="discount" name="price" value="<?php echo isset($price)?$price:0?>"></td>
                        </tr>
                        <tr>
                            <th width="20%">Product Describe : </th>
                            <td><textarea class="productDescribe" name="productDescribe" placeholder="Write down product description..." rows="6" cols="60"><?php echo isset($productDescribe)?$productDescribe:"";?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input id="create" type="button" style="float: right" onclick="confirmMessage()" value="Done"></td>
                        </tr>
                    </table>
                    <input hidden="true" name="createProduct" value="createProduct">
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>