<?php
require 'Function/helper.php';
//Prevent hacker to exploit the system
function antiExploit($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
if(isset($_POST['createCategory'])){
   //Check category name
    if (!isset($_POST['categoryName'])){
        $error['categoryName'] = 'Please enter <b>Category Name</b>.';
    }else{
        $categoryName = antiExploit($_POST['categoryName']);
        if(strlen($categoryName)>30)
        {
            $error['categoryName'] = '<b>Category Name</b> is too long. It must be not more than 30.';
        }
        else if(!preg_match('/^[A-Za-z0-9\s]+$/',$categoryName))
        {
            $error['categoryName'] = 'There are invalid characters in <b>Category Name</b>. You only can enter <b>alphabets</b> or <b>numbers</b>.';
        }
    }
    //Check category icon
    if(isset($_FILES['categoryIcon'])){
                 
        $file = $_FILES['categoryIcon']; //obj

        if($file['error']>0)
        {
            //check to determine the error code
            switch ($file['error']){
                case UPLOAD_ERR_NO_FILE:
                    $error['icon'] = "No file was selected!";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error['icon'] = "No file was selected!";
                    break;
                default:
                    $error['icon'] = "There was an error while uploading the file!";
                    break;
            }
        }else if($file['size']>31457280)
        {

            $error['icon'] = "File uploaded is too large. Maximum 3MB allowed.";
        }else{
            $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

            //check the file extension
            if($ext != 'jpg' && $ext != 'png' && $ext != 'gif' && $ext != 'jpeg'){
                $error1 = "Only jpg, png and gif file format allow.";
            }else{
                if(empty($error)){
                    $save_as = uniqid("",true).'.'.$ext;

                    //save the file (move file from original location to server folder)
                    move_uploaded_file($file['tmp_name'], '../uploads/'.$save_as);
                }

            }
        }
    }
    if(empty($error)) //display output
    {
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT * from category ORDER BY category_ID DESC LIMIT 1";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    $categoryID = $row->category_ID;
                }
            }
            $categoryCount = substr($categoryID, 2,4);
            $categoryCount = intval($categoryCount)+1;
            $categoryID = "CG".str_repeat("0", 4-strlen($categoryCount)).$categoryCount; 
            $sql = "INSERT INTO category(category_id,category_name, category_icon) VALUES (?,?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sss', $categoryID, $categoryName, $save_as);
            if($stmt->execute()){
                //UPDATE successful
                createAuditLog("Product","Create new category","Category ID with categoryID $categoryID created.");
                $stmt->free_result();
                $con->close();
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: categories.php');
            }else{
                //UPDATE failed
                echo '
                    <div class="error">
                    Error, unable to update record. Please try again.'.$con->error.'</div>';
            }
            
        }
    }
}
function displayErrorMessage(){
    global $error;
    if(!empty($error)){
        printf('<ul class="error"><li>%s</li></ul>
                     ', implode('</li><li>', $error));
    }
}
