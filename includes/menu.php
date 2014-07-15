<ul>
	<li><a href="index.php">Home</a></li>
	<?php 
	if($general->logged_in()){?>
		<li><a href="members.php">Members</a></li>
		<li><a href="admincp/index.php">Admin</a></li>
	<?php
	}else{?>
		<li><a href="register.php">Register</a></li>
		<li><a href="login.php">Login</a></li>
	<?php
	}
	?>
</ul>

