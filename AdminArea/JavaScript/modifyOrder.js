var count = 1;
var subtotal = 0;
var discountTotal = 0;
/*Address remove confirmation box*/
function openBox(address,client){
    document.getElementById("confirmationBox").style.display="block";
    addressId = address;
    clientId = client;
}
function closeBox(){
    document.getElementById("confirmationBox").style.display="none";
}
var addressId = "";
var clientId = "";
/*Remove the address*/
function removeAddress(){
    if(document.getElementById("b"+addressId).checked) {
        addNotification("red","<b>ERROR</b>! Cannot remove default address.");
        
    }else{
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            location.reload();
          }
        };
        xmlhttp.open("GET", "AJAX/removeAddress.php?addressID=" + addressId+"&clientID="+clientId, true);
        xmlhttp.send();
    }
    closeBox();
}
/*Change the section type*/
function switchType(type){
    var information = document.getElementsByClassName("information")[0];
    var informationtab = document.getElementById("information");
    var products = document.getElementsByClassName("products")[0];
    var productstab = document.getElementById("products");
    var address = document.getElementsByClassName("addressA")[0];
    var addresstab = document.getElementById("addressA");
    var modifyAddress = document.getElementsByClassName("modifyAddress")[0];
    var newAddress = document.getElementsByClassName("newAddress")[0];    
    if (type=="information"){
        information.style.display="block";
        products.style.display="none";
        informationtab.className="active";
        productstab.classList.remove("active");
        address.style.display="none";
        addresstab.classList.remove("active");
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="products"){
        information.style.display="none";
        products.style.display="block";
        informationtab.classList.remove("active");
        productstab.className="active";
        address.style.display="none";
        addresstab.classList.remove("active");
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="address"){
        information.style.display="none";
        products.style.display="none";
        informationtab.classList.remove("active");
        productstab.classList.remove("active");
        address.style.display="block";
        addresstab.className="active";
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="existaddress"){
        information.style.display="none";
        products.style.display="none";
        informationtab.classList.remove("active");
        productstab.classList.remove("active");
        address.style.display="none";
        addresstab.className="active";
        modifyAddress.style.display="block";
        newAddress.style.display="none";
    }else{
        information.style.display="none";
        products.style.display="none";
        informationtab.classList.remove("active");
        productstab.classList.remove("active");
        address.style.display="none";
        addresstab.className="active";
        modifyAddress.style.display="none";
        newAddress.style.display="block";
    }
}
//Create new row for product order list
function createRow(element){
    var tr = element.parentNode.parentNode;
    var table = document.getElementsByClassName("productTable")[0];
    var isLastChild = (tr === table.children[table.childElementCount-1]);
    if (isLastChild){
        count = count+1;
        var row = document.createElement("TR");
        row.className="productData";
        row.innerHTML = '<td>'+count+'</td><td><input list="productList'+count+'" type="text" name="productId[]" id="productId" size="3" maxlength="6" oninput="createRow(this); suggestProduct(this);" autocomplete="off" ><dataList id="productList'+count+'" name="productList'+count+'"></dataList></td><td><input style="width:80%;" type="text" name="description[]" id="description" readonly></td><td><input style="width:80%; text-align:center;" type="number" name="quantity[]" id="quantity" value="0" step="1" min="0" max="999" oninput="calculateTotal(this),createRow(this);"></td><td><input style="width:80%; text-align:center;" type="number" name="unitPrice[]" readonly id="unitPrice" value="0.00" step="0.05" min="0" max="99999"></td><td><span id="total">0.00</span></td>';
        tr.parentNode.appendChild(row);
    }
}
//Calculate product total price
function calculateTotal(element){
    var quantity = element.parentNode.parentNode.getElementsByTagName("td")[3].children[0].value;
    var unitPrice = element.parentNode.parentNode.getElementsByTagName("td")[4].children[0].value;
    element.parentNode.parentNode.getElementsByTagName("td")[5].innerHTML = (quantity*unitPrice).toFixed(2);
    getTotal();
    
}
//Calculate and return subtotal
function getTotal(){
    subtotal = 0;
    var product = document.getElementsByClassName("productData");
    for (var i = 0;i < product.length-1;i++){
        var target = product[i].getElementsByTagName("td")[5].innerHTML;
        subtotal += parseFloat(target);
    }
    document.getElementById("subtotal").innerHTML = subtotal.toFixed(2);
    document.getElementById("inputSubTotal").value = subtotal.toFixed(2);
    getDiscount();
}
//Calculate and return discount
function getDiscount(){
    var discount = document.getElementById("discountNum").value;
    discountTotal = (subtotal*(discount/100)).toFixed(2);
    document.getElementById("discount").innerHTML = discountTotal;
    document.getElementById("inputDiscount").value = discountTotal;
    getGrandTotal();
}
//Calculate and return grand total
function getGrandTotal(){
    document.getElementById("grandtotal").innerHTML = (subtotal-discountTotal).toFixed(2);
    document.getElementById("inputGrandTotal").value = (subtotal-discountTotal).toFixed(2);
}
function addNotification(type,message){ //Create the notification
    var notice = document.createElement("div");
    notice.className = type;
    notice.id="notification";
    notice.innerHTML = message;
    document.getElementById("notificationBox").appendChild(notice);
    setTimeout(function(){removeNotification();}, 2400);
}
function removeNotification(){ //Remove the notification after 10 second
    var notice = document.getElementById("notification");
    notice.style.opacity = "0";
    setTimeout(function(){ notice.remove(); }, 600);
    
}
function randomOrderNo(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("orderNo").value = this.responseText;
        }
    };
    xmlhttp.open("GET", "AJAX/randomNumber.php", true);
    xmlhttp.send();
}
function suggestClient(){
    var xmlhttp = new XMLHttpRequest();
    var search = document.getElementById("clientId").value;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("clientList").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "AJAX/suggestClient.php?search="+search, true);
    xmlhttp.send();
}
function suggestProduct(target){
    var xmlhttp = new XMLHttpRequest();
    var search = target.value;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            target.nextElementSibling.innerHTML = this.responseText;
            displayDescription(target);
            displayUnitPrice(target);
        }
    };
    xmlhttp.open("GET", "AJAX/suggestProduct.php?search="+search, true);
    xmlhttp.send();
    
    
    
}
function displayDescription(target){
    var xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          target.parentNode.parentNode.getElementsByTagName("input")[1].value = this.responseText;
        }
    };
    xmlhttp2.open("GET", "AJAX/displayDescription.php", true);
    xmlhttp2.send();
}
function displayUnitPrice(target){
    var xmlhttp3 = new XMLHttpRequest();
    xmlhttp3.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          target.parentNode.parentNode.getElementsByTagName("input")[3].value = this.responseText;
          calculateTotal(target);
        }
    };
    xmlhttp3.open("GET", "AJAX/displayUnitPrice.php", true);
    xmlhttp3.send();
}
//Open confirmation message dialog
function confirmMessageInformation(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageInformation").dialog('isOpen')){
            $("#confirmationMessageInformation").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageInformation").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable:false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#informationForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Open confirmation message dialog
function confirmMessageProducts(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageProducts").dialog('isOpen')){
            $("#confirmationMessageProducts").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageProducts").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#productsForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Open confirmation message dialog
function confirmMessageDefaultAddress(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageDefaultAddress").dialog('isOpen')){
            $("#confirmationMessageDefaultAddress").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageDefaultAddress").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#defaultAddressForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Open confirmation message dialog
function confirmMessageModifyAddress(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageModifyAddress").dialog('isOpen')){
            $("#confirmationMessageModifyAddress").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageModifyAddress").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#modifyAddressForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Open confirmation message dialog
function confirmMessageCreateAddress(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageCreateAddress").dialog('isOpen')){
            $("#confirmationMessageCreateAddress").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageCreateAddress").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#createAddressForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}