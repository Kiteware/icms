<div id="sidebar">
    <div class="dropdown" >
      <a href="index.php">Home</a>
    </div>
	<?php 
	if($general->logged_in()){?>

    <div class="dropdown" >
      Blog
      <ul>
        <li><a href="index.php?page=new_blog.php">New</a></li>
        <li><a href="index.php?page=edit_blog.php">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
      Pages
      <ul>
        <li><a href="index.php?page=new_page.php">New</a></li>
        <li><a href="index.php?page=edit_page.php">Edit</a></li>
      </ul>
    </div> 
   <div class="dropdown" >
      Users
      <ul>
        <li><a href="index.php?page=new_user.php">New</a></li>
        <li><a href="index.php?page=edit_user.php">Edit</a></li>
        <li><a href="index.php?page=edit_permissions.php">Permissions</a></li>
      </ul>
    </div>
    <div class="dropdown" >
        <a href="index.php?page=settings.php">Settings</a>
    </div>
    <div class="dropdown">
        <a href="index.php?page=template.php">Template</a>
    </div>

	<?php
	}
	?>
 </div>