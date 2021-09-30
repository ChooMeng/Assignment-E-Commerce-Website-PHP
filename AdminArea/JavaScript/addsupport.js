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