<!DOCTYPE html>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <link href="CSS/products.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>

    </head>
    <body>
        <?php include 'Function/searchProduct.php';include 'header.php';?>
        <section>
            <div id="productTable">
                <h1 id="pageTitle">Product List</h1>
                <table>
                    <h1 id="headerTitle">Result of <?php echo $_GET['search']; ?></h1>
                    <?php allProducts(); ?>
                </table>
                <br/>
                <table>
                    <h1 id="headerTitle">Promotions</h1>
                    <?php discountProduct(); ?>
                </table>
            </div>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>