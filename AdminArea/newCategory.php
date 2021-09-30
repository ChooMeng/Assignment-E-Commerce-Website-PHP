<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/newCategory.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/newCategory.js" type="text/javascript"></script>
        
    </head>
    <body>
        <?php include 'header.php';include 'Function/newCategory.php'; ?>
        <div id="confirmationMessage" class="confirmationMessage" title="Confirmation Message">
            <p>Do you want to create the new category?</p>
        </div>
        <section id="box">
            <div id="table">
              <div class="title">
                    <a href="products.php">Product</a>
                    <a href="newproduct.php">New Product</a>
                    <a href="categories.php">Category</a>
                    <a class="active" href="newCategory.php">New Category</a>
              </div>
              <form action="" method="POST" enctype="multipart/form-data">
                  <h1>New Category</h1>
                    <?php displayErrorMessage();?>
                  <table id="category">
                      <tr>
                          <th style="background-color: #fbc4ab">New category name : </th>
                          <td><input id="text" type="text" name="categoryName" value="<?php echo isset($categoryName)?$categoryName:""?>" maxlength="30" /></td>
                      </tr>
                      <tr>
                          <th style="background-color: #fbc4ab">Category Icon : </th>
                          <td><input id="icon" type="file" name="categoryIcon"></td>
                      </tr>
                      <tr>
                          <td colspan="2"><input id="create" type="button" onclick="confirmMessage()" style="float: right" value="Create"></td>
                      </tr>
                  </table>
                  <input hidden="true" name="createCategory" value="createCategory">
              </form>  
            </div>
        </section>
        <?php include 'footer.php';?>
          
    </body>
</html>
