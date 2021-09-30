//Delete Shopping Cart Row
function applyDiscount(){
    var xmlhttp = new XMLHttpRequest();
    var discountID = document.getElementsByName("voucherInput")[0].value;
    xmlhttp.onreadystatechange = function() {
        
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    };
    xmlhttp.open("GET", "AJAX/countDiscount.php?discount=" + discountID, true);
    xmlhttp.send();
}
//Remove from cart
function removeFromCart(productID){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    };
    xmlhttp.open("GET", "AJAX/removeFromCart.php?productID=" + productID, true);
    xmlhttp.send();
}
function addNotification(type,message){ //Create the notification
    var notice = document.createElement("div");
    notice.className = type;
    notice.id="notifications";
    notice.innerHTML = message;
    document.getElementById("notificationBox").appendChild(notice);
    setTimeout(function(){removeNotification();}, 2400);
}
function removeNotification(){ //Remove the notification after 10 second
    var notice = document.getElementById("notifications");
    notice.style.opacity = "0";
    setTimeout(function(){ notice.remove(); }, 600);
    
}
function updateQuantity(productID,element){
    var xmlhttp = new XMLHttpRequest();
    var quantity = element.value;
    xmlhttp.onreadystatechange = function() {
        
        if (this.readyState == 4 && this.status == 200) {
            window.history.replaceState( null, null, window.location.href );
            location.href="shoppingcart.php";
        }
    };
    xmlhttp.open("GET", "AJAX/updateQuantity.php?productID=" + productID+"&quantity="+quantity, true);
    xmlhttp.send();
}