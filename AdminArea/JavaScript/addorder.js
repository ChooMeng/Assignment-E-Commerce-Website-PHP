var count = 1;
var subtotal = 0;
var discountTotal = 0;
//Switch to new address section or existing address section
function switchAddress(type){
    var newAddress = document.getElementsByClassName("newAddress")[0];
    var newAddresstab = document.getElementById("newAddress");
    var existAddress = document.getElementsByClassName("existAddress")[0];
    var existAddresstab = document.getElementById("existAddress");
    if (type=="new"){
        newAddress.style.display="block";
        existAddress.style.display="none";
        newAddresstab.className="active";
        existAddresstab.classList.remove("active");
        document.getElementById("addressType").value = "new";
    }else{
        newAddress.style.display="none";
        existAddress.style.display="block";
        newAddresstab.classList.remove("active");
        existAddresstab.className="active";
        document.getElementById("addressType").value = "exist";
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
        row.innerHTML = '<td>'+count+'</td><td><input list="productList'+count+'" type="text" name="productId[]" id="productId" size="3" maxlength="6" oninput="createRow(this), suggestProduct(this);" autocomplete="off" ><dataList id="productList'+count+'" name="productList'+count+'"></dataList></td><td><input style="width:80%;" type="text" name="description[]" id="description" readonly></td><td><input style="width:80%; text-align:center;" type="number" name="quantity[]" id="quantity" value="0" step="1" min="0" max="999" oninput="calculateTotal(this),createRow(this);"></td><td><input style="width:80%; text-align:center;" type="number" name="unitPrice[]" readonly id="unitPrice" value="0.00" step="0.05" min="0" max="99999"></td><td><span id="total">0.00</span></td>';
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
function displayAddress(){
    var xmlhttp = new XMLHttpRequest();
    var search = document.getElementById("clientId").value;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementsByClassName("existAddress")[0].innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "AJAX/displayAddress.php?search="+search, true);
    xmlhttp.send();
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
//Open confirmation message dialog
function confirmMessageCreate(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageCreate").dialog('isOpen')){
            $("#confirmationMessageCreate").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageCreate").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#createForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}