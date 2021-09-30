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
    var profile = document.getElementsByClassName("profile")[0];
    var profiletab = document.getElementById("profile");
    var changePassword = document.getElementsByClassName("changePassword")[0];
    var changePasswordtab = document.getElementById("changePassword");
    var address = document.getElementsByClassName("addressA")[0];
    var addresstab = document.getElementById("addressA");
    var modifyAddress = document.getElementsByClassName("modifyAddress")[0];
    var newAddress = document.getElementsByClassName("newAddress")[0];
    if (type=="profile"){
        
        profile.style.display="block";
        changePassword.style.display="none";
        profiletab.className="active";
        changePasswordtab.classList.remove("active");
        address.style.display="none";
        addresstab.classList.remove("active");
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="password"){
        profile.style.display="none";
        changePassword.style.display="block";
        profiletab.classList.remove("active");
        changePasswordtab.className="active";
        address.style.display="none";
        addresstab.classList.remove("active");
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="address"){
        profile.style.display="none";
        profiletab.classList.remove("active");
        changePassword.style.display="none";
        changePasswordtab.classList.remove("active");
        address.style.display="block";
        addresstab.className="active";
        modifyAddress.style.display="none";
        newAddress.style.display="none";
    }else if(type=="existaddress"){
        profile.style.display="none";
        profiletab.classList.remove("active");
        changePassword.style.display="none";
        changePasswordtab.classList.remove("active");
        address.style.display="none";
        addresstab.className="active";
        modifyAddress.style.display="block";
        newAddress.style.display="none";
    }else{
        profile.style.display="none";
        profiletab.classList.remove("active");
        changePassword.style.display="none";
        changePasswordtab.classList.remove("active");
        address.style.display="none";
        addresstab.className="active";
        modifyAddress.style.display="none";
        newAddress.style.display="block";
    }
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
//Open confirmation message dialog
function confirmMessagePassword(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessagePassword").dialog('isOpen')){
            $("#confirmationMessagePassword").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessagePassword").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("#changePasswordForm").submit();
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
               $("#newAddressForm").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}