<?php
namespace Nixhatter\ICMS;
?>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-decode navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><?php echo $this->container['settings']->production->site->name; ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                $pages    = new model\PagesModel($this->container);
                $navigation = $pages->list_nav();
                foreach ($navigation as $showNav) {
                    echo "<li><a href=\"".$showNav['nav_link']."\">".$showNav['nav_name']."</a></li>";
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($this->controller->logged_in()) {?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->user['username'];?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/user/profile">Profile</a></li>
                            <li><a href="/user/settings">Settings</a></li>
                            <li><a href="/user/changepassword">Change password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/admin">Admin</a></li>
                            <li><a href="/user/logout">Log out</a></li>
                        </ul>
                    </li>
                    <?php
                } else {?>
                <li><a href="/user/login">login</a></li>
                <li class="active"><a href="/user/register">register</a></li>
            </ul>
            <?php  }    ?>
        </div>
    </div>
</nav>
