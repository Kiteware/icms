<div id="topbar">
	<div class="right">
          		<?php if($general->logged_in()){?>
                <ul class="dropdown-menu">
       					<li><a href="#"><?php echo $user['username'];?> <img src="http://ui-cloud.com/res/pixelsdaily/Drop-Down-Menu/demo/images/arrow.png"/></a>
      						<ul>
     							<li><a href="?page=profile.php?username=<?php echo $user['username'];?>">Profile</a></li>
     							<li><a href="?page=settings.php">Settings</a></li>
     							<li><a href="?page=change-password.php">Change password</a></li>
     							<li><a href="admincp/index.php">Admin</a></li>
     							<li><a href="?page=logout.php">Log out</a></li>
      						</ul>
       					</li>
                </ul>
        		<?php
        		}else{?>
        			     <a href="?page=login.php">Login</a> | <a href="?page=register.php">Register</a>	
        	   <?php  }    ?>

    </div>
</div>
<header>
    <a href="index.php" class="logo">ICMS</a>
    <nav>
	    <?php include 'includes/menu.php'; ?>
    </nav>
</header>
