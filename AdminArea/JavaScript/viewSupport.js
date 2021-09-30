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
var msgId = "";
var supportId = "";
/*Support Tickets message remove confirmation box*/
function openBox(msg,support){
    document.getElementById("confirmationBox").style.display="block";
    msgId = msg;
    supportId = support;
}
function closeBox(){
    document.getElementById("confirmationBox").style.display="none";
}

/*Remove support ticket response*/
function removeSupport(){
    var xmlhttp = new XMLHttpRequest();
    var reason = document.getElementsByName("reason")[0].value;
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          
        location.reload();
        
      }
    };
    xmlhttp.open("GET", "AJAX/removeMessage.php?supportID=" + supportId+"&messageID="+msgId+"&reason="+reason, true);
    xmlhttp.send();
    closeBox();
    return false;
}
/*Update support tickets status and priority box*/
function openSupportBox(support){
    document.getElementById("supportBox").style.display="block";
    supportId = support;
}
function closeSupportBox(){
    document.getElementById("supportBox").style.display="none";
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
function updateStatus(){ /*Update status and priority*/
    var xmlhttp = new XMLHttpRequest();
    var statusApply = document.getElementsByName("statusUpdate")[0].value;
    var priorityApply = document.getElementsByName("priorityUpdate")[0].value;
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        location.reload();
      }
    };
    xmlhttp.open("GET", "AJAX/updateStatusSupport.php?supportID=" + supportId+"&statusApply="+statusApply+"&priorityApply="+priorityApply, true);
    xmlhttp.send();
    closeBox();
    return false;
}