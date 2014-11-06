<body>
<div class="topbar">
	<div class="right">
        <div class="whitebg">
          		<?php if ($general->logged_in()) {?>
       					<span id="toggle-menu"><?php echo $user['username'];?></span>
                        </div>
                            <div id="menu">
     							<a href="?page=profile.php&username=<?php echo $user['username'];?>">Profile</a>
     							<a href="?page=settings.php">Settings</a>
     							<a href="?page=change-password.php">Change password</a>
     							<a href="admincp/index.php">Admin</a>
     							<a href="?page=logout.php">Log out</a>
                             </div>
        		<?php
                } else {?>
  			       <a id="toggle-login">Log in</a>
                        <div id="login">
                          <h1>Log in</h1>
                          <form method="post" action="index.php?page=login.php">
                            <input type="text" name="username" placeholder="username" />
                            <input type="password" name="password" placeholder="Password" />
                            <input type="submit" name="submit" value="Login" />
                          </form>
                        </div>
                    |
                         <a href="?page=register.php">Register</a>
        	   <?php  }    ?>
            </div>
        </div>
    </div>
<header>
    <div class="banner">Intelligent <strong>CMS</strong><br/><small>0.3</small></div>
</header>
