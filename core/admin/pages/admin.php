<?php
defined('_ICMS') or die;

$current = json_decode(file_get_contents("../version.json"), true);
$latest = json_decode(file_get_contents("https://raw.githubusercontent.com/Nixhatter/CMS/master/version.json"), true);

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">Admin Panel</div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <p>Welcome to the ICMS Administrator panel. <br />
                            GitHub: <a href="https://github.com/Nixhatter/CMS">https://github.com/Nixhatter/CMS </a><br />
                            Support: dillon@nixx.co</p>
                    </div>
                    <div class="col-sm-6">
                        Site Name: <?php echo $this->settings->production->site->name ?> <br />
                        Description: <?php echo $this->settings->production->site->description ?> <br />
                        Current Version: <?php echo $current['version'] ?> <br />
                        Latest Version: <?php echo $latest['version'] ?> <br />
                        Language: <?php echo $this->settings->production->site->language ?> <br />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
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
                    $navigation = $this->model->list_nav();
                    foreach ($navigation as $showNav) {
                        //displaying posts
                        echo "
            <tr><td>".$showNav['nav_name']."</td>
			<td>".$showNav['nav_link']."</td>
			<td>".$showNav['nav_position']."</td>
            <td><a onClick='editNav(\"".$showNav['nav_name']."\", \"".$showNav['nav_link']."\", \"".$showNav['nav_position']."\", \"".$showNav['nav_permission']."\");'><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a></td>
            <td><a onClick='deleteNav(\"".$showNav['nav_link']."\");'> <i class=\"fa fa-trash\" aria-hidden=\"true\"></i> </a></td></tr>";
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
                        <input type="checkbox" name="is_update" id="is_update" style="display:none" />
                        <button name="nav_create" type="submit" value="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">New Blog Post</div>
            <div class="box-body">
                <form action="/admin/blog/create" method="post" class="no-reload-form" name="menu-manager" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="postName">Title</label>
                                <input type="text" class="form-control" name="postName" id="postName">
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="postDesc">Meta Description</label>
                                <input type="text" class="form-control" name="postDesc" id="postDesc">
                            </fieldset>
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <label for="postContent">Content</label>
                        <textarea class="form-control" name="postContent"></textarea>
                    </fieldset>
                    <button name="submit" type="submit" value="publish" class="btn btn-primary">Publish</button>
                    <button name="submit" type="submit" value="draft" class="btn btn-warning">Draft</button>
                </form>
            </div>
        </div>
    </div>
</div>