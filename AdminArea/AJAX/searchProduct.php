<?php
    session_start();
    if (!isset($_SESSION["staffId"])||!isset($_GET["search"])||!isset($_GET["page"])){ //Check it is the login session and it is correct way to connect if not send back to login page
         header('location: ../login.php');
    }
    include '../../settings.php';
    echo '<table id="products">';
    //Get page number and calculate index
    if (isset($_GET['page'])){
        $index = $_GET['page'];
        if ($_GET['total']>0){
            if ($index > ceil($_GET['total']/20.00)){
                $index = ceil($_GET['total']/20.00);
            }
        }
    }else{
        $index = 1;
    }
    //Product status array
    $statusArr = array("Enabled","OutOfStock","Disabled");
    //Product data type Array
    $dataArr = array("Product_ID"=>"Product ID","Name"=>"Name","Status"=>"Status","Category"=>"Category","Stock"=>"Stock");
    $dataWidthArr = array("Product_ID"=>"5%","Name"=>"45%","Status"=>"15%","Category"=>"20%","Stock"=>"10%");
    $status = "%";
    if (isset($_GET['status'])){
        $status = $_GET['status'];
        if (!in_array($status, $statusArr)){
            $status ="%";
        }
    }
    //Sorting the table column
    $sort = "NA";
    $sortOrder = "ASC";
    if (isset($_GET['sort'])){
        $sort=$_GET['sort'];
        if (!array_key_exists($sort, $dataArr)){
            $sort="NA";
        }
        $sortOrder = "ASC";
        if (isset($_GET['sortOrder'])){
            $sortOrder = $_GET['sortOrder'];
            if ($sortOrder != "DESC"&&$sortOrder!="ASC"){
                $sortOrder = "ASC";
            }
        }
    }
    echo "<tr>";
    if(isset($_GET['search'])){
        $search = $_GET['search'];
    }
    if (empty($search)){
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="location.href=\'?page=%s&status=%s&sort=%s&sortOrder=%s\'">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$index,$status,$key,isset($sortOrder)?($sortOrder=="ASC"?($sort==$key?"DESC":"ASC"):"ASC"):"ASC",$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
        }
    }else{
        $dataCount = 0;
        foreach($dataArr as $key => $value){
            printf('
                <th width="%s" onclick="sort(%d)">%s<span id="sort"><i class="%s"></i></span></th>
            ',$dataWidthArr[$key],$dataCount,$value, $sort=="$key"?($sortOrder=="ASC"?"fas fa-sort-up":"fas fa-sort-down"):"fas fa-sort");
            $dataCount++;
        }
    }
    echo '<th width="5%" style="cursor:default;">Action</th></tr>';
    //Read all product data
    @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database
    if ($con -> connect_errno) { //Check it is the connection succesful
        echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
    }else{
        $statement = $sort=="NA"?"ORDER BY Product_ID DESC":"ORDER BY $sort $sortOrder";
        $statusStatement = $status == "NA"?"WHERE status != 'Disabled'":"WHERE status LIKE '%$status%' AND status != 'Disabled'";
        $min_result = ($index-1)*LIST_PER_PAGE;
        if (empty($search)){
            $sql = "SELECT * from productlist p, category c $statusStatement AND p.Category = c.Category_ID $statement LIMIT $min_result,".LIST_PER_PAGE;
        }else{
            $sql = "SELECT * from productlist p, category c WHERE p.Category = c.Category_ID AND status LIKE '$status' AND status != 'Disabled' AND (Product_ID LIKE '%$search%' OR Name LIKE '%$search%' OR Status LIKE '%$search%' OR Category LIKE '%$search%' OR Stock LIKE '%$search%') $statement";
        }
        if(!$result = $con->query($sql)){
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
        }else{
            while($row = $result->fetch_object()){
                printf('
                       <tr class="productData">
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     <td>%s</td>
                     <td>
                         <form action="viewProduct.php?id=%s" method="POST">
                            <input id="view" type="submit" value="View" name="view" />
                        </form>
                         <br/>
                         <form action="editProduct.php?id=%s" method="POST">
                             <input id="edit" type="submit" value="Edit" name="edit" />
                         </form>
                        <br/>
                        <form action="" method="POST">
                            <input id="delete" type="submit" value="Delete" name="delete" />
                        </form>
                    </td>
                 </tr>
                ',$row->Product_ID,$row->Name,$row->Status,$row->category_name,$row->Stock,$row->Product_ID,$row->Product_ID);

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
    echo '</table>';
    if (empty($search)){
        $totalAmount = $enableAmount = $outOfStockAmount = $removedAmount = 0;
        @$con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME); //Connect to database @-mean disable error message for create a custom error message
        if ($con -> connect_errno) { //Check it is the connection succesful
            echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->connect_error."</div>";
        }else{
            $sql = "SELECT status, count(*) as total from productlist GROUP BY status";
            if(!$result = $con->query($sql)){
                echo "<div class='error'><b>DATABASE ERROR</b>. Please contact Administrator<br/>Error Message: ".$con->error."</div>";
            }else{
                while($row = $result->fetch_object()){
                    switch($row->status){
                       case 'Enabled':
                            $enableAmount = $row->total;
                            break;
                        case 'OutOfStock':
                            $outOfStockAmount = $row->total;
                            break;
                        case 'Disabled':
                            $removedAmount= $row->total;
                            break;
                    }
                    $totalAmount+=$row->total;
                }
                $totalAmount = $totalAmount - $removedAmount;
            }
            $result->free();
            $con->close();
        }
        echo'<!-- Page number -->
                <div id="page">
                     <div id="pageNumber">
                        ';
        if (isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        
        $totalPage = ceil($_GET['total']/20);
        if (isset($_GET['status'])){
            switch($_GET['status']){
                case 'Enabled':
                    $totalPage = ceil($enableAmount/20);
                    break;
                case 'OutOfStock':
                    $totalPage = ceil($outOfStockAmount/20);
                    break;
            }
        }
        if ($totalPage<$currentPage){
            $currentPage = $totalPage;
        }
        if ($currentPage != 1&&$currentPage != 0){
            $top = 1;
            echo "<a href='?page=$top' class='backward'><i class='fas fa-backward'></i></a>";
        }
        for ($i = 1; $i <= $totalPage;$i++){
            if ($i>=$currentPage-2&&$i<=$currentPage+2){
                if ($i == $currentPage){
                    echo "<a class='current'>$i</a>";
                }else{
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
        }
        if ($currentPage != $totalPage){
            $next = $totalPage;
            echo "<a href='?page=$next' class='forward'><i class='fas fa-forward'></i></a>";
        }
    }

