//Open confirmation message dialog
function confirmMessageProfile(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessageProfile").dialog('isOpen')){
            $("#confirmationMessageProfile").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessageProfile").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable:false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#profileForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
/*Address remove confirmation box*/
function openBox(address){
    document.getElementById("confirmationBox").style.display="block";
    addressId = address;
}
function closeBox(){
    document.getElementById("confirmationBox").style.display="none";
}
var addressId = "";
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
        xmlhttp.open("GET", "AJAX/removeAddress.php?addressID=" + addressId, true);
        xmlhttp.send();
    }
    closeBox();
}
function addNotification(type,message){ //Create the notification
    var notice = document.createElement("div");
    notice.className = type;
    notice.id="notification2";
    notice.innerHTML = message;
    document.getElementById("notificationBox").appendChild(notice);
    setTimeout(function(){removeNotification();}, 2400);
}
function removeNotification(){ //Remove the notification after 10 second
    var notice = document.getElementById("notification2");
    notice.style.opacity = "0";
    setTimeout(function(){ notice.remove(); }, 600);
    
}