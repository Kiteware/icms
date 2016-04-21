<nav class="navbar navbar-static-top">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/admin"> <?php echo $this->settings->production->site->name." Administrator Panel" ?></a>
    </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a >
                        <i class="fa fa-paper-plane-o fa-lg"></i>
                        <span class="b-userbar__icons-item-notify i-font_normal">0</span>
                    </a>
                </li>
                <li>
                    <a href="http://<?php echo $this->settings->production->site->url ?>">
                        <i class="fa fa-sitemap"></i>
                    </a>
                </li>
                <li>
                    <a href="/user/profile" >
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
</nav>
