<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/newproduct.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/client.js" type="text/javascript"></script>
        <script src="JavaScript/editProduct.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';include 'Function/editproduct.php'?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to save the edited product details?</p>
        </div>
        <section id="box">
            <div class="title">
                <a href="products.php">Product</a>
                <a href="newproduct.php">New Product</a>
                <a href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
                <a class="active" href="">Edit Product</a>
            </div>
            <div id="table">
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <table id="products">
                        <tr>
                            <td colspan="2" style="font-size: 1.5em; background-color: #d8bbff" >Edit Product</td>
                        </tr>
                        <tr>
                            <?php displayErrorMessage(); ?>
                        </tr>
                        <tr>
                            <th width="20%">Category : </th>
                            <td><select name="categoryName">
                                    <option value="">--Select One--</option>
                                    <?php categoryOption(); ?>
                                </select></td>
                        </tr>

                        <tr>
                            <th width="20%">Name : </th>
                            <td><input id="text" type="text" name="editName" value="<?php echo isset($editName)?$editName:""?>" size="50" /></td>
                        </tr>

                        <tr>
                            <th width="20%">Insert image file: </th>
                            <td><input id="text" type="file" name="image" value="<?php echo isset($image)?$image:""?>"/></td>
                        </tr>

                        <tr>
                            <th width="20%">Status : </th>
                            <td><input type="radio" name="status" value="Enabled" <?php if(isset($status) && $status == "Enabled"){ echo"checked"; }else if($status=="Enabled"){ echo "checked";}?>/> Enabled
                                <input type="radio" name="status" value="OutOfStock" <?php if(isset($status) && $status == "OutOfStock"){ echo"checked";}else if($status=="OutOfStock"){ echo "checked";};?>/> Out of Stock</td>
                        </tr>

                        <tr>
                            <th width="20%">Stock : </th>
                            <td><input type="text" name="stock" value="<?php echo isset($stock)?$stock:""?>" size="5"></td>
                        </tr>
                        <tr>
                            <th width="20%">Discount (%): </th>
                            <td><input type="number" max="100" min="0" step="1" id="discount" name="discount" value="<?php echo isset($discount)?$discount:$discount?>"></td>
                        </tr>
                        <tr>
                            <th width="10%">Price (RM): </th>
                            <td><input type="number" max="10000" min="0" step="1" id="discount" name="price" value="<?php echo isset($price)?$price:$price?>"></td>
                        </tr>
                        <tr>
                            <th width="20%">Product Describe : </th>
                            <td><textarea class="productDescribe" name="productDescribe" placeholder="Write down product description..." rows="6" cols="60"><?php echo isset($productDescribe)? $productDescribe:$productDescribe;?></textarea></td>
                        </tr>

                        <tr>
                            <td colspan="2"><input id="create" onclick="confirmMessage()" type="button" style="float: right;cursor:pointer;" value="Done"></td>
                        </tr>
                    </table>
                    <input hidden="true" name="editProduct" value="editProduct">
                </form>
            </div>
        </section>

        <?php include 'footer.php'; ?>

    </body>
</html>