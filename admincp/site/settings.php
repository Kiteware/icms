<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
?>
<div id="content">
    <div class="box">
        <div class="box-header">Settings</div>
        <div class="box-body">
            <div class="alert alert-warning" role="alert">
                <strong>Warning</strong> - Saving incorrect settings can break your website!
            </div>
            <form  method="post" action="" enctype="multipart/form-data">
                <div class="col-md-6">
                    <fieldset>
                        <h2 class="fs-title">Enter information about your website</h2>
                        <label>Site Name</label>
                        <input type="text" name="sitename" class="form-control" value="<?php echo $this->settings->production->site->name ?>" />
                        <label>Site File Location</label>
                        <input type="text" name="cwd" class="form-control" value="<?php echo $this->settings->production->site->cwd ?>" />
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="<?php echo $this->settings->production->site->url ?>" />
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $this->settings->production->site->email ?>" />
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <h2 class="fs-title">Database</h2>
                        <label>Database Host</label>
                        <input type="text" name="dbhost" class="form-control" value="<?php echo $this->settings->production->database->host ?>" />
                        <label>Database Port</label>
                        <input type="text" name="dbport" class="form-control" value="<?php echo $this->settings->production->database->port ?>" />
                        <label>Database Name</label>
                        <input type="text" name="dbname" class="form-control" value="<?php echo $this->settings->production->database->name ?>" />
                        <label>Database User</label>
                        <input type="text" name="dbuser" class="form-control" value="<?php echo $this->settings->production->database->user ?>" />
                    </fieldset>
                </div>

                <button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
            </form>
            <br />
            <form  method="post" action="" name="post" enctype="multipart/form-data">
                <button type="submit" name="cwd" class="btn btn-primary">Scan Working Directory</button>
            </form>

        </div>
    </div>
</div>
