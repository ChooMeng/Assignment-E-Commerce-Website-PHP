<!-- Display client order details -->
            <h2 style="text-align:center">Client Order Lists</h2>
            <!-- Search Bar -->
            <div class="filter">
                <div class="bar">
                    
                    <input type="text" class="searchBar" id="searchBar" onkeyup="search()" placeholder="Filter the client list" title="Type in any word that you want to search">
                    <i id="searchIcon" class="fas fa-search"></i>
                </div>
            </div>
            <table id="orderList">
                <tr>
                    <th width="10%" onclick="sort(0);">Order ID<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="20%" onclick="sort(1);">Customer Name<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="15%" onclick="sort(2);">Purchased Time<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="10%" onclick="sort(3);">Order No.<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="10%" onclick="sort(4);">Product Amount<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="10%" onclick="sort(5);">Total<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="15%" onclick="sort(6);">Status<span id="sort"><i class="fas fa-sort"></i></span></th>
                    <th width="10%" >Action</th>
                </tr>
                    <?php orderDetails();?>
                <tr>
                        <td colspan='9'height='60px' style="display:none;" class="emptySlot"><b>NO RESULT FOUND</b></td>
                    </tr>
            </table>
            <!-- Page number -->
            <div id="page">
                     <div id="pageNumber">
                        <a><i class="fas fa-backward"></i></a>
                        <a class="current">1</a>
                        <a><i class="fas fa-forward"></i></a>
                    </div>
                </div>
            <br/>