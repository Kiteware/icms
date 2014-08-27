<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
<form  method="post" action="install.php" name="post" enctype="multipart/form-data">
	<fieldset>
		<h2 class="fs-title">Enter information about your website</h2>
		<h3 class="fs-subtitle">Site Name : Site Location : URL </h3>
		<input type="text" name="sitename" placeholder="<?php echo $settings->production->site->name ?>" />
		<input type="text" name="cwd" placeholder="<?php echo $settings->production->site->cwd ?>" />
		<input type="text" name="url" placeholder="<?php echo $settings->production->site->url ?>" />
	</fieldset>
	<fieldset>
		<h2 class="fs-title">Database</h2>
		<h3 class="fs-subtitle">MySQL user/database information</h3>
        <input type="text" name="dbconnection" placeholder="<?php echo $settings->production->database->connection ?>" />
		<input type="text" name="dbname" placeholder="<?php echo $settings->production->database->name ?>" />
		<input type="text" name="dbuser" placeholder="<?php echo $settings->production->database->user?>" />
		<input type="password" name="dbpassword" placeholder="<?php echo $settings->production->database->password?>" />
	</fieldset>
    <br />
<input type="submit" name="submit" class="submit action-button" value="Submit" />
</form>

    </div>
    </div>
	</div>
</body>