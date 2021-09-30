<?php
require 'Function/helper.php';
//Verify productID
if (!isset($_GET['id'])){
    setcookie("error","productId",time()+1,"products.php");
    header('Location: products.php');
}
//Prevent hacker to exploit the system
function antiExploit($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
$productID = $_GET['id'];
$categoryList = array();
@$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
if ($con -> connect_errno) { //Check it is the connection succesful
    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
}else{
    $sql = "SELECT category_name FROM category";
    if($result = $con->query($sql)){
        while($row = $result->fetch_object()){
            array_push($categoryList, $row->category_name);

        }
        $result->free();
    }
}
$con->close();
function categoryOption(){
    global $categoryList,$categoryName;

    foreach ($categoryList as $value){
        $selected = "";
        if(isset($_POST['categoryName'])){
            if(antiExploit($_POST['categoryName']) == $value)
            {
                $selected = 'selected';
            }
        }else{
            if (antiExploit($categoryName)==$value){
                $selected = 'selected';
            }
        }
        printf('
                <option value="%s" %s>%s</option>
            ', $value, $selected, $value);
    }

}
@$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
if ($con -> connect_errno) { //Check it is the connection succesful
    echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
}else{
    $sql = "SELECT * FROM productlist p, category c WHERE p.Product_ID = '$productID' AND p.Category = c.category_ID AND p.Status != 'Deleted'";
    if($result = $con->query($sql)){
        while($row = $result->fetch_object()){
            $categoryName = $row->category_name;
            $editName = $row->Name;
            $save_as = $row->Image;
            $status = $row->Status;
            $stock = $row->Stock;
            $discount = $row->Discount;
            $price = $row->Price;
            $productDescribe = $row->Product_Desc;

        }
        if ($result->num_rows ==0){
            setcookie("error","productId",time()+1,"products.php");
            header('Location: products.php');
        }
        $result->free();
    }
}
$con->close();
if (isset($_POST['editProduct'])) {
    if (empty($_POST['categoryName'])){ //Check product category
        $error['categoryName'] = 'Please select a <b>Category</b>.';
    }else{
        $categoryName = antiExploit($_POST['categoryName']);
        if (!in_array($categoryName, $categoryList)){
            $error['categoryName'] = 'Please select a valid <b>Category</b>.';
        }
    }
    if (empty($_POST['editName'])){ //Check product name
        $error['name'] = 'Please enter <b>New Name</b>.';
    }else{
        $name = antiExploit($_POST['editName']);
        if (strlen($name)>50){
            $error['name'] = '<b>Name</b> is too long. It must be not more than 50.';
        }
    }
    //check status
    $statusArr = array("Enabled","OutOfStock");
    if (empty($_POST['status'])){
        $error['status'] = 'Please select a <b>Status of Product</b>.';
    }else{
        $status = antiExploit($_POST['status']);
        if(!in_array($status, $statusArr)){
            $error['status'] = 'Please select a valid <b>Status of Product</b>.';
        }
    }
    
    if (empty($_POST['stock'])&&$_POST['stock']!=0){
        $error['stock'] = 'Please enter <b>Stock Amount</b>.';
    }else{
        $stock = antiExploit($_POST['stock']);
        if (empty($stock)){
            $stock = 0;
        }
        if(strlen($stock)>5)
        {
            $error['stock'] = '<b>Stock Amount</b> is too many.';
        }else{
            if(!preg_match('/^[0-9]+$/', $stock)){
                $error['stock'] = 'There are invalid characters in <b>Stock</b>. You only can enter <b>numbers</b>.';
            }
        }
    }
    if (empty($_POST['price'])){
        $error['price'] = 'Please enter <b>Price</b>.';
    }else{
        $price = antiExploit($_POST['price']);
        if(strlen($price)>5)
        {
            $error['price'] = '<b>Price</b> is too many.';
        }
    }
    if (empty($_POST['productDescribe'])){
        $error['productDescribe'] = 'Please <b>Describe</b> the <b>Product Details</b>.';
    }else{
        $productDescribe = antiExploit($_POST['productDescribe']);
        
    }
    if (empty($_POST['discount'])&&$_POST['discount']!=0){
        $error['discount'] = 'Please enter <b>Discount</b> amount. Enter 0 for no discount.'.$_POST['discount'];
    }else{
        $discount = antiExploit($_POST['discount']);
        if (empty($discount)){
            $discount = 0;
        }
        if ($discount<0||$discount>100){
            $error['discount'] = '<b>Discount</b> percentage only between 0 to 100.';
        }
        
    }
    //check image name
    if(isset($_FILES['image']))
    {
        $file = $_FILES['image']; //obj

        if($file['error']>0)
        {
            //check to determine the error code
            switch ($file['error']){
                case UPLOAD_ERR_NO_FILE:
                    $noFile = true;
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error['image'] = "No file was selected!";
                    break;
                default:
                    $error['image'] = "There was an error while uploading the file!";
                    break;
            }
        }else if($file['size']>31457280)
        {

            $error['image'] = "File uploaded is too large. Maximum 3MB allowed.";
        }else{
            $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

            //check the file extension
            if($ext != 'jpg' && $ext != 'png' && $ext != 'gif' && $ext != 'jpeg'){
                $error['image'] = "Only jpg, png and gif file format allow.";
            }else{
                if(empty($error)){
                    $save_as = uniqid("",true).'.'.$ext;

                    //save the file (move file from original location to server folder)
                    move_uploaded_file($file['tmp_name'], '../uploads/'.$save_as);

                }

            }
        }
    }
    if (empty($error)) { //display output
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT category_ID FROM category c WHERE category_name = '$categoryName'";
            if($result = $con->query($sql))
            {
                while($row = $result->fetch_object())
                {
                    $categoryID = $row->category_ID;
                }
                $result->free();
            }else{
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }
            if ($noFile){
                 $sql = "UPDATE productlist SET Product_ID=?, Category=?, Name=?, Status=?, Stock=?, Discount=?, Price=?, Product_Desc=?
                   WHERE Product_ID=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param('sssssssss', $productID, $categoryID, $name, $status, $stock, $discount, $price, $productDescribe, $productID);
            }else{
                 $sql = "UPDATE productlist SET Product_ID=?, Category=?, Name=?, Image=?, Status=?, Stock=?, Discount=?, Price=?, Product_Desc=?
                   WHERE Product_ID=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param('ssssssssss', $productID, $categoryID, $name, $save_as, $status, $stock, $discount, $price, $productDescribe, $productID);
            }
           
            if($stmt->execute()){
                //UPDATE successful
                createAuditLog("Product","Product details edited","Product details with productID $productID edited.");
                $stmt->free_result();
                $con->close();
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: products.php');
            }else{
                //UPDATE failed
                echo '
                    <div class="error">
                    Error, unable to update record. Please try again. Error:'.$con->error.'</div>';
            }
            
        }
    }
}
    function displayErrorMessage(){
        global $error;
        if(!empty($error)){
            echo "<td colspan='2'>";
            printf('<ul class="error"><li>%s</li></ul>
                         ', implode('</li><li>', $error));
            echo "</td>";
        }
    }

