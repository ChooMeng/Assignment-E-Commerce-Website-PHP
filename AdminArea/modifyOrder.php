<!DOCTYPE html>
<?php include 'Function/checkSession.php';?>
<html>
    <head>
        <?php include 'Function/default.php';?>
        <link href="CSS/default.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/default.js" type="text/javascript"></script>
        <link href="CSS/modifyOrder.css" rel="stylesheet" type="text/css"/>
        <script src="JavaScript/modifyOrder.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include 'header.php'; include 'Function/orderModify.php'; ?>
        <section>
            <div id="confirmationMessageInformation" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the edited client profile?</p>
            </div>
            <div id="confirmationMessageProducts" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to change the client password?</p>
            </div>
            <div id="confirmationMessageDefaultAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the default address?</p>
            </div>
            <div id="confirmationMessageModifyAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to save the edited existing address?</p>
            </div>
            <div id="confirmationMessageCreateAddress" class="confirmationMessage" title="Confirmation Message">
                <p>Do you want to create the new address?</p>
            </div>
            <h1>Modify Order Details</h1>
            <!--Navigation Bar for switching order information, products and address section-->
            <div class="modifyMenu">
                <a id="information" class="active" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=information'">Information</a>
                <a id="products" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=products'">Products</a>
                <a id="addressA" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=address'">Address</a>
            </div>
            <?php displayErrorMessage();?>
            <?php displayDatabaseErrorMessage();?>
            <!--Display the notifications-->
            <div class="notificationBox" id="notificationBox"></div>
            <!--Display the confirmation box while trying to remove address-->
            <div id='confirmationBox'> 
                <div class='contents'>
                    <div class='header'>
                        Are you sure you want remove this address?
                    </div>
                    <div class='body'>
                        <div id='button'>
                            <div class='yes' onclick="removeAddress()">Yes</div>
                            <div class='no' onclick='closeBox(),addNotification("yellow","<b>Cancelled</b>! Unsuccesful remove address.")'>No</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order information section:  Display exist order information and allow edit-->
            <div class="information">
                <form id="informationForm" action="modifyOrder.php?orderId=<?php echo $orderID;?>&type=information" method="POST">
                    <div class="rightSide">
                        <label for="clientId">Client ID: <span class="required">*</span></label>
                        <input list="clientList" type="text" name="clientId" id="clientId" maxlength="7" placeholder="xxxxx" value="<?php echo isset($_POST["clientId"])?$_POST["clientId"]:$clientID;?>" oninput="suggestClient()">
                        <dataList id="clientList" name="clientId"></dataList>
                        <?php echo isset($errorClientId)?displayError():"";?>
                        <br/>
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
                    <div class="leftSide">
                        <label for="orderID">Order ID: </label>
                        <input style="cursor:no-drop;" type="text" name="orderID" id="orderId" value="<?php echo $orderID;?>" readonly disabled="true">
                        <br/>
                        <label for="orderNo">Order No: <span class="required">*</span></label>
                        <input type="text" name="orderNo" id="orderNo" placeholder="xxxxxxxxxxxx" maxlength="12" value="<?php echo isset($_POST["orderNo"])?$_POST["orderNo"]:$orderNo;?>">
                        <?php echo isset($errorOrderNo)?displayError():"";?>
                        <div id="randomBox" style="text-align:center;" onclick="randomOrderNo()">Random Generate</div>
                        <br/>
                    </div>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageInformation()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='orders.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyInformation">
                </form>
            </div>
            <!-- Ordered products section: Display ordered product list and allow edit-->
            <div class="products">
                <form id="productsForm" action="modifyOrder.php?orderId=<?php echo $orderID;?>&type=products" method="POST">
                    <table >
                        <tbody class='productTable'>
                            <tr>
                                <th>No.</th>
                                <th>Product ID <?php echo isset($errorProductId)?displayError():"";?></th>
                                <th>Description <?php echo isset($errorDescription)?displayError():"";?></th>
                                <th>Qty</th>
                                <th>Unit Price<br>(RM)</th>
                                <th>Total<br>(RM)</th>
                            </tr>
                            <?php displayProduct(); ?>
                            <tr class='productData'>
                                <td><script>document.write(count);</script></td>
                                <td><input list="productList-default" type="text" name="productId[]" id="productId" size="3" maxlength="5" oninput="createRow(this); suggestProduct(this);" autocomplete="off" ><dataList id="productList-default" name="productList-default"><select>test</select></dataList></td>
                                <td><input type="text" name="description[]" id="description" readonly></td>
                                <td><input style='text-align:center;' type="number" name="quantity[]" id="quantity" value="0" step="1" min="0" max="999" oninput="calculateTotal(this),createRow(this);"></td>
                                <td><input style='text-align:center;' type="number" name="unitPrice[]" id="unitPrice" value="0.00" step="0.05" min="0" max="99999" readonly></td>
                                <td><span id="total">0.00</span></td>
                            </tr> 
                            
                        </tbody>
                    </table>
                    <!-- Calculate the subtotal,total discount and grand total -->
                    <table>
                        <tr>
                            <th class="tableHeader" width="90%" style='text-align:right;'>SubTotal </th>
                            <td width="10%" id='subtotal'><?php echo isset($_POST['subtotal'])?$_POST['subtotal']:number_format($subtotal, 2, '.', ''); ?></td>
                            <input hidden="true" value='<?php echo isset($_POST['subtotal'])?$_POST['subtotal']:number_format($subtotal, 2, '.', ''); ?>' name='subtotal' id='inputSubTotal'>
                        </tr>
                        <tr>
                            <th class="tableHeader" width="90%" style='text-align:right;'>Discount <input type='number' style='width:40px;height:20px;' id='discountNum' name='discount' max='100' min='0' value='<?php echo isset($_POST["discount"])?$_POST["discount"]:$discount;?>' oninput='getDiscount();'>%</th>
                            <td width="10%" id='discount'><?php printf( "%.2f", isset($_POST['totalDiscount'])?$_POST['totalDiscount']:$subtotal*($discount/100)); ?></td>
                            <input hidden="true" value='<?php printf( "%.2f",isset($_POST['totalDiscount'])?$_POST['totalDiscount']:$subtotal*($discount/100)); ?>' name='totalDiscount' id='inputDiscount'>
                        </tr>
                        <tr>
                            <th class="tableHeader" width="90%" style='text-align:right;'>Grand Total </th>
                            <td width="10%" id='grandtotal'><?php printf( "%.2f",isset($_POST['grandtotal'])?$_POST['grandtotal']:$subtotal-($subtotal*($discount/100))); ?></td>
                            <input hidden="true" value='<?php printf( "%.2f",isset($_POST['grandtotal'])?$_POST['grandtotal']:$subtotal-($subtotal*($discount/100))); ?>' name='grandtotal' id='inputGrandTotal'>
                        </tr>
                        <script>getTotal();</script>
                    </table>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageProducts()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='orders.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyProducts">
                </form>
            </div>
            <!--Address section: Display exist address details and allow edit-->
            <div class="addressA"><!-- Select an address and set it as default -->
                <form id="defaultAddressForm" action="modifyOrder.php?orderId=<?php echo $orderID;?>&type=address" method="POST">
                    <div class="createMenu">
                        <button type="button" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=newaddress'">Add new address</button>
                    </div>
                    <table>
                        <tr>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Selected<?php echo isset($errorSelected)?displayError():"";?></th>
                            <th>Action</th>
                        </tr>

                        <?php displayAddress();?>
                    </table>
                    <br>
                    <br>
                    <br>
                    <div class="formCenter"><!-- Button that control the form -->
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageDefaultAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='orders.php'">
                    </div>
                    <input hidden="true" name="type" value="modifyAddress">
                </form>
                
            </div>
            <div class="modifyAddress"> <!-- Modify existing address -->
                
                <form id="modifyAddressForm" action="modifyOrder.php?orderId=<?php echo $orderID;?>&type=address&address=<?php echo isset($_GET['address'])?$_GET['address']:"";?>" method="POST">
                    <label for="address">Address: <span class="required">*</span></label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST["address"])?$_POST["address"]:$address;?></textarea>
                    <?php echo isset($errorAddress)?displayError():"";?>
                    <br/>
                    <label for="zipCode">Zip Code: <span class="required">*</span></label>
                    <input type="text" name="zipCode" placeholder="11111" id="zipCode" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:$zipCode;?>">
                    <?php echo isset($errorZipCode)?displayError():"";?>
                    <br/>
                    <label for="city">City: <span class="required">*</span></label>
                    <input type="text" name="city" id="city" maxlength="28" placeholder="Gelugor" value="<?php echo isset($_POST["city"])?$_POST["city"]:$city;?>">
                    <?php echo isset($errorCity)?displayError():"";?>
                    <br>
                    <label for="state">State: <span class="required">*</span></label>
                    <select name="state" id="state">
                        <?php stateList();?>
                    </select>
                    <?php echo isset($errorState)?displayError():"";?>
                    <br>
                    <br>
                    <br>
                    <!-- Button that control the form -->
                    <div class="formCenter">
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageModifyAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=address'">
                    </div>
                    <input hidden="true" name="type" value="modifyExistAddress">
                </form>
            </div>
            <div class="newAddress"><!-- Create new address -->
                <form id="createAddressForm" method="POST" action="modifyOrder.php?orderId=<?php echo $orderID;?>&type=newaddress">
                    <label for="address">Address: <span class="required">*</span></label>
                    <textarea name="address" id="address" rows="3"><?php echo isset($_POST["address"])?$_POST["address"]:"";?></textarea>
                    <?php echo isset($errorAddress)?displayError():"";?>
                    <br/>
                    <label for="zipCode">Zip Code: <span class="required">*</span></label>
                    <input type="text" name="zipCode" placeholder="11111" id="zipCode" maxlength="5" value="<?php echo isset($_POST["zipCode"])?$_POST["zipCode"]:"";?>">
                    <?php echo isset($errorZipCode)?displayError():"";?>
                    <br/>
                    <label for="city">City: <span class="required">*</span></label>
                    <input type="text" name="city" id="city" maxlength="28" placeholder="Gelugor" value="<?php echo isset($_POST["city"])?$_POST["city"]:"";?>">
                    <?php echo isset($errorCity)?displayError():"";?>
                    <br>
                    <label for="state">State: <span class="required">*</span></label>
                    <select name="state" id="state">
                        <?php stateList();?>
                    </select>
                    <?php echo isset($errorState)?displayError():"";?>
                    <br>
                    <br>
                    <br>
                    <div class="formCenter"><!-- Button that control the form -->
                        <input type="button" value="Save" id="formButton" class="submit" onclick="confirmMessageCreateAddress()">
                        <input type="button" value="Cancel" id="formButton" class="cancel" onclick="location.href='modifyOrder.php?orderId=<?php echo $orderID;?>&type=address'">
                    </div>
                    <input hidden="true" name="type" value="newAddress">
                </form>
            </div>
            <!-- Check the section type -->
            <?php check();?>
        </section>
        <?php include 'footer.php';?>
    </body>
</html>
