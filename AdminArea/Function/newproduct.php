<?php
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
    function detectNewProductNameError()
    {
        global $newProductName, $category;

        //array hold error msg
        $error = array(); //create empty array

        //check product name
        if($newProductName == null)
        {
            $error['newProductName'] = 'Please enter <b>Product Name</b>.';
        }
        else if(strlen($newProductName)>50)
        {
            $error['newProductName'] = '<b>Product Name</b> is too long. It must be not more than 50.';
        }
        
        if (empty($category)){
            $error['category'] = 'Please select a <b>Category</b>.';
        }else if(!in_array($category, $GLOBALS['categoryList'])){
            $error['category'] = 'Please select a valid <b>Category</b>.';
        }
        return $error;

    }
    function categoryOption(){
        global $categoryList;
        
        foreach ($categoryList as $value){
            $selected = "";
            if(isset($_POST['categoryName'])){
                if($_POST['categoryName'] == $value)
                {
                    $selected = 'selected';
                }
            }
            printf('
                    <option value="%s" %s>%s</option>
                ', $value, $selected, $value);
        }
        
    }
   if(isset($_POST['create']))
        {
        $newProductName = trim($_POST['name']); 
        $category = trim($_POST['categoryName']); 
        
        
        $error = detectNewProductNameError(); // call function for validation
        
        if(empty($error)) //display output
        {
            header('HTTP/1.1 307 Temporary Redirect');
            header("Location: newProductSettings.php");
        }
    }
    function displayErrorMessage(){
        global $error;
        if(!empty($error)){
            printf('<ul class="error"><li>%s</li></ul>
                         ', implode('</li><li>', $error));
        }
    }
?>