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
function searchClient(){  //Search from latest 5 clients table
    var i,k,l=0;
    var searchBar = document.getElementById("searchBarClient");
    var filter = searchBar.value.toUpperCase();
    var data = document.getElementById("clientList");
    var slot = data.getElementsByClassName("clientData");
     
    for (i=0;i < slot.length;i++){
        var tdSlot = slot[i].getElementsByTagName("td");
        data.getElementsByClassName("emptySlot")[0].style.display="none";
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
    if(l==0){
        if (data.getElementsByClassName("emptySlot").length==1){
            data.getElementsByClassName("emptySlot")[0].style.display="table-cell";
        }
    }
}
function sortClient(col){ //Sort latest 5 clients table
    var table = document.getElementById("clientList");
    var row = table.getElementsByClassName("clientData");
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
    for (var k = 0;k<th.length;k++){
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
function searchOrder(){ //Search from latest 5 orders table
    var i,k,l = 0;
    var searchBar = document.getElementById("searchBarOrder");
    var filter = searchBar.value.toUpperCase();
    var data = document.getElementById("orderList");
    var slot = data.getElementsByClassName("orderData");
    for (i=0;i < slot.length;i++){
        var tdSlot = slot[i].getElementsByTagName("td");
        data.getElementsByClassName("emptySlot")[0].style.display="none";
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
    if(l==0){
        if (data.getElementsByClassName("emptySlot").length==1){
            data.getElementsByClassName("emptySlot")[0].style.display="table-cell";
        }
    }
}
function sortOrder(col){ //Sort latest 5 orders table
    var table = document.getElementById("orderList");
    var row = table.getElementsByClassName("orderData");
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
    for (var k = 0;k<th.length;k++){
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
function searchSupport(){ //Search from latest 5 supports table
    var i,k,l = 0;
    var searchBar = document.getElementById("searchBarSupport");
    var filter = searchBar.value.toUpperCase();
    var data = document.getElementById("supportList");
    var slot = data.getElementsByClassName("supportData");
    for (i=0;i < slot.length;i++){
        var tdSlot = slot[i].getElementsByTagName("td");
        data.getElementsByClassName("emptySlot")[0].style.display="none";
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
    if(l==0){
        if (data.getElementsByClassName("emptySlot").length==1){
            data.getElementsByClassName("emptySlot")[0].style.display="table-cell";
        }
    }
}
function sortSupport(col){ //Sort latest 5 supports table
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
    for (var k = 0;k<th.length;k++){
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
//Open and close the container
$(document).ready(function(){
  $(".collapse").click(function(){
      $openBtn = $(this); //Open or close button
      $openBtn.find(".fa-plus-square").toggle();
      $openBtn.find(".fa-minus-square").toggle();
      $filter = $openBtn.parent().parent();
      $table = $filter.next();
      $table.toggle();
  });
  $(".summaryCollapse").click(function(){
      $openBtn = $(this); //Open or close button
      $openBtn.find(".fa-plus-square").toggle();
      $openBtn.find(".fa-minus-square").toggle();
      $graph = $openBtn.next().next().next();
      $graph.toggle();
  });
  $(".statusCollapse").click(function(){
      $openBtn = $(this); //Open or close button
      $openBtn.find(".fa-plus-square").toggle();
      $openBtn.find(".fa-minus-square").toggle();
      $bar = $openBtn.next().next();
      $bar.toggle();
      $desc = $openBtn.next().next().next();
      $desc.toggle();
  });
});