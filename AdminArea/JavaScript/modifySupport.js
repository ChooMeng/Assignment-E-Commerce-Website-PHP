/*Change the section type*/
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