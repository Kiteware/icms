<head>
	<meta name="description" content="An Intelligent Content Management System">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">	
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<title>Welcome to ICMS</title>
</head>
<div id="topbar"><div class="right">Hello</div></div>
<header>
		<a href="index.php" class="logo">ICMS</a>
		
		<nav>
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
    	</nav>
	</header>
