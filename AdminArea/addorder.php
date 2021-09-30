<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/addorder.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/addorder.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; ?>
        <section>
            <div id="confirmationMessageCreate" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to create the order?</p>
            </div>
            <!--Display the notifications-->
            <div class="notificationBox" id="notificationBox"></div>
            <h1>Create new order</h1>
            <?php include 'Function/orderCreate.php';?>
            <!--Form of create new order-->
            <form id="createForm" action="addorder.php" method="POST">
                <!-- Right column of the form -->
                <div class="rightSide">
                    <label for="status">Status: <span class="required">*</span></label>
                    <select name="status" id="status">
                        <?php statusList();?>
                    </select>
                    <?php echo isset($errorStatus)?displayError():"";?>
                    <br/>
                    <label for="payment">Payment Method: <span class="required">*</span></label>
                    <select name="payment" id="payment">
                    <?php paymentList();?>
                    </select>
                    <?php echo isset($errorPayment)?displayError():"";?>
                </div>
                <!-- Left column of the form -->
                <div class="leftSide">
                    <label for="orderNo">Order No: <span class="required">*</span></label>
                    <input type="text" name="orderNo" id="orderNo" maxlength="12" placeholder="xxxxxxxxxxxx" value="<?php echo isset($_POST["orderNo"])?$_POST["orderNo"]:'';?>">
                    <?php echo isset($errorOrderNo)?displayError():"";?>
                    <div id="randomBox" style="text-align:center;" onclick="randomOrderNo()">Random Generate</div>
                    <label for="clientId">Client ID: <span class="required">*</span></label>
                    <input list="clientList" type="text" name="clientId" id="clientId" maxlength="7" placeholder="Cxxxxxx" autocomplete="off" value="<?php echo isset($_POST["clientId"])?$_POST["clientId"]:'';?>" oninput="suggestClient(),displayAddress()">
                    <dataList id="clientList" name="clientId"></dataList>
                    <?php echo isset($errorClientId)?displayError():"";?>
                    <br/>
                </div>
                <hr>
                <!-- Create new address or choose existing address-->
                <h2 style='text-align: center'>Client Address's</h2>
                
                <div class="addressMenu">
                    <a id="newAddress" class="active" onclick="switchAddress('new');">New Address</a>
                    <a id="existAddress" onclick="switchAddress('exist');">Existing Address</a>
                </div>
                <div class="newAddress">
                    <br/>
                    <label for="address">Address: <span class="required">*</span></label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST["address"])?$_POST["address"]:'';?></textarea>
                    <?php echo isset($errorAddress)?displayError():"";?>
                    <br/>
                    <label for="zipCode">Zip Code: <span class="required">*</span></label>
                    <input type="text" name="zipCode" placeholder="11111" id="zipCode" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:'';?>">
                    <?php echo isset($errorZipCode)?displayError():"";?>
                    <br/>
                    <label for="city">City: <span class="required">*</span></label>
                    <input type="text" name="city" id="city" maxlength="28" placeholder="Gelugor" value="<?php echo isset($_POST["city"])?$_POST["city"]:'';?>">
                    <?php echo isset($errorCity)?displayError():"";?>
                    <br>
                    <label for="state">State: <span class="required">*</span></label>
                    <select name="state" id="state">
                        <?php stateList();?>
                    </select>
                    <?php echo isset($errorState)?displayError():"";?>
                </div>
                <div class="existAddress">
                    <table>
                        <tr>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Selected</th>
                        </tr>
                        <?php displayAddress();?>
                    </table>
                </div>
                <input hidden="true" name="addressType" id="addressType" value="new">
                <script> //Return to the addAddress section or existAddress section

                    <?php echo isset($_POST["addressType"])?$_POST["addressType"]=="new"?"switchAddress('new');":"switchAddress('exist');":"switchAddress('new');";?>
                </script>
                <hr/>
                <!-- Input products details that customer ordered -->
                <h2 style='text-align: center'>Products</h2>
                <table>
                    <tbody class='productTable'>
                        <tr>
                            <th>No.</th>
                            <th>Product ID <?php echo isset($errorProductId)?displayError():"";?></th>
                            <th>Description</th>
                            <th>Qty <?php echo isset($errorQuantity)?displayError():"";?></th>
                            <th>Unit Price<br>(RM)</th>
                            <th>Total<br>(RM)</th>
                        </tr>
                        <?php displayProduct(); ?>
                        <tr class='productData'>
                            <td><script>document.write(count);</script></td>
                            <td><input list="productList-old" type="text" name="productId[]" id="productId" size="3" maxlength="5" oninput="createRow(this), suggestProduct(this);" autocomplete="off" ><dataList id="productList-old" name="productList-old"></dataList></td>
                            <td><input type="text" name="description[]" id="description" readonly></td>
                            <td><input style='text-align:center;' type="number" name="quantity[]" id="quantity" value="0" step="1" min="0" max="999" oninput="calculateTotal(this),createRow(this);"></td>
                            <td><input style='text-align:center;' type="number" name="unitPrice[]" id="unitPrice" value="0.00" step="0.05" min="0" max="99999" readonly></td>
                            <td><span id="total">0.00</span></td>
                        </tr> 
                        
                    </tbody>
                </table>
                <!-- Calculate the subtotal,total discount and grand total -->
                <table>
                    
                    <tr >
                        <th class="tableHeader" width="90%" style='text-align:right;'>SubTotal</th>
                        <td width="10%" id='subtotal'><?php echo isset($_POST['subtotal'])?$_POST['subtotal']:"0.00"; ?></td>
                        <input hidden="true" value='<?php echo isset($_POST['subtotal'])?$_POST['subtotal']:"0.00"; ?>' name='subtotal' id='inputSubTotal'>
                    </tr>
                    <tr >
                        <th class="tableHeader" width="90%" style='text-align:right;'>Discount <input type='number' style='width:40px;height:20px;' id='discountNum' name='discount' max='100' min='0' value='<?php echo isset($_POST["discount"])?$_POST["discount"]:0;?>' oninput='getDiscount();'>%</th>
                        <td width="10%" id='discount'><?php echo isset($_POST['totalDiscount'])?$_POST['totalDiscount']:"0.00"; ?></td>
                        <input hidden="true" value='<?php echo isset($_POST['totalDiscount'])?$_POST['totalDiscount']:"0.00"; ?>' name='totalDiscount' id='inputDiscount'>
                    </tr>
                    <tr >
                        <th class="tableHeader" width="90%" style='text-align:right;'>Grand Total</th>
                        <td width="10%" id='grandtotal'><?php echo isset($_POST['grandtotal'])?$_POST['grandtotal']:"0.00"; ?></td>
                        <input hidden="true" value='<?php echo isset($_POST['grandtotal'])?$_POST['grandtotal']:"0.00"; ?>' name='grandtotal' id='inputGrandTotal'>
                    </tr>
                    
                </table>
                <script>getTotal();</script>
                <br/><hr/>
                <br/>
                <br/>
                <!-- Button that control the form -->
                <div class="formCenter">
                    <input type="button" value="Submit" id="formButton" class="submit" onclick="confirmMessageCreate()">
                    <input type="reset" value="Reset" id="formButton" class="reset" onclick="location='<?php echo $_SERVER['PHP_SELF']?>'">
                    <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='orders.php'">
                </div>
                <br/>
                <input hidden="true" name="type" value="order">
            </form>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
