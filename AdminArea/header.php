<script src="https://kit.fontawesome.com/3f628a0091.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Sans">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=PT+Sans+Narrow">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans">
<?php date_default_timezone_set(TIMEZONE); ?>
<!-- Top navigation bar -->
<nav class="menu">
    <ul class="nav">
        <li id="sideMode" ><a onclick="openSideNav()">&#9776</a></li>
        <li class="admin" onclick="location.href='dashboard.php'" > AdminArea</li>
        <li class="account">
            <a><i class="fas fa-user"></i> 
                <?php 
                    
                    echo $loginUserName;
                ?>
            </a>
            <div class="dropdown" onmouseover="navHover(this)" onmouseout="navUnHover(this)">
                <a href="profile.php">Profile</a>
                <a href="profile.php?type=password">Change Password</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
    <ul class="sideBar">
        <li>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li>
            <a href="client.php"><i class="fas fa-users"></i> Clients</a>
        </li>
        <li>
            <a href="products.php"><i class="fas fa-shopping-bag"></i> Products</a>
        </li>
        <li>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        </li>
        <li>
            <a href="staffs.php"><i class="fas fa-users-cog"></i> Staffs</a>
        </li>
        <li>
            <a href="supports.php"><i class="fas fa-ticket-alt"></i> Support</a>
        </li>
        <li>
            <a href="auditlog.php"><i class="fas fa-history"></i> Audit Log</a>
        </li>
        <li class="restore">
            <a><i class="fas fa-undo"></i> Restore</a>
            <div class="dropdownRestore" onmouseover="navHover(this)" onmouseout="navUnHover(this)">
                <a href="productRestore.php">Products</a>
                <a href="orderRestore.php">Orders</a>
                <a href="supportRestore.php">Supports</a>
            </div>
        </li>
    </ul>
</nav>
<button id="scrollToTop" onclick="scrollFunction()">
    <p><i class="fas fa-arrow-up"></i></p>
</button>