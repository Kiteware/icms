<?php defined('_ICMS') or die; ?>
<div id="sidebar-collapse" class="collapse navbar-collapse">
<div id="sidebar">
    <ul class="sidebar-nav">
        <?php
        if ($this->controller->logged_in()) {?>
            <li>
                <a href="/admin/home"><i class="fa fa-home fa-fw" aria-hidden="true"></i>&nbsp; Dashboard</a>
            </li>
            <li>
                <a href="#blog" data-toggle="collapse"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i>&nbsp; Blog<span class="caret"></span></a>
                <div class="collapse" id="blog">
                    <a href="/admin/blog/create">New</a>
                    <a href="/admin/blog/edit">Edit</a>
                </div>

            </li>
            <li>
                <a href="#pages" data-toggle="collapse"><i class="fa fa-file fa-fw" aria-hidden="true"></i>&nbsp; Pages<span class="caret"></span></a>
                <div class="collapse" id="pages">
                    <a href="/admin/pages/create">New</a>
                    <a href="/admin/pages/edit">Edit</a>
                </div>
            </li>
            <li>
                <a href="#users" data-toggle="collapse"><i class="fa fa-users fa-fw" aria-hidden="true"></i>&nbsp; Users<span class="caret"></span></a>
                <div class="collapse" id="users">
                    <a href="/admin/user/create">New</a>
                    <a href="/admin/user/edit">Edit</a>
                    <a href="/admin/user/permissions">Permissions</a>
                </div>
            </li>
            <li>
                <a href="/admin/site/settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i>&nbsp; Settings</a>
            </li>
            <li>
                <a href="/admin/site/template"><i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i>&nbsp; Template</a>
            </li>
            <li>
                <a href="/user/logout"><i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>&nbsp; Logout</a>
            </li>
        <?php } ?>
    </ul>
</div>
</div>
<div id="content">
    <div class="col-md-12">
