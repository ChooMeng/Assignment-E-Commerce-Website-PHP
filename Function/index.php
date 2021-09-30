<?php
    //Get notification type and display notification
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['submitCreditCard'])||isset($_POST['submitPaypal'])||isset($_POST['submitTouchNGo'])){
            echo '<script>setTimeout(function(){addNotification("green","<b>SUCCESFUL</b>! Created new order.");}, 100);</script>';
        }
    }
    function categoryItem(){
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
                    $parentCategoryImage[$row->category_ID] = $row->category_icon;
                }
                $result->free();
            }
                
        }
        $con->close();
        $i = 0;
        foreach($parentCategory as $value){
            if ($i%3==0){
                echo "<tr>";
            }
            printf('<td width="33%%">
                        <a href="products.php?type=%s">
                            <img src="uploads/%s" class="categoryImg">
                            <p>%s</p>
                        </a>
                    </td>',$value,$parentCategoryImage[$value],$parentCategoryName[$value]);
            if ($i%3==2){
                echo "</tr>";
            }
            $i++;
        }
    }

