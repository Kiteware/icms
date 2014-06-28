<?php 
require 'core/init.php';
$general->logged_in_protect();

?>
<!doctype html>
<html lang="en">
<head>
	<meta name="description" content="An Intelligent Content Management System">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">	
	<link type='text/css' rel='stylesheet' href='<?php echo $this->getCurrentTemplatePath();?>css/style.css' /> 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<title>Welcome to ICMS</title>
</head>
<div id="topbar"><div class="right">Hello</div></div>
<header>
		<a href="index.php" class="logo"><?php $this->widgetOutput('logoPosition');?></a>
		<nav>
	    <?php include 'includes/menu.php'; ?>
	    </nav>
	</header>
<body>
<section id="image">
  <div class="inner">
    <div class="center">
    <h1><strong>Intelligent</strong> Content Management System</h1>
    <p>In Alpha</p>
    </div>
  </div>
</section>

<section class="content">
  <div class="inner">
    <div class="center">
    <h1>Welcome</h1>
    <p><?php $this->widgetOutput('sidebarPosition');?></p>
    </div>
  </div>
</section>

<section id="contentAlt">
  <div class="inner">
    <div class="center">
    <h1></h1>
		<p><?php $this->widgetOutput('mainPosition');?></p>
    </div>
  </div>
</section>
</body>
<footer>
	 	&copy; 2014 <a href="http://nixx.co">NiXX</a>
</footer>
</html>
