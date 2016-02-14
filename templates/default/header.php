<body>
<div class="topbar">
    <div class="right">
        <?php if ($this->container['general']->logged_in()) {?>
            <div class="whitebg">
                <span id="toggle-menu"><?php echo $this->user['username'];?></span>
            </div>

        <div id="menu">
            <a href="/user/profile">Profile</a>
            <a href="/user/settings">Settings</a>
            <a href="/user/changepassword">Change password</a>
            <a href="/admin">Admin</a>
            <a href="/user/logout">Log out</a>
        </div>
        <?php
        } else {?>
        <div class="whitebg">
            <a id="toggle-login">Log in</a>
            <div id="login">
                <h1>Log in</h1>
                <form method="post" action="/user/login">
                    <input type="text" name="username" placeholder="username" />
                    <input type="password" name="password" placeholder="Password" />
                    <input type="submit" name="submit" value="Login" />
                </form>
            </div>
            |
            <a href="/user/register">Register</a>
            </div>
        <?php  }    ?>
    </div>
</div>
<header>
    <div class="banner">Intelligent <strong>CMS</strong><br/><small>0.5</small></div>
</header>
