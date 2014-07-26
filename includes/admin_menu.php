<div id="sidebar">
    <div class="dropdown" >
      <a href="index.php">Home</a>
    </div>
	<?php 
	if($general->logged_in()){?>

    <div class="dropdown" >
      Blog
      <ul>
        <li><a href="index.php?blog.php">View</a></li>
        <li><a href="index.php?new_blog.php">New</a></li>
        <li><a href="index.php?edit_blog.php">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
      Pages
      <ul>
        <li><a href="index.php?pages.php">View</a></li>
        <li><a href="index.php?new_page.php">New</a></li>
        <li><a href="index.php?edit_page.php">Edit</a></li>
      </ul>
    </div> 
   <div class="dropdown" >
      Users
      <ul>
        <li><a href="index.php?users.php">View</a></li>
        <li><a href="index.php?new_user.php">New</a></li>
        <li><a href="index.php?edit_user.php">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
        <a href="index.php?settings.php">Settings</a>
    </div>
    <div class="dropdown">
        <a href="index.php?template.php">Template</a>
    </div>

	<?php
	}
	?>
 </div>