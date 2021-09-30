var productId = "";
/*Product Restore Confirmation box*/
function openBox(product){
    document.getElementById("confirmationBox").style.display="block";
    productId = product;
}
function closeBox(){
    document.getElementById("confirmationBox").style.display="none";
}
function restoreProduct(){ /*Restore product*/
    var xmlhttp = new XMLHttpRequest();
    var reason = document.getElementsByName("reason")[0].value;
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        location.href="productRestore.php";
      }
    };
    xmlhttp.open("GET", "AJAX/restoreProduct.php?productID=" + productId+"&reason="+reason, true);
    xmlhttp.send();
    closeBox();
    return false;
}

/*Search from table*/
function search(str,sort,sortOrder,page,total) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("table").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "AJAX/searchRemovedProducts.php?search=" + str+"&sort='"+sort+"&sortOrder='"+sortOrder+"&page="+page+"&total="+total, true);
    xmlhttp.send();
}
/*Sort the table*/
function sort(col){
    var table = document.getElementById("orderList");
    var row = table.getElementsByClassName("productData");
    var sortType = "ascending";
    var changeSwitchType = false;
    do{
        var sorting = false;
        for (var i = 0;i < row.length-1;i++){
            current = row[i].getElementsByTagName("td")[col];
            target = row[i+1].getElementsByTagName("td")[col];
            if (sortType=="ascending"){
                if (current.innerHTML.toLowerCase()>target.innerHTML.toLowerCase()){
                    sorting = true;
                    row[i].parentNode.insertBefore(row[i+1],row[i]);
                    changeSwitchType = true;
                    break; //To prevent still looping for no purpose and waste memory
                }
            }else{
                if (current.innerHTML.toLowerCase()<target.innerHTML.toLowerCase()){
                    sorting = true;
                    row[i].parentNode.insertBefore(row[i+1],row[i]);
                    break; //To prevent still looping for no purpose and waste memory
                }
            }
            
        }
        if(sortType == "ascending"&&changeSwitchType==false){
            sortType ="descending";
            sorting = true;
        }
    }while(sorting==true);
    var th = table.getElementsByTagName("tr")[0].getElementsByTagName("th");
    for (var k = 0;k<th.length-1;k++){
        var spanSort = th[k].getElementsByTagName("span")[0];
        if (k==col){
            if (sortType=="ascending"){
                spanSort.innerHTML = '<i class="fas fa-sort-up"></i></span>';
            }else{
                spanSort.innerHTML = '<i class="fas fa-sort-down"></i></span>';
            }
        }else{
            spanSort.innerHTML = '<i class="fas fa-sort"></i></span>';
        }
    }
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