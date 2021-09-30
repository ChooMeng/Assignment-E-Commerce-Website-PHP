<?php
require 'settings.php';
if (!isset($_GET['type'])){
    setcookie("error","type",time()+1,"productList.php");
    header('Location: productList.php');
}
$type = trim($_GET['type']);
@$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
if ($con -> connect_errno) { //Check it is the connection succesful
    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
}else{
    $sql = "SELECT * from category WHERE category_ID='$type'";
    if(!$result = $con->query($sql)){
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
    }else{
        if ($result->num_rows ==0){
            setcookie("error","type",time()+1,"productList.php");
            header('Location: productList.php');
        }
        while($row = $result->fetch_object()){
            $categoryName = $row->category_name;
        }
        $result->free();
    }

}
$con->close();
function discountProduct(){
    global $type;
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productList WHERE Discount>0 AND category = '$type' AND status != 'Disabled'";
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
                                %s
                                <br>
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
    global $type;
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $sql = "SELECT * from productList WHERE category = '$type' AND status != 'Disabled'";
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
