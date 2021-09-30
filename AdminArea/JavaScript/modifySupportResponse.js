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
