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