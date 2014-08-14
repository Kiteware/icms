<div id="sidebar">
    <a href="index.php">
        <div class="dropdown" >
            Home
        </div>
    </a>
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
    <a href="index.php?page=settings.php">
        <div class="dropdown" >
            Settings
        </div>
    </a>
    <a href="index.php?page=template.php">
        <div class="dropdown">
            Template
        </div>
    </a>

	<?php
	}
	?>
 </div>