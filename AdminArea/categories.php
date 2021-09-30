<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/categories.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/categories.js" type="text/javascript"></script>
        
    </head>
    <body>
        <?php include 'header.php';include 'Function/categories.php';?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
        <div id='confirmationBox'> 
            <div class='contents'>
                <form id="removeForm" name="removeForm" action="" method="POST" onsubmit="return deleteCategory();">
                    <div class='header'>
                        Are you sure you want delete this Category?
                    </div>
                    <div class='body'>
                        <label for="reason">Reason:</label>
                        <br/>
                        <textarea type="textbox" name="reason" id="reason" required></textarea>
                        <div id='button'>
                            <input type="submit" class='yes' value="Yes">
                            <input type="button" class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful delete category.")' value="No">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <section id="box">
            <div class="title">
                <a href="products.php">Product</a>
                <a href="newproduct.php">New Product</a>
                <a class="active" href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
                <div class="bar">

                    <input type="text" class="searchBar" id="searchBar" onkeyup="search()" placeholder="Search product" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <div id="table">
                <h1>Categories List</h1>
                <table id="categories">
                    <tr style="background-color: #a0c4ff">
                        <th width="10%" onclick="sort(0);">Category ID<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="40%" onclick="sort(1);">Category Name<span id="sort"><i class="fas fa-sort"></i></span></th>
                        <th width="15%" style="cursor:default;">Action</th>
                    </tr>
                    <?php categoryList(); ?>
                </table>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
