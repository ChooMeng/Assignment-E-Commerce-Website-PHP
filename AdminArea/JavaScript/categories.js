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
function sort(col){ //Sort the table
    var table = document.getElementById("categories");
    var row = table.getElementsByClassName("categoriesData");
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
/*Search from table*/
function search(){
    var i,k,l = 0;
    var searchBar = document.getElementById("searchBar");
    var filter = searchBar.value.toUpperCase();
    var data = document.getElementById("categories");
    var slot = data.getElementsByClassName("categoriesData");
    for (i=0;i < slot.length;i++){
        var tdSlot = slot[i].getElementsByTagName("td");
        for (k=0;k < tdSlot.length-1;k++){
            var contents = tdSlot[k].innerText.toUpperCase();
            if (contents.indexOf(filter)>-1){
                slot[i].style.display="table-row";
                l++;
                break;
            }else{
                slot[i].style.display="none";
            }
        }
    }
}
/*Delete Category Confirmation box*/
var categoryID = "";

function openBox(category){
    document.getElementById("confirmationBox").style.display="block";
    categoryID = category;
}
function closeBox(){
    document.getElementById("confirmationBox").style.display="none";
}

function deleteCategory(){ /*Delete category*/
    var xmlhttp = new XMLHttpRequest();
    var reason = document.getElementsByName("reason")[0].value;
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          
        location.href="categories.php";
      }
    };
    xmlhttp.open("GET", "AJAX/deleteCategory.php?categoryID=" + categoryID+"&reason="+reason, true);
    xmlhttp.send();
    closeBox();
    return false;
}