<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>

<div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
        <?php
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
        ?>
        <p>Welcome to the ICMS Administrator panel. <br />
            GitHub: <a href="https://github.com/Nixhatter/CMS">https://github.com/Nixhatter/CMS </a><br />
            Support: dillon@nixx.co</p>
    </div>
</div>
</div>
<div class="col-md-6">
    <div id="menu-manager" class="box">
        <div class="box-header">Menu Manager</div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Positions</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $navigation        =$this->model->list_nav();
                foreach ($navigation as $showNav) {
                    //displaying posts
                    echo "
            <tr><td>".($showNav['nav_name']."</td>
			<td>".$showNav['nav_link']."</td>
			<td>".$showNav['nav_position']."</td>
            <td><a onClick='editNav(\"".$showNav['nav_name']."\", \"".$showNav['nav_link']."\", \"".$showNav['nav_position']."\", \"".$showNav['nav_permission']."\");'>edit</a></td>
            <td><a onClick='deleteNav(\"".$showNav['nav_link']."\");'> Delete </a></td></tr>");
                }
                ?>
                </tbody>
            </table>
            <div id="create-menu">
                <h3>Add a New Navigation Item</h3>
                <form class="partial-reload-form" action="/admin/pages/menu" method="post" name="menu-manager">
                    <fieldset class="form-group">
                        <label>Name </label>
                        <input id="nav_name" name="nav_name" type="text" class="form-control"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Link </label>
                        <input id="nav_link" name="nav_link" type="text" class="form-control"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Position </label>
                        <input id="nav_position" name="nav_position" type="text" class="form-control" size="5"/>
                    </fieldset>
                    <input type="checkbox" name="is-update" id="is-update" style="display:none" />
                    <button name="nav_create" type="submit" value="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="box">
        <div class="box-header">New Blog Post</div>
        <div class="box-body">
            <form action="/admin/blog/create" method="post" class="partial-reload-form" name="menu-manager" enctype="multipart/form-data">
                <fieldset class="form-group">
                    <label for="postName">Title</label>
                    <input type="text" class="form-control" name="postName" id="postName">
                </fieldset>
                <fieldset class="form-group">
                    <label for="postContent">Content</label>
                    <textarea class="form-control" name="postContent"></textarea>
                </fieldset>
                <button name="submit" type="submit" class="btn btn-primary">Publish</button>
            </form>
        </div>
    </div>
</div>