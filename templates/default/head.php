<!doctype html>
<html lang="en">
<head>
	<title><?php echo $this->data->keywords. " - " . $this->container['settings']->production->site->name; ?></title>
	<meta name="keywords" content="<?php echo $data->keywords; ?>">
	<meta name="description" content="<?php echo $data->description; ?>">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<meta name="googlebot" content="index,follow" />
	<meta http-equiv="content-language" content="<?php echo$this->container['settings']->production->site->language; ?>">
	<meta name="robots" content="index,follow">
	<meta name="revisit-after" content="7 days">
	<!-- Custom CSS --><link type='text/css' rel='stylesheet' href='/templates/default/css/style.css' />
	<!-- Open Sans/Google Fonts --><link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<!-- jQuery --><script src="/templates/admin/js/jquery-2.2.4.min.js"></script>
	<!-- PNotify JS --><script type="text/javascript" src="/templates/admin/js/pnotify.custom.min.js"></script>
	<!-- PNotify CSS --><link href="/templates/admin/css/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" />
</head>
