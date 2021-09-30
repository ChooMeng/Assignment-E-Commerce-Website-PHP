//Open confirmation message dialog
function confirmMessage(){
    $(window).resize(function(){ //Resize the box when window is resizing
        if ($("#confirmationMessage").dialog('isOpen')){
            $("#confirmationMessage").dialog({
                width: "auto"
            });
        }
    });
    $("#confirmationMessage").dialog({ //Setup the dialog
       resizeable: false,
       height: "auto",
       width: "auto",
       modal: true,
       draggable: false,
       buttons: {
           Confirm: function(){
               $( this ).dialog( "close" );
               $("form").submit();
           },
           Cancel:function(){
               $( this ).dialog( "close" );
           }
       }
    });
}
