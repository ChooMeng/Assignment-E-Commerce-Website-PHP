<?php 
    include 'settings.php';
    //Check logged in session
    date_default_timezone_set(TIMEZONE);
    function loggedIn(){
        if (isset($_SESSION["clientId"])){
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $loginID = $_SESSION["clientId"];
                $sql = "SELECT userName from client WHERE clientID='$loginID'";
                if($result = $con->query($sql)){
                    if (($result->num_rows)>0){
                        while($row = $result->fetch_object()){
                            $loginUserName = $row->userName;
                            
                        }
                    }else{
                        unset($_SESSION["clientId"]);
                    }

                }else{
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }
                $result->free();
                $con->close();
            }
            if (isset($loginUserName)){
                //Logged in session found
                printf('
                <a><i class="fas fa-user"></i> %s</a>
                <div class="dropdown loggedin" onmouseover="navHover(this)" onmouseout="navUnHover(this)">
                    <a href="profile.php">Profile</a>
                    <a href="viewOrder.php">My Orders</a>
                    <a href="supports.php">My Tickets</a>
                    <a href="wishList.php">My Wishlist</a>
                    <a href="logout.php">Logout</a>
                </div>
                    ',$loginUserName);
            }else{
                displayInvalidAccount();
                
            }
            
        }else{
            displayInvalidAccount();
        }
    }
    //Calculate total cart amount
    if (isset($_SESSION['clientId'])){
        $clientID = $_SESSION['clientId'];
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from shoppingcarttable WHERE clientID='$clientID'";
            if($result = $con->query($sql)){
                $totalCartAmount = 0;
                if (($result->num_rows)>0){

                    while($row = $result->fetch_object()){
                        $totalCartAmount++;
                    }
                }
            }
        }
    }
    /*If there are no logged in session than display invalid account message*/
    function displayInvalidAccount(){
        printf('
           <a><i class="fas fa-user"></i> Account</a>
           <div class="dropdown" onmouseover="navHover(this)" onmouseout="navUnHover(this)">
               <a href="login.php">Login</a>
               <a href="register.php">Register</a>
               <a href="forgotpassword.php">Forgot Password</a>
           </div>
               ');
    }
    $parentCategoryName = array();
    /*Display product category*/
    function productCategory(){
        $parentCategory = array();
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from category LIMIT 0,".MAX_CATEGORY_IN_LIST;
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    array_push($parentCategory,$row->category_ID);
                    $parentCategoryName[$row->category_ID] = $row->category_name;
                }
                $result->free();
            }
                
        }
        $con->close();
        
        foreach($parentCategory as $value){
            $categoryName = $parentCategoryName[$value];
            echo '<div class="category">
                    <a onclick="openCat(this)">'.$categoryName.'<i class="fas fa-hand-point-right" style="float:right;"></i></a>
                    <div class="sub-category">
                        <a href="products.php?type='.$value.'">All '.$categoryName.'</a>';
            @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
            $count = 0;
            if ($con -> connect_errno) { //Check it is the connection succesful
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
            }else{
                $sql = "SELECT * from productList WHERE category='$value' AND status != 'Disabled' LIMIT 0,".MAX_PRODUCT_IN_LIST;
                if(!$result = $con->query($sql)){
                    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
                }else{
                    while($row = $result->fetch_object()){
                        echo '<a href="productDetails.php?productID='.$row->Product_ID.'">'.$row->Name.'</a>';
                        $count++;
                    }
                    $result->free();
                }
                
            }
            $con->close();
            if ($count>MAX_PRODUCT_IN_LIST){
                echo '<a href="products.php?type='.$value.'">More products...</a>';
            }
            
            echo '</div></div>';
        }
        if (count($parentCategory)>MAX_CATEGORY_IN_LIST){
             echo '<a href="productList.php">More products...</a>';
        }
    }
?>