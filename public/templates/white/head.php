<!doctype html>
<html lang="<?php echo$this->container['settings']->production->site->language; ?>">
<head>
	<title><?php echo $data->keywords. " - " . $this->settings->production->site->name; ?></title>
	<meta name="keywords" content="<?php echo $data->keywords; ?>">
	<meta name="description" content="<?php echo $data->description; ?>">
	<meta charset="UTF-8" />
	<meta name="googlebot" content="index,follow" />
	<meta name="robots" content="index,follow">
	<meta name="revisit-after" content="7 days">
	<meta name="viewport" content="width=device-width">
	<link type='text/css' rel='stylesheet' href='/templates/decode/css/style.min.css' />
	<!-- jQuery --><script src="/templates/admin/js/jquery-2.2.4.min.js"></script>
	<!-- Bootstrap JS --><script src="/templates/admin/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<!-- PNotify JS --><script type="text/javascript" src="/templates/admin/js/pnotify.custom.min.js"></script>
	<!-- Favicon --><link href="/templates/admin/favicon.ico" rel="icon" type="image/x-icon" />
	<?php include("templates/admin/analyticstracking.php"); ?>
</head>
