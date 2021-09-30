//Scroll to the top button
window.onload = function(){
    var topButton = document.getElementById("scrollToTop");
    window.onscroll = function() {detectScroll()};
    function detectScroll(){
        if(document.body.scrollTop>50||document.documentElement.scrollTop > 50){
            topButton.style.display = "block";
        }else{
            topButton.style.display = "none";
        }
    }
    
};
function scrollFunction(){
    window.location.href="#top"
}

/*Set hover color to account menu when account dropdown menu is hovering*/
function navHover(element){
    element.parentNode.getElementsByTagName("a")[0].classList.add("pointing");
}
/*Unset hover color to account menu when account dropdown menu is hovering*/
function navUnHover(element){
    element.parentNode.getElementsByTagName("a")[0].classList.remove("pointing");
}
/*Open/Close side navigationbar*/
function openSideNav(){
    var sideBar = document.getElementsByClassName("sideBar")[0];
    if (sideBar.style.opacity=="1"){
        sideBar.style.width="0";
        sideBar.style.opacity="0";
    }else{
        sideBar.style.width="20%";
        sideBar.style.opacity="1";
    }
}
