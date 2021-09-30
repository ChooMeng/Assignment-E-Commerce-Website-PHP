//Delete wishlist item
function removeWishlist(prodId){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            
            location.reload();
            
        }
    };
    xmlhttp.open("GET", "AJAX/removeWishlist.php?productID=" + prodId, true);
    xmlhttp.send();
  }

  function addToCart(prodId){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            addNotification("green","<b>SUCCESFUL</b>! Added to shopping cart.")
            var amount = document.getElementsByClassName("notification")[0].innerHTML;
            document.getElementsByClassName("notification")[0].innerHTML = parseInt(amount)+1;
        }
    };
    xmlhttp.open("GET", "AJAX/addToCart.php?productID=" + prodId, true);
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