<script src="https://kit.fontawesome.com/3f628a0091.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Sans">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow">
<?php include_once 'Function/header.php';?>
<!-- Top navigation bar -->
<nav class="menu">
    <ul class="nav">
        <li>
            <a href="index.php" <?php activeMainNavigation('index.php');  ?>><i class="fas fa-home"></i> Home</a>
        </li>
        <!-- Large resolution navigation bar -->
        <li class="products" onmouseover="hover(this)" onmouseleave="unHover(this)">
            <a href='productList.php' style='cursor:pointer;'<?php activeMainNavigation('products.php');  ?> <?php activeMainNavigation('productList.php');  ?>><i class="fas fa-shopping-bag"></i> Products</a>
            <div class="productList" >
                <?php productCategory(); ?>
            </div>
        </li>
        <li>
            <a href="aboutus.php" <?php activeMainNavigation('aboutus.php');?>><i class="fas fa-address-card"></i> About Us</a>
        </li>
        <li>
            <a href="addsupport.php" <?php activeMainNavigation('supports.php');activeMainNavigation('addsupport.php');;activeMainNavigation('viewSupport.php');?>><i class="fas fa-phone"></i> Contact Us</a>
        </li>
        <li class="account">
            <?php loggedIn(); ?>
        </li>
    </ul>
    <!-- Small resolution navigation bar -->
    <ul class="mobile-nav">
        <li id="sideMode" ><a onclick="openSideNav()">&#9776</a></li>
    </ul>
    <ul class="sideBar">
        <li>
            <a href="index.php" <?php activeMainNavigation('index.php');  ?>><i class="fas fa-home"></i> Home</a>
        </li>
        <li>
            <a onclick="mobileProduct(this)" <?php activeMainNavigation('products.php');  ?> <?php activeMainNavigation('productList.php');  ?>><i class="fas fa-shopping-bag"></i> Products</a>
            <div class="productList" >
                <?php productCategory(); ?>
            </div>
        </li>
        <li>
            <a href="aboutus.php" <?php activeMainNavigation('aboutus.php');?>><i class="fas fa-address-card"></i> About Us</a>
        </li>
        <li>
            <a href="addsupport.php" <?php activeMainNavigation('supports.php');activeMainNavigation('addsupport.php');;activeMainNavigation('viewSupport.php');?>><i class="fas fa-phone"></i> Contact Us</a>
        </li>
        <li class="account">
            <?php loggedIn(); ?>
        </li>
    </ul>
    <!-- Shop bar which consist of icon, searchbar, shopping cart and notification icon -->
    <ul class="shop">
        <li id="shopIcon">
            <img onclick="location.href='index.php'" src="Media/favicon.png"/>
        </li>
        <li id="searchBar">
            <form id="searchForm" action="searchProduct.php" method="get">
                <input type="text" class="searchBar" name="search" placeholder="Search for products" title="Type in products name or category name">
                <i id="searchIcon" class="fas fa-search" onclick="document.getElementById('searchForm').submit();" onmouseover="searchHover(this)" onmouseout="searchunHover(this)"></i>
            </form>
        </li>
        <li id="shoppingCart" onclick='location.href="shoppingcart.php"'>
            <a><i class="fas fa-shopping-cart"></i><span class="notification"><?php echo isset($totalCartAmount)?$totalCartAmount:(isset($_SESSION['shoppingCart'])? count($_SESSION['shoppingCart']):0); ?></span></a>
        </li>
        <li id="notification" onclick='location.href="notification.php"'>
            <a><i class="fas fa-bell"></i><span class="notification">0</span></a>
        </li>
    </ul>
</nav>
<button id="scrollToTop" onclick="scrollFunction()">
    <p><i class="fas fa-arrow-up"></i></p>
</button>