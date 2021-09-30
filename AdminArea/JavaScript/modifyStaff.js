/*Change the section type*/
function switchType(type){
    var profile = document.getElementsByClassName("profile")[0];
    var profiletab = document.getElementById("profile");
    var changePassword = document.getElementsByClassName("changePassword")[0];
    var changePasswordtab = document.getElementById("changePassword");
    if (type=="profile"){
        
        profile.style.display="block";
        changePassword.style.display="none";
        profiletab.className="active";
        changePasswordtab.classList.remove("active");
    }else if(type=="password"){
        profile.style.display="none";
        changePassword.style.display="block";
        profiletab.classList.remove("active");
        changePasswordtab.className="active";
    }
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
