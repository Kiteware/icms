<!doctype html>
<html lang="<?php echo$this->container['settings']->production->site->language; ?>">
<head>
	<title><?php echo $this->data->keywords. " - " . $this->container['settings']->production->site->name; ?></title>
	<meta name="keywords" content="<?php echo $data->keywords; ?>">
	<meta name="description" content="<?php echo $data->description; ?>">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<meta name="googlebot" content="index,follow" />
	<meta name="robots" content="index,follow">
	<meta name="revisit-after" content="7 days">
	<meta name="viewport" content="width=device-width">
	<!-- Custom CSS --><link type='text/css' rel='stylesheet' href='/templates/default/css/style.min.css' />
	<!-- jQuery --><script src="/templates/admin/js/jquery-2.2.4.min.js"></script>
	<!-- PNotify JS --><script type="text/javascript" src="/templates/admin/js/pnotify.custom.min.js"></script>
	<!-- Favicon --><link href="/templates/admin/favicon.ico" rel="icon" type="image/x-icon" />
	<?php include_once("templates/admin/analyticstracking.php"); ?>
</head>
