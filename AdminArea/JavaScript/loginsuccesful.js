var i = 2;
var timer = setInterval(function() {
    document.getElementById("time").innerHTML=i;
    i--;
},1000);