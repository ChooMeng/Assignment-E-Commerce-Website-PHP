<?php
//Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['createCategory'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Created new category.");}, 100);</script>';
            $_POST = array();
        }
        if (isset($_POST['editCategory'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Modified existing category.");}, 100);</script>';
            $_POST = array();
        }
    }
    //Display invalid category id notification
    if(isset($_COOKIE['error'])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Invalid categoryID.");}, 100);</script>';
    }
    //Display succesful remove category message
    if(isset($_SESSION["removed"])){
        echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Removed the category.");}, 100);</script>';
        unset($_SESSION["removed"]);
    }
    //Display succesful remove category message
    if(isset($_SESSION["errorConstraint"])){
        echo '<script>setTimeout(function(){addNotification("red","<b>ERROR</b>! Please remove all the product that under this category before delete this category.");}, 100);</script>';
        unset($_SESSION["errorConstraint"]);
    }
    function categoryList(){
         //Read all product data
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from category ORDER BY category_ID DESC";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    printf('
                           <tr class="categoriesData">
                        <td>%s</td>
                        <td>%s</td>
                        <td>
                            <form action="editCategory.php?id=%s" method="POST">
                                <input id="edit" type="submit" value="Edit" name="edit"/>
                            </form>
                            <br/>
                            <button id="delete" onclick="openBox(\'%s\')">Delete</button><br>
                        </td>
                    </tr>
                    ',$row->category_ID,$row->category_name,$row->category_ID,$row->category_ID);

                }
                if ($result->num_rows ==0){
                    printf("
                        <tr><td colspan='6'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                            ");
                }

            }
            $result->free();
            $con->close();
        }
    }