<ul>
	<li><a href="../index.php">Home</a></li>
	<?php 
	if($general->logged_in()){?>
		<li><a href="blog.php">Blog</a></li>
			<ul>
				<li><a href="new_blog.php">New Blog Post</a></li>
				<li><a href="edit_blog.php">Edit Blog Post</a></li>
			</ul>
		<li><a href="pages.php">Pages</a></li>
			<ul>
				<li><a href="new_page.php">New Page</a></li>
				<li><a href="edit_page.php">Edit Page</a></li>
			</ul>
		<li><a href="modules.php">Modules</a></li>
			<ul>
				<li><a href="new_module.php">New Module</a></li>
				<li><a href="edit_module.php">Edit Module</a></li>
			</ul>
		<li><a href="users.php">Users</a></li>
			<ul>
				<li><a href="new_user.php">New User</a></li>
				<li><a href="edit_user.php">Edit User</a></li>
			</ul>
		<li><a href="settings.php">Settings</a></li>
		<li><a href="template.php">Template</a></li>
	<?php
	}
	?>
</ul>

