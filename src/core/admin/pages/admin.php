<?php
defined('_ICMS') or die;

$installed = json_decode(file_get_contents("../version.json"), true);
$latest = json_decode(file_get_contents("https://raw.githubusercontent.com/dillonco/CMS/master/version.json"), true);

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">Info Panel</div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        Welcome to the ICMS Administrator panel. <br />
                        GitLab: <a href="https://gitlab.com/dillonco/icms">https://gitlab.com/dillonco/icms </a><br />
                        Language: <?php echo $this->settings->production->site->language ?>

                    </div>
                    <div class="col-sm-6">
                        Site Name: <?php echo $this->settings->production->site->name ?> <br />
                        Description: <?php echo $this->settings->production->site->description ?> <br />
                        Installed Version: <?php echo $installed['version'] ?> <br />
                        Latest Version: <?php echo $latest['version'] ?> <br />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">Menu Manager</div>
            <div class="box-body">
                <table class="table table-striped" id="menu-manager">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>URL</th>
                        <th>Position</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $navigation = $this->model->listNavAdmin();
                    $parent = '';
                    foreach ($navigation as $showNav) {
                        if($showNav['parent'] != '0') $parent = $navigation[$showNav['parent']-1]['nav_name'];
                        //displaying posts
                        echo "
            <tr><td>".$showNav['nav_id']."</td>
            <td>".$showNav['nav_name']."</td>
            <td>".$parent."</td>
			<td>".$showNav['nav_link']."</td>
			<td>".$showNav['nav_position']."</td>
            <td><a onClick='editNav(\"".$showNav['nav_name']."\", \"".$showNav['nav_link']."\", \"".$showNav['nav_position']."\", \"".$showNav['nav_id']."\", \"".$showNav['parent']."\");'><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a></td>
            <td><a onClick='deleteNav(\"".$showNav['nav_id']."\");'> <i class=\"fa fa-trash\" aria-hidden=\"true\"></i> </a></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <hr />
                <div id="create-menu">
                    <h3>Add a New Menu Item</h3>
                    <form class="reload-form" action="/admin/pages/menu" method="post" name="menu-manager">
                        <fieldset class="form-group">
                            <label>Name</label>
                            <input id="nav-name" name="nav-name-required" type="text" class="form-control" required />
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Link/URL</label>
                            <input id="nav-link" name="nav-link-required" type="text" class="form-control" value="/" required />
                        </fieldset>
                        <div class="row">
                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label>Parent ID</label>
                                <input id="parent" name="parent" type="number" class="form-control" value="0" />
                            </fieldset>
                            </div>
                            <div class="col-lg-6">
                            <fieldset class="form-group">
                                <label>Position </label>
                                <input id="nav-position" name="nav-position-required" type="number" class="form-control" size="10" value="5" required/>
                            </fieldset>
                            </div>
                        </div>
                            <input id="nav-id" name="nav-id" type="number"  type="hidden" style="display: none" />
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