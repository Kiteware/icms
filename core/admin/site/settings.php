<?php
defined('_ICMS') or die;

$clientid = $this->settings->production->email->clientid;
$basicPass = $this->settings->production->email->pass;
?>
<div class="box">
    <div class="box-header">Settings</div>
    <div class="box-body">
        <div class="alert alert-warning" role="alert">
            <strong>Warning</strong> - Saving incorrect settings can break your website!
        </div>
        <form method="post" action="/admin/site/settings" class="reload-form" enctype="multipart/form-data">
            <!-- General Site Settings -->
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <h2 class="fs-title">Enter information about your website</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Site Name</label>
                                <input type="text" name="sitename" class="form-control" value="<?php echo $this->settings->production->site->name ?>" />
                            </div>
                            <div class="col-md-6">
                                <label>Site Description</label>
                                <input type="text" name="sitedesc" class="form-control" value="<?php echo $this->settings->production->site->description ?>" />
                            </div>
                        </div>
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="<?php echo $this->settings->production->site->url ?>" />
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $this->settings->production->site->email ?>" />
                        <fieldset class="form-group">
                            <label for="template">Theme</label>
                            <select class="form-control" name="template">
                                <option selected"><?php echo $this->settings->production->site->template ?></option>
                                <option>default</option>
                                <option>decode</option>
                            </select>
                        </fieldset>
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
                        <label>Database Password</label>
                        <input type="text" name="dbpass" class="form-control" value="unchanged"/>
                        <small class="text-muted">Leave this as "unchanged" unless you're changing the password</small>
                    </fieldset>
                </div>
            </div>
            <!-- Email Settings -->
            <h1>Email Configuration</h1>
            <div class="row">
                <div class="col-md-8">
                    <fieldset class="form-group">
                        <label for="emailHost">Host</label>
                        <input type="text" class="form-control" name="emailHost" value="<?php echo $this->settings->production->email->host ?>">
                    </fieldset>
                </div>
                <div class="col-md-4">
                    <fieldset class="form-group">
                        <label for="emailPort">Port</label>
                        <input type="text" class="form-control" name="emailPort" value="<?php echo $this->settings->production->email->port ?>">
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="emailUser">Email address</label>
                        <input type="email" class="form-control" name="emailUser" value="<?php echo $this->settings->production->email->user ?>">
                    </fieldset>

                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="<?php echo $this->controller->isActive($clientid) ?>"><a href="#xoauth" aria-controls="home" role="tab" data-toggle="tab">XOAUTH</a></li>
                            <li role="presentation" class="<?php echo $this->controller->isActive($basicPass) ?>"><a href="#basic" aria-controls="profile" role="tab" data-toggle="tab">BASIC</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane <?php echo $this->controller->isActive($clientid) ?>" id="xoauth">
                                <?php
                                if (version_compare(phpversion(), '5.5.0', '<')) {
                                    echo("<div class=\"alert alert-danger\" role=\"alert\">You need at least php version 5.5 to use XOAUTH2, yours is <strong>".phpversion()."</strong></div>");
                                }
                                ?>
                                <fieldset class="form-group">
                                    <label for="emailClientID">Client ID</label>
                                    <input type="text" class="form-control" name="emailClientID" value="<?php echo $clientid ?>">
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="emailClientSecret">Client Secret</label>
                                    <input type="text" class="form-control" name="emailClientSecret" value="<?php echo $this->settings->production->email->clientsecret ?>">
                                </fieldset>
                                <!--
                                TODO - When a user adds oauth details, it should take them through the necessary steps
                                <form  method="post" action="/admin/site/oauth" enctype="multipart/form-data">
                                    <button type="submit" class="btn btn-primary">Setup Google OAuth</button>
                                </form>-->
                            </div>
                            <div role="tabpanel" class="tab-pane <?php echo $this->controller->isActive($basicPass) ?>" id="basic">
                                <fieldset class="form-group">
                                    <label for="emailUser">Email Password</label>
                                    <input type="password" class="form-control" name="emailPassword" value="<?php echo $basicPass ?>">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MailChimp Settings  -->
            <h1>MailChimp</h1>
            <div class="row">
                <div class="col-md-6">
                    <fieldset class="form-group">
                        <label for="mailchimpapi">MailChimp API</label>
                        <input type="text" class="form-control" name="mailchimpapi" value="<?php echo $this->settings->production->addons->mailchimpapi ?>">
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset class="form-group">
                        <label for="mailchimplistid">MailChimp List ID</label>
                        <input type="text" class="form-control" name="mailchimplistid" value="<?php echo $this->settings->production->addons->mailchimplistid ?>">
                    </fieldset>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        <hr />
        <h3>Misc. Actions</h3>
        <form method="post" action="/admin/site/minifycss" class="no-reload-form" enctype="multipart/form-data">
            <button type="submit" class="btn btn-primary">Update CSS</button>
        </form>
        <form method="post" action="/admin/site/sitemap" class="no-reload-form" enctype="multipart/form-data">
            <button type="submit" class="btn btn-primary">Generate Sitemap</button>
        </form>
    </div>
</div>