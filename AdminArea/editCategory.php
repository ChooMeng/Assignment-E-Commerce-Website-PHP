<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/newCategory.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/editCategory.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php';include 'Function/editCategory.php';?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to save the edited category details?</p>
        </div>
        <section id="box">
            <div id="table">
                <div class="title">
                    <a href="products.php">Product</a>
                    <a href="newproduct.php">New Product</a>
                    <a href="categories.php">Category</a>
                    <a href="newCategory.php">New Category</a>
                    <a class="active" href="#">Edit Category</a>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <table id="category">

                        <tr >
                            <th style="background-color: #fbc4ab" colspan="2">Edit Category</th>
                        </tr>
                        <tr>
                            <?php displayErrorMessage();?>
                        </tr>
                        <tr>
                            <th style="background-color: #fbc4ab">Change Name : </th>
                            <td><input id="text" type="text" name="categoryName" value="<?php echo isset($_POST['categoryName'])?$_POST['categoryName']:$categoryName; ?>" maxlength="30" /></td>
                        </tr>
                        <tr>
                            <th style="background-color: #fbc4ab">Category Icon : </th>
                            <td><input id="icon" type="file" name="categoryIcon"></td>
                        </tr>
                        <tr>
                            <th style="background-color: #fbc4ab">Current Icon : </th>
                            <td><img src="../uploads/<?php echo $currentIcon; ?>" style="max-height: 100px;max-width: 100%" ></td>
                        </tr>
                         <tr>
                             <td colspan="2"><input id="create" onclick="confirmMessage()" type="button" style="float: right" value="Done"></td>
                        </tr>
                    </table>
                    <input hidden="true" name="editCategory" value="editCategory">
                </form>
            </div>
        </section>
        <?php include 'footer.php';?>
        
    </body>
</html>