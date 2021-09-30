var orderId = "";
var clientId = "";
/*Order remove confirmation box*/
function openBox(order,client){
  document.getElementById("cancelOrderBlock").style.display="block";
  orderId = order;
  clientId = client;
}

function closeBox(){
  document.getElementById("cancelOrderBlock").style.display="none";
}

/*Remove the order*/
function removeOrder(order){
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          location.reload();
        }
      };
      xmlhttp.open("GET", "AJAX/removeOrder.php?orderID=" + order + "&clientID=" + clientId, true);
      xmlhttp.send();
  closeBox();
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