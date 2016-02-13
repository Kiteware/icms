<div id="sidebar">
    <a href="/admin">
        <div class="dropdown" >
            Home
        </div>
    </a>
	<?php
    if ($this->general->logged_in()) {?>
    <div class="dropdown" >
      Blog
      <ul>
        <li><a href="/admin/blog/create">New</a></li>
        <li><a href="/admin/blog/edit">Edit</a></li>
      </ul>
    </div>
    <div class="dropdown" >
      Pages
      <ul>
        <li><a href="/admin/pages/create">New</a></li>
        <li><a href="/admin/pages/edit">Edit</a></li>
      </ul>
    </div>
   <div class="dropdown" >
      Users
      <ul>
        <li><a href="/admin/user/create">New</a></li>
        <li><a href="/admin/user/edit">Edit</a></li>
        <li><a href="/admin/user/permissions">Permissions</a></li>
      </ul>
    </div>
    <a href="/admin/site/settings">
        <div class="dropdown" >
            Settings
        </div>
    </a>
    <a href="/admin/site/template">
        <div class="dropdown">
            Template
        </div>
    </a>
<?php } ?>
</div>
