    <div class="dropdown" >
      Home
    </div>
	<?php 
	if($general->logged_in()){?>

    <div class="dropdown" >
      Blog
      <ul>
        <li><a href="blog.php">View</a></li>
        <li><a href="new_blog.php">New</a></li>
        <li><a href="edit_blog.php">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
      Pages
      <ul>
        <li><a href="pages.php">View</a></li>
        <li><a href="new_page.php">New</a></li>
        <li><a href="edit_page.php">Edit</a></li>
      </ul>
    </div> 
   <div class="dropdown" >
      Users
      <ul>
        <li><a href="users.php">View</a></li>
        <li><a href="new_user.php">New</a></li>
        <li><a href="edit_user.php">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
    Settings
        <ul>
            <li> <a href="settings.php">Settings</a></li>
        </ul>
    </div>
    <div class="dropdown">
    Template
     <ul>
        <li><a href="template.php">Template</a></li>
      </ul> 
    </div>

	<?php
	}
	?>
