/*Open/Close side navigationbar*/
function openSideNav(){
    var sideBar = document.getElementsByClassName("sideBar")[0];
    var productList = sideBar.getElementsByClassName("productList")[0];
    if (sideBar.style.opacity=="1"){
        sideBar.style.width="0";
        sideBar.style.opacity="0";
    }else{
        sideBar.style.width="20%";
        sideBar.style.opacity="1";
        productList.style.display = "none";
        unHover(sideBar.getElementsByTagName("li")[1])
    }
}
/*Open product category*/
function openCat(element){
    unHover(element.parentNode.parentNode);
    var category = element.parentNode.getElementsByTagName("div")[0];
    category.style.display="inline-block";
    element.style.backgroundColor="darkgray";
}
/*Open product subcategory*/
function openSubCat(element){
    unHover2(element.parentNode.parentNode);
    var category = element.nextElementSibling;
    category.style.display="inline-block";
    element.style.backgroundColor="darkgray";
}
/*Hover the large resolutation category bar*/
function hover(element){
    element.getElementsByTagName("a")[0].style.backgroundColor="#4ea8de";
}
/*Unhover the large resolution category bar*/
function unHover(element){
    var select = element.querySelectorAll(".sub-category");
    var select2 = element.querySelectorAll(".sub-sub-category");
    var category = element.querySelectorAll(".category");
    for (i=0;i<select.length;i++){
        select[i].removeAttribute("style");
        var numA = select[i].getElementsByTagName("a");
        for (l=0;l<numA.length;l++){
            select[i].getElementsByTagName("a")[l].removeAttribute("style");
        }
    }
    for (k=0;k<category.length;k++){
         category[k].getElementsByTagName("a")[0].removeAttribute("style");
    }
    for (n=0;n<select2.length;n++){
        select2[n].removeAttribute("style");
    }
    element.getElementsByTagName("a")[0].removeAttribute("style");
}
/*Unhover the small resolution category bar*/
function unHover2(element){
    var select = element.querySelectorAll(".sub-category");
    var select2 = element.parentNode.parentNode.querySelectorAll(".sub-sub-category");
    for (i=0;i<select.length;i++){
        var numA = select[i].getElementsByTagName("a");
        for (l=0;l<numA.length;l++){
            select[i].getElementsByTagName("a")[l].removeAttribute("style");
        }
    }
    for (n=0;n<select2.length;n++){
        select2[n].removeAttribute("style");
    }
}
/*Open small resolution product list*/
function mobileProduct(element){
    var select = element.parentNode.parentNode;
    unHover(element.parentNode);
    if (select.style.width=="100%"){
        element.nextElementSibling.style.display = "none"
        select.style.width = "20%";
    }else{
        select.style.width = "100%";
        element.nextElementSibling.style.display = "block"
    }
}
/*Set hover color to account menu when account dropdown menu is hovering*/
function navHover(element){
    element.parentNode.getElementsByTagName("a")[0].classList.add("pointing");
}
/*Unset hover color to account menu when account dropdown menu is hovering*/
function navUnHover(element){
    element.parentNode.getElementsByTagName("a")[0].classList.remove("pointing");
}
/*Set color of search bar when hover*/
function searchHover(element){
    element.style.backgroundColor="gray";
}
/*Set color of search bar when unhover*/
function searchunHover(element){
    element.style.backgroundColor="lightgray";
}
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