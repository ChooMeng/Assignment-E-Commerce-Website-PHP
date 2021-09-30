<?php
    include 'settings.php';
    $clientID = $_SESSION["clientId"];
    if (isset($_SESSION['removed'])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Remove the product from wishlist.");}, 100);</script>';
        unset($_SESSION['removed']);
    }
    function displayWishlist(){
        global $clientID;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * FROM client c, wishlist w, productlist p
                    WHERE c.clientID = '$clientID' AND w.clientID = c.clientID AND p.Product_ID = w.Product_ID";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                        <tr class="item">
                            <td>
                                <img src="uploads/%s" class="productImg">
                            </td>
                            <td valign="top">
                                <p class="itemName">%s</p>
                                <button type="button" class="trash" onclick="removeWishlist(%s)">
                                    <span >
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </button>
                            </td>
                            <td valign="top">
                                <p>RM %.2f</p>
                            </td>
                            <td>
                                <button type="button" class="addToCart" onclick="addToCart(%s)">
                                    <span >
                                        <i class="fas fa-plus"></i><i class="fas fa-shopping-cart"></i>
                                    </span>
                                </button>
                            </td>
                        </tr>
                    ', $row->Image, $row->Name, "'".$row->Product_ID."'", $row->Price, "'".$row->Product_ID."'");
                }
                echo "<tr>";
                printf('<td colspan="4" id="wishlistStatement"><hr>');
                if(!$result->num_rows == 0){
                    printf('<div>This is the end of the wishlist rows.</div>');
                }elseif ($result->num_rows == 0){
                    printf('<div>Hmm... seems like you hanen\'t add any items into your wishlist.</div>');
                }
                echo "</td></tr>";
            }
        }
    }