<link href="CSS/topNavBar.css" rel="stylesheet" type="text/css"/>
<div class="topNavBar">
    <a href="profile.php" class="topNavButton<?php activeSubNavigation('profile.php');  ?>" >View Profile</a>
    <a href="editProfile.php" class="topNavButton<?php activeSubNavigation('editProfile.php');activeSubNavigation('editAddress.php');?>">Edit Profile</a>
    <a href="changePassword.php" class="topNavButton<?php activeSubNavigation('changePassword.php');activeSubNavigation('newPassword.php');?>">Change Password</a>
    <a href="addAddress.php" class="topNavButton<?php activeSubNavigation('addAddress.php');?>">Add Address</a>
    
</div>