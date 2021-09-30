<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/client.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/products.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <script src="JavaScript/client.js" type="text/javascript"></script>
        <script src="JavaScript/products.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/products.php'?>
        <!--Display the notifications-->
        <div class="notificationBox" id="notificationBox"></div>
         <div id='confirmationBox'> 
            <div class='contents'>
                <form id="removeForm" name="removeForm" action="" method="POST" onsubmit="return deleteProduct();">
                    <div class='header'>
                        Are you sure you want delete this Product?
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
                <a class="active" href="products.php">Product</a>
                <a href="newproduct.php">New Product</a>
                <a href="categories.php">Category</a>
                <a href="newCategory.php">New Category</a>
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search(this.value,<?php printf("'%s','%s','%s',%s,%s",$status,$sort,$sortOrder, isset($_GET['page'])?$_GET['page']:1,$totalAmount); ?>)" placeholder="Filter the products list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <div id="description">
                 <h1>Products List</h1>
                <!-- Display total product amount -->
                <div class="productMenu">
                    <p onclick="location.href='products.php?status=Enabled'"><span class="amount" style="background-color:#5cb85c;border-color: #4cae4c;"><?php echo $enableAmount;?></span>Enable</p>
                    <p onclick="location.href='products.php?status=OutOfStock'"><span class="amount" style="background-color:#f0ad4e;border-color: #eea236;"><?php echo $outOfStockAmount;?></span>Out Of Stock</p>
                    <p onclick="location.href='products.php'"><span class="amount"><?php echo $totalAmount; ?></span>Total Products</p>  
                </div>
            </div>
            
            <div id="table">
               
                <table id="products">
                     <?php productList(); ?>
                </table>
                <!-- Page number -->
                <div id="page">
                     <div id="pageNumber">
                        <?php displayPageNumber(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php include 'footer.php'; ?>

    </body>
</html>