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
/*Support Tickets mark as resolved*/
function openResolvedBox(support){
    document.getElementById("resolvedConfirmationBox").style.display="block";
    supportId = support;
}
function closeResolvedBox(){
    document.getElementById("resolvedConfirmationBox").style.display="none";
}
/*Support Tickets mark as canceled*/
function openCanceledBox(support){
    document.getElementById("canceledConfirmationBox").style.display="block";
    supportId = support;
}
function closeCanceledBox(){
    document.getElementById("canceledConfirmationBox").style.display="none";
}
var supportId = "";
//Status change
function resolvedTicket(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        location.reload();
      }
    };
    xmlhttp.open("GET", "AJAX/resolvedTicket.php?supportID=" + supportId, true);
    xmlhttp.send();
    closeResolvedBox();
    return false;
}
function canceledTicket(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        location.reload();
      }
    };
    xmlhttp.open("GET", "AJAX/canceledTicket.php?supportID=" + supportId, true);
    xmlhttp.send();
    closeCanceledBox();
    return false;
}
// Zooming the image
function zoomSlide(element){
    var imgModal = document.getElementById("imgModal");
    var zoomImg = document.getElementById("zoomImg");
    imgModal.style.display ="block";
    zoomImg.src = element.src;
}
function closeZoom(){
    var imgModal = document.getElementById("imgModal");
    imgModal.style.display = "none";
}