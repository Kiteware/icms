<ul>
	<li><a href="index.php">Home</a></li>
	<?php 

	if($general->logged_in()){?>
		<li><a href="members.php">Members</a></li>
		<li><a href="profile.php?username=<?php echo $user['username'];?>">Profile</a></li>
		<li><a href="settings.php">Settings</a></li>
		<li><a href="change-password.php">Change password</a></li>
		<li><a href="logout.php">Log out</a></li>
	<?php
	}else{?>
		<li><a href="register.php">Register</a></li>
		<li><a href="login.php">Login</a></li>
	<?php
	}
	?>
</ul>
<hr />