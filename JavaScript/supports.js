/*Search from table*/
function search(str,type,sort,sortOrder,page,total) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("table").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "AJAX/searchSupport.php?search=" + str+"&status="+type+"&sort='"+sort+"&sortOrder='"+sortOrder+"&page="+page+"&total="+total, true);
    xmlhttp.send();
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
/*Sort the table*/
function sort(col){
    var table = document.getElementById("supportList");
    var row = table.getElementsByClassName("supportData");
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