<?php defined('_ICMS') or die; ?>
<nav class="navbar navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed navbar-btn" data-toggle="collapse" data-target="#sidebar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/admin" class="navbar-brand"> <?php echo $this->settings->production->site->name." Administrator Panel" ?></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!--<li>
                    <a href="#">
                        <i class="fa fa-paper-plane-o fa-lg"></i>
                        <span class="b-userbar__icons-item-notify i-font_normal">0</span>
                    </a>
                </li> -->
                <li>
                    <a href="http://<?php echo $this->settings->production->site->url ?>" target="_blank">
                        <i class="fa fa-sitemap"></i>
                    </a>
                </li>
                <li>
                    <a href="/user/profile" target="_blank">
                        <?php echo $this->user['username']?>
                    </a>
                </li>
                <li>
                    <a href="/user/logout">
                        <i class="fa fa-power-off fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
