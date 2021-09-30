var id = 2;
var slideShowTimeout = 0;
var max = 0;
// Zooming the image
function zoomSlide(element){
    var imgModal = document.getElementById("imgModal");
    var zoomImg = document.getElementById("zoomImg");
    imgModal.style.display ="block";
    zoomImg.src = element.src;
    pauseSlide();
}
function closeZoom(){
    var imgModal = document.getElementById("imgModal");
    imgModal.style.display = "none";
    playSlide();
}

// SlideShow
function maxSlide(){
    var show = document.getElementsByClassName("slideshow")[0];
    var imgShow = show.getElementsByTagName("img");
    max = imgShow.length;
    slideShow();
}
//Display the slideshow
function slideShow(){
    if (id > max-2){
        id = 0;
    }else{
        id++;
    }
    currentSlide();
    slideShowTimeout = setTimeout(slideShow, 5000);
}
//Set the current slide
function currentSlide(){
    var i;
    var show = document.getElementsByClassName("slideshow")[0];
    var imgShow = show.getElementsByTagName("img");
    var showBar = document.getElementsByClassName("slideShowBar")[0];
    var bar = showBar.getElementsByTagName("img");
    for (i = 0;i < imgShow.length;i++){
        if (id == i){
            imgShow[i].style.display="block";
        }else{
            imgShow[i].style.display="none";
        }
    }
    for (i = 0;i < bar.length;i++){
        if (id == i){
            bar[i].className="active";
        }else{
            bar[i].classList.remove("active");
        }
    }
}
//Change the slide
function changeSlide(num){
    id = id + num;
    if (id>max-1){
        id = 0;
    }
    else if(id < 0){
        id = max-1;
    }
    currentSlide();
    if (slideShowTimeout != "0"){
        clearTimeout(slideShowTimeout);
        slideShowTimeout = setTimeout(slideShow, 5000);
    }
}
//Specific the slide
function specificSlide(num){
    id = num;
    currentSlide();
    if (slideShowTimeout != "0"){
        clearTimeout(slideShowTimeout);
        slideShowTimeout = setTimeout(slideShow, 5000);
    }
    
}
//Pause the slide
function pauseSlide(){
    var pause = document.getElementById("pause");
    var play = document.getElementById("play");
    pause.style.display ="none";
    play.style.display="block";
    clearTimeout(slideShowTimeout);
    slideShowTimeout = 0;
}
//Play the slide
function playSlide(){
    var pause = document.getElementById("pause");
    var play = document.getElementById("play");
    pause.style.display ="block";
    play.style.display="none";
    slideShowTimeout = setTimeout(slideShow, 5000);
}
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
//Scroll to the top button
window.onload = function(){
    var topButton = document.getElementById("scrollToTop");
    maxSlide();
    window.onscroll = function() {detectScroll()};
    function detectScroll(){
        if(document.body.scrollTop>50||document.documentElement.scrollTop > 50){
            topButton.style.display = "block";
        }else{
            topButton.style.display = "none";
        }
    }
    
};
