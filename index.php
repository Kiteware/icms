<?php 
require 'core/init.php';
$general->logged_in_protect();

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<title>Welcome to ICMS</title>
</head>
<div id="topbar">Top Bar -</div>
<header>
		<a href="index.php" class="logo">ICMS</a>
		
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
    <h1>First Post</h1>
    <p>Content</p>
    <h2>Lorem Ipsum</h2>
    <p>More content</p>
    </div>
  </div>
</section>

<section id="contentAlt">
  <div class="inner">
    <div class="center">
    <h1>Second Post</h1>
    <p>Content</p>
    </div>
  </div>
</section>
</body>
<footer>
Copyright 2014 <em>NiXX</em>
</footer>
</html>
