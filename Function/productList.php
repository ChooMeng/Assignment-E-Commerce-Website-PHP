<?php
function discountProduct(){
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productList WHERE discount>0 AND status != 'Disabled'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $i = 0;
            while($row = $result->fetch_object()){
                if ($i%5==0){
                    echo '<tr>';

                }
                printf( '<td width="20%%" height="128px">
                        <a href="productDetails.php?productID=%s">
                            <img style="max-width:240px;" src="uploads/%s" class="categoryImg">
                            <p>%s<br>
                            %s<br>
                                <span id="price">RM%.2f</span><br/>
                                <span id="promotion">RM%.2f</span>
                                <span id="discount">%d%%</span>
                            </p>
                        </a>
                    </td>',$row->Product_ID,$row->Image,$row->Name,$row->Stock==0?"<span style='color:red'>Out of stock</span>":"",$row->Price*(1-($row->Discount/100)),$row->Price,$row->Discount);
                if ($i%5==4){
                    echo '</tr>';
                }
                $i++;
            }
            if ($result->num_rows ==0){
                printf("
                    <tr><td colspan='5'height='60px' class='emptySlot'><b>NO RESULT FOUND</b></td></tr>
                        ");
            }
            $result->free();
        }

    }
    $con->close();
}
function allProducts(){
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productList WHERE status != 'Disabled'";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $i = 0;
            while($row = $result->fetch_object()){
                if ($i%5==0){
                    echo '<tr>';

                }
                printf( '<td width="20%%" height="128px">
                        <a href="productDetails.php?productID=%s">
                            <img style="max-width:240px;" src="uploads/%s" class="categoryImg">
                            <p>%s<br>%s<br><span id="price">RM %.2f</span><br/></p>
                        </a>
                    </td>',$row->Product_ID,$row->Image,$row->Name,$row->Stock==0?"<span style='color:red'>Out of stock</span>":"",$row->Discount>0?$row->Price*(1-($row->Discount/100)):$row->Price);
                if ($i%5==4){
                    echo '</tr>';
                }
                $i++;
            }
            $result->free();
        }

    }
    $con->close();
}
function sideNav(){
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from category";
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            $i = 0;
            while($row = $result->fetch_object()){
               printf('<a href="products.php?type=%s">%s</a>',$row->category_ID,$row->category_name);
            }
            $result->free();
        }

    }
    $con->close();
}