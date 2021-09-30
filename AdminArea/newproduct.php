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
    </head>
    <body>
        <?php include 'header.php'; include 'Function/newproduct.php';?>
        <section id="box">
            <div class="title">
                <a href="products.php">Product</a>
                <a class="active" href="newproduct.php">New Product</a>
                <a href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
            </div>
            <div id="table">
                <h1>New product</h1>
                <?php displayErrorMessage();?>
                <form action="" method="POST">
                    <table id="products">

                    
                        <tr>
                            <th>Category : </th>
                            <td>
                                <select name="categoryName">
                                    <option value="">--Select One--</option>
                                    <?php categoryOption(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Name : </th>
                            <td><input id="text" type="text" name="name" size="30" value="<?php echo isset($newProductName)?$newProductName:""?>"></td>
                        </tr>
                        <tr>

                            <td colspan="2"><input id="create" type="submit" style="float: right" name="create" value="Create" ></td>
                        </tr>
                    </table>
                </form>
            </div>
                
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
