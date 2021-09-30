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
//Open confirmation message dialog
function confirmMessageWishList(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageWishList").dialog('isOpen')){
            $("#confirmationMessageWishList").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageWishList").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               wishListadd();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Open confirmation message dialog
function confirmMessageCart(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageCart").dialog('isOpen')){
            $("#confirmationMessageCart").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageCart").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               cartadd();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
//Send wishlist request
function wishListadd(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        addNotification("green","<b>SUCCESFUL</b>! Added to wishList.");
      }
    };
    xmlhttp.open("GET", "AJAX/wishList.php?productID=" + productID, true);
    xmlhttp.send();
    return false;

}
//Send shopping cart request
function cartadd(){
    var xmlhttp = new XMLHttpRequest();
    var quantity = document.getElementsByName("qty")[0].value;
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        addNotification("green","<b>SUCCESFUL</b>! Added to shoppingcart.");
        var amount = document.getElementsByClassName("notification")[0].innerHTML;
        document.getElementsByClassName("notification")[0].innerHTML = parseInt(amount)+1;
      }
    };
    xmlhttp.open("GET", "AJAX/shoppingcart.php?productID=" + productID+"&quantity="+quantity, true);
    xmlhttp.send();
    return false;

}