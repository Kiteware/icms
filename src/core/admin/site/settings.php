<?php
defined('_ICMS') or die;
?>
<div class="box">
    <div class="box-header">Settings</div>
    <div class="box-body">
        <div class="alert alert-warning" role="alert">
            <strong>Warning</strong> - Saving incorrect settings can break your website!
        </div>
        <form method="post" action="/admin/site/settings" class="reload-form">
            <div class="row">
                <!-- General Site Settings -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">Site Information</div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="sitename">Site Name</label>
                                <input type="text" name="sitename" id="sitename" class="form-control" value="<?php echo $this->settings->production->site->name ?>" />
                            </div>
                            <div class="form-group">
                                <label for="sitedesc">Site Description</label>
                                <input type="text" name="sitedesc" id="sitedesc" class="form-control" value="<?php echo $this->settings->production->site->description ?>" />
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url" id="url" class="form-control" value="<?php echo $this->settings->production->site->url ?>" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $this->settings->production->site->email ?>" />
                            </div>
                            <div class="form-group">
                                <label for="template">Theme</label>
                                <select class="form-control" name="template" id="template">
                                    <?php
                                    foreach (glob('templates/*', GLOB_ONLYDIR) as $dir) {
                                        if (basename($dir) !== 'admin') {
                                            if (basename($dir) === $this->settings->production->site->template) {
                                                echo('<option selected>' . basename($dir) . '</option>');
                                            } else {
                                                echo('<option>' . basename($dir) . '</option>');
                                            }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div><!-- End of box-body -->
                    </div><!-- End of box -->
                </div><!-- End of col-md-6 -->
                <!-- Database Settings -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">Database</div>
                        <div class="box-body">
                            <div class="col-md-6 form-group" style="padding-left: 0px;">
                                <label for="dbhost">Database Host</label>
                                <input type="text" name="dbhost" id="dbhost" class="form-control" value="<?php echo $this->settings->production->database->host ?>" />
                            </div>
                            <div class="col-md-6 form-group" style="padding-right: 0px;">
                                <label for="dbport">Database Port</label>
                                <input type="text" name="dbport" id="dbport" class="form-control" value="<?php echo $this->settings->production->database->port ?>" />
                            </div>
                            <div class="form-group">
                                <label for="dbname">Database Name</label>
                                <input type="text" name="dbname" id="dbname" class="form-control" value="<?php echo $this->settings->production->database->name ?>" />
                            </div>
                            <div class="form-group">
                                <label for="dbuser">Database User</label>
                                <input type="text" name="dbuser" id="dbuser" class="form-control" value="<?php echo $this->settings->production->database->user ?>" />
                            </div>
                            <div class="form-group">
                                <label for="dbpass">Database Password</label>
                                <input type="text" name="dbpass" id="dbpass" class="form-control" value="unchanged"/>
                                <small class="text-muted">Leave this as "unchanged" unless you're changing the password</small>
                            </div>
                        </div><!-- End of box-body -->
                    </div><!-- End of box -->
                </div><!-- End of col-md-6 -->
            </div><!-- End of Row -->
            <div class="row">
                <!-- Email Settings -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">Email Configuration</div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="mailerType">Mailer</label>
                                <?php
                                $auth = $this->settings->production->email->auth;
                                ?>
                                <select class="form-control poop" name="mailerType" id="mailerType">
                                    <option value="basic" <?php if ($auth === 'BASIC') {
                                    echo 'selected';
                                } ?> >Basic</option>
                                    <option value="oauth" <?php if ($auth === 'XOAUTH2') {
                                    echo 'selected';
                                } ?> >OAUTH</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group" style="padding-left: 0px;">
                                <label for="emailHost">Host</label>
                                <input type="text" class="form-control" name="emailHost" id="emailHost" value="<?php echo $this->settings->production->email->host ?>">
                            </div>
                            <div class="col-md-6 form-group" style="padding-right: 0px;">
                                <label for="emailPort">Port</label>
                                <input type="text" class="form-control" name="emailPort" id="emailPort" value="<?php echo $this->settings->production->email->port ?>">
                            </div>
                            <div class="form-group">
                                <label for="emailUser">Email address</label>
                                <input type="email" class="form-control" name="emailUser" id="emailUser" value="<?php echo $this->settings->production->email->user ?>">
                            </div>
                            <div class="form-group" id="clientId" style="display: none;">
                                <label for="emailClientID">Client ID</label>
                                <input type="text" class="form-control" name="emailClientID" id="emailClientID" value="<?php echo $this->settings->production->email->clientid ?>">
                            </div>
                            <div class="form-group" id="clientSecret" style="display: none;">
                                <label for="emailClientSecret">Client Secret</label>
                                <input type="text" class="form-control" name="emailClientSecret" id="emailClientSecret" value="<?php echo $this->settings->production->email->clientsecret ?>">
                            </div>
                            <div class="form-group" id="basicPass">
                                <label for="emailUser">Email Password</label>
                                <input type="password" class="form-control" name="emailPassword" id="emailUser" value="<?php echo $this->settings->production->email->pass ?>">
                            </div>
                        </div><!-- End of box-body -->
                    </div><!-- End of box -->
                </div><!-- End of col-md-6 -->

                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">Addons</div>
                        <div class="box-body">
                            <!-- Google Analytics  -->
                            <fieldset class="form-group">
                                <label for="analytics">Google Analytics Tracking ID</label>
                                <input type="text" class="form-control" name="analytics" value="<?php echo $this->settings->production->site->analytics ?>">
                            </fieldset>
                            <!-- MailChimp Settings  -->
                            <fieldset class="form-group">
                                <label for="mailchimpapi">MailChimp API</label>
                                <input type="text" class="form-control" name="mailchimpapi" value="<?php echo $this->settings->production->addons->mailchimpapi ?>">
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="mailchimplistid">MailChimp List ID</label>
                                <input type="text" class="form-control" name="mailchimplistid" value="<?php echo $this->settings->production->addons->mailchimplistid ?>">
                            </fieldset>
                        </div><!-- End of box-body -->
                    </div><!-- End of box -->
                </div><!-- End of col-md-6 -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </div><!-- End of Row -->
        </form>
        <hr />

        <div class="row">
            <div class="col-md-12">
                <h3>Misc. Actions</h3>
                <form method="post" action="/admin/site/minifycss" class="no-reload-form" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-primary">Update CSS</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-top: 10px;">
                <form method="post" action="/admin/site/sitemap" class="no-reload-form" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-primary">Generate Sitemap</button>
                </form>
            </div>
        </div>
    </div>
</div>