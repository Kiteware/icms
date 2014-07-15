

<div id="topbar">
	<div class="right">
		<?php 
		if($general->logged_in()){?>
		<div class="content">
		  <div id="admin"><?php echo $user['username'];?></div>
		  <div id="settings" class="fa fa-cog"></div>
		  <div id="menu">
			<div id="arrow"></div>
			<a href="profile.php?username=<?php echo $user['username'];?>">Profile<i id="firstIcon" class="fa fa-user"></i></a>
			<a href="settings.php">Settings<i id="secondIcon" class="fa fa-bar-chart-o"></i></a>
			<a href="change-password.php">Change password <i id="thirdIcon" class="fa fa-cloud-upload"></i></a>
			<a href="admincp/index.php">Admin<i id="fourthIcon" class="fa fa-pencil"></i></a>
			<a href="logout.php">Log out<i id="fourthIcon" class="fa fa-pencil"></i></a>
		  </div>
		</div>	
		<?php
		}else{?>
			<li><a href="register.php">Register</a></li>
			<li> | </li>
			<li><a href="login.php">Login</a></li>
		<?php
		}
		?>
	</ul>
	</div>
</div>
<header>
		<a href="index.php" class="logo">ICMS</a>
		<nav>
	    <?php include 'includes/menu.php'; ?>
	    </nav>
	</header>
